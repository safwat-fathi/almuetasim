# Plan: Fix product-card image URL to use /storage/uploads

## Goal
Ensure images in `resources/views/components/product-card.blade.php` resolve correctly by pointing to `/storage/uploads` instead of `/uploads`, matching the working behavior seen in `resources/views/admin/products/index.blade.php`.

## Approach
- Implement a safe, consistent URL resolver inside the component:
  - If `$image` is an absolute URL (`http://` or `https://`) or already starts with `/storage`, keep it as-is.
  - Otherwise, generate a URL using `Storage::url($image)` and fall back to `asset('storage/' . ltrim($image, '/'))` to cover typical disk setups.
  - This mirrors the working pattern in `index.blade.php` and fixes cases where `$image` is like `uploads/...` or `/uploads/...` by resolving to `/storage/uploads/...`.

## Files Affected
- Update: `resources/views/components/product-card.blade.php`

## Implementation Notes
- Add a small `@php` block in the component to compute `$imageUrl` using the logic above, then bind `src` to `$imageUrl`.
- Keep all existing Tailwind classes and structure unchanged.

## Validation
- Manually verify on pages using the component:
  - `resources/views/home.blade.php`
  - `resources/views/search-results.blade.php`
  - `resources/views/category.blade.php`
  - `resources/views/product.blade.php` (related products section)
- Confirm images load and URLs reflect `/storage/uploads/...` where appropriate.

## Rollback Plan
- Revert to the previous `src="{{ $image }}"` if any unexpected regressions occur.

