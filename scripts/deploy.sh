#!/usr/bin/env bash
set -euo pipefail

# Safer Laravel deploy for shared hosting (Hostinger/LiteSpeed/Apache)
# - Builds locally, uploads via rsync
# - Avoids deleting critical webroot files by default
# - Preserves storage/, public/storage
# - Fixes permissions to prevent 403

# Usage:
#   scripts/deploy.sh
#   DEPLOY_HOST=... DEPLOY_USER=... DEPLOY_PATH=... DEPLOY_PORT=22 scripts/deploy.sh
#   scripts/deploy.sh --env-file .deploy.env

ENV_FILE=".deploy.env"
if [[ ${1-} == "--env-file" ]]; then
  ENV_FILE=${2-}; shift 2 || true
fi

if [[ -f "$ENV_FILE" ]]; then
  set -a; . "$ENV_FILE"; set +a
fi

: "${DEPLOY_HOST:?Missing DEPLOY_HOST}"
: "${DEPLOY_USER:?Missing DEPLOY_USER}"
: "${DEPLOY_PATH:?Missing DEPLOY_PATH}"
DEPLOY_PORT="${DEPLOY_PORT:-22}"

# If you REALLY want to overwrite the server .htaccess, set to "true"
OVERWRITE_HTACCESS="${OVERWRITE_HTACCESS:-false}"

# Set to "true" if your DEPLOY_PATH is the document root (public_html)
# and you need to copy public/* to root
FLATTEN_PUBLIC="${FLATTEN_PUBLIC:-true}"

echo "Host: $DEPLOY_HOST"
echo "User: $DEPLOY_USER"
echo "Path: $DEPLOY_PATH"
echo "Port: $DEPLOY_PORT"
echo "Overwrite .htaccess: $OVERWRITE_HTACCESS"
echo "Flatten public/: $FLATTEN_PUBLIC"

command -v ssh >/dev/null || { echo "ssh not found" >&2; exit 1; }
command -v rsync >/dev/null || { echo "rsync not found" >&2; exit 1; }
command -v composer >/dev/null || { echo "composer not found" >&2; exit 1; }

echo "Installing Composer dependencies (prod)…"
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

if [[ -f package.json ]]; then
  echo "Building frontend assets…"
  if command -v npm >/dev/null 2>&1; then
    if [[ -f package-lock.json || -f npm-shrinkwrap.json ]]; then
      npm ci
    else
      npm install
    fi
    if npm run | grep -q " build"; then
      npm run build
    fi
  else
    echo "npm not found; skipping asset build" >&2
  fi
fi

# Build excludes
EXCLUDES_FILE=$(mktemp)
cat > "$EXCLUDES_FILE" << 'EOF'
.git/
.github/
.vscode/
.DS_Store
tests/
node_modules/
*.log
.env
.env.*
.deploy.env
storage/
public/storage/
public/hot
hot
bootstrap/cache/*.php
EOF

# By default DO NOT overwrite or delete these critical files:
if [[ "${OVERWRITE_HTACCESS}" != "true" ]]; then
  echo ".htaccess" >> "$EXCLUDES_FILE"
fi
# Remove index.php exclusion - we DO want to upload it
# echo "index.php" >> "$EXCLUDES_FILE"

# Upload to a temporary directory first, then rsync into place
TMP_DIR=".__deploy_tmp.$(date +%Y%m%d%H%M%S)"

echo "Creating temp dir and entering maintenance mode…"
ssh -p "$DEPLOY_PORT" "${DEPLOY_USER}@${DEPLOY_HOST}" bash -lc "set -e
  mkdir -p '${DEPLOY_PATH}/${TMP_DIR}'
  cd '${DEPLOY_PATH}' || exit 1
  if command -v php >/dev/null 2>&1 && [[ -f artisan ]]; then
    php artisan down || true
  fi
"

echo "Uploading to temp dir via rsync…"
rsync -az --delete-after \
  --exclude-from="$EXCLUDES_FILE" \
  -e "ssh -p ${DEPLOY_PORT}" \
  ./ "${DEPLOY_USER}@${DEPLOY_HOST}:${DEPLOY_PATH}/${TMP_DIR}/"

# Now sync temp dir into the live dir (preserving protected paths)
# We still exclude storage and public/storage here to prevent deletion
echo "Syncing temp dir into live path…"

# Build rsync exclude arguments
RSYNC_EXCLUDES="--exclude 'storage/' --exclude 'public/storage/' --exclude 'bootstrap/cache/*.php'"
if [[ "${OVERWRITE_HTACCESS}" != "true" ]]; then
  RSYNC_EXCLUDES="${RSYNC_EXCLUDES} --exclude '.htaccess'"
fi

ssh -p "$DEPLOY_PORT" "${DEPLOY_USER}@${DEPLOY_HOST}" bash -lc "set -e
  cd '${DEPLOY_PATH}'
  rsync -az --delete-after \
    ${RSYNC_EXCLUDES} \
    './${TMP_DIR}/' './'
  rm -rf './${TMP_DIR}'
"

# If FLATTEN_PUBLIC is true, copy public/* to root (for shared hosting)
if [[ "${FLATTEN_PUBLIC}" == "true" ]]; then
  echo "Flattening public/ directory to root (shared hosting mode)…"
  ssh -p "$DEPLOY_PORT" "${DEPLOY_USER}@${DEPLOY_HOST}" bash -lc "set -e
    cd '${DEPLOY_PATH}'
    # Copy public/* to root, but don't overwrite .htaccess unless specified
    if [[ '${OVERWRITE_HTACCESS}' == 'true' ]]; then
      cp -rf public/* ./
    else
      rsync -a --exclude '.htaccess' public/ ./
    fi
    echo 'Public files flattened to root'
  "
fi

echo "Fixing permissions (avoid 403)…"
ssh -p "$DEPLOY_PORT" "${DEPLOY_USER}@${DEPLOY_HOST}" bash -lc "set -e
  cd '${DEPLOY_PATH}'
  
  # Set directory permissions (755 = rwxr-xr-x)
  find . -type d -not -path './.git*' -exec chmod 755 {} + 2>/dev/null || true
  
  # Set file permissions (644 = rw-r--r--)
  find . -type f -not -path './.git*' -exec chmod 644 {} + 2>/dev/null || true
  
  # Critical files need correct permissions
  chmod 644 index.php 2>/dev/null || true
  chmod 644 .htaccess 2>/dev/null || true
  chmod 755 public 2>/dev/null || true
  find public -type f -exec chmod 644 {} + 2>/dev/null || true
  find public -type d -exec chmod 755 {} + 2>/dev/null || true
  
  # Storage and bootstrap/cache need write permissions
  chmod -R 775 storage bootstrap/cache 2>/dev/null || true
  
  # Make artisan executable
  chmod 755 artisan 2>/dev/null || true
"

echo "Clearing all caches and optimizing…"
ssh -p "$DEPLOY_PORT" "${DEPLOY_USER}@${DEPLOY_HOST}" bash -lc "set -e
  cd '${DEPLOY_PATH}'
  if command -v php >/dev/null 2>&1 && [[ -f artisan ]]; then
    # Clear ALL caches thoroughly
    php artisan cache:clear || true
    php artisan config:clear || true
    php artisan route:clear || true
    php artisan view:clear || true
    php artisan clear-compiled || true
    
    # Remove cached files manually as backup
    rm -f bootstrap/cache/config.php || true
    rm -f bootstrap/cache/routes-v7.php || true
    rm -f bootstrap/cache/services.php || true
    rm -f bootstrap/cache/packages.php || true
    
    # Run migrations
    php artisan migrate --force || true
    
    # Rebuild caches
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
    
    # Exit maintenance mode
    php artisan up || true
  fi
"

echo "Ensuring storage symlink exists…"
ssh -p "$DEPLOY_PORT" "${DEPLOY_USER}@${DEPLOY_HOST}" bash -lc "set -e
  cd '${DEPLOY_PATH}'
   if [[ -d '${DEPLOY_PATH}/storage/app/public' ]]; then
    mkdir -p public
    if [[ -e '${DEPLOY_PATH}/public/storage' && ! -L '${DEPLOY_PATH}/public/storage' ]]; then
      rm -rf '${DEPLOY_PATH}/public/storage'
    fi
    ln -sfn '${DEPLOY_PATH}/storage/app/public' '${DEPLOY_PATH}/public/storage'
  fi
"

echo "Restarting OPcache (if available)…"
ssh -p "$DEPLOY_PORT" "${DEPLOY_USER}@${DEPLOY_HOST}" bash -lc "
  cd '${DEPLOY_PATH}'
  # Touch a file to trigger LiteSpeed/OPcache reload
  touch '${DEPLOY_PATH}/.htaccess' || true
  # Try to restart OPcache via a PHP script
  php -r 'if (function_exists(\"opcache_reset\")) { opcache_reset(); echo \"OPcache cleared\n\"; }' || true
"

rm -f "$EXCLUDES_FILE"

echo ""
echo "Verifying deployment…"
ssh -p "$DEPLOY_PORT" "${DEPLOY_USER}@${DEPLOY_HOST}" bash -lc "
  cd '${DEPLOY_PATH}'
  echo 'Critical files:'
  ls -la index.php .htaccess 2>/dev/null || true
  if [ ! -f index.php ]; then
    echo 'WARNING: index.php is MISSING!'
  fi
  echo ''
  echo 'Public directory contents:'
  ls -la public/ | head -5 || true
  echo ''
  echo 'Recently modified PHP files:'
  find . -maxdepth 2 -name '*.php' -type f -mmin -10 2>/dev/null | head -10 || true
"

echo " "
echo "Deploy completed. If changes still don't appear, check:"
echo "  1. Browser cache (hard refresh with Ctrl+Shift+R)"
echo "  2. Hostinger cache panel"
echo "  3. APP_ENV and APP_DEBUG settings in .env"