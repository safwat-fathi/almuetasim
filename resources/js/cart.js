// cart-clean.js - single clean implementation (no duplicates)
const Cart = {
    async addToCart(productId) {
        const meta = document.querySelector('meta[name="csrf-token"]');
        const csrf = meta ? meta.getAttribute('content') : null;
        const headers = { 'Content-Type': 'application/json', 'Accept': 'application/json' };
        if (csrf) headers['X-CSRF-TOKEN'] = csrf;

        const res = await fetch(`/cart/add/${productId}`, { method: 'POST', headers });
        const data = await res.json();
        if (data && data.success) {
            this.updateCartCount(data.count || 0);
            try { document.dispatchEvent(new CustomEvent('cartUpdated', { detail: { count: data.count, message: data.message || null } })); } catch (e) { }
            this.refreshMiniCart().catch(() => { });
            this.showNotification('تمت إضافة المنتج إلى سلة التسوق بنجاح', 'success');
        } else {
            this.showNotification('خطأ: ' + (data?.message || 'غير معروف'), 'error');
        }
        return data;
    },

    async removeFromCart(productId) {
        const meta = document.querySelector('meta[name="csrf-token"]');
        const csrf = meta ? meta.getAttribute('content') : null;
        const headers = { 'Accept': 'application/json' };
        if (csrf) headers['X-CSRF-TOKEN'] = csrf;

        const res = await fetch(`/cart/remove/${productId}`, { method: 'POST', headers });
        const data = await res.json();
        if (data && data.success) {
            this.updateCartCount(data.count || 0);
            try { document.dispatchEvent(new CustomEvent('cartUpdated', { detail: { count: data.count } })); } catch (e) { }
            this.refreshMiniCart().catch(() => { });
        } else {
            this.showNotification('خطأ: ' + (data?.message || 'غير معروف'), 'error');
        }
        return data;
    },

    async updateQuantity(productId, quantity) {
        const meta = document.querySelector('meta[name="csrf-token"]');
        const csrf = meta ? meta.getAttribute('content') : null;
        const headers = { 'Content-Type': 'application/json', 'Accept': 'application/json' };
        if (csrf) headers['X-CSRF-TOKEN'] = csrf;

        const res = await fetch(`/cart/update/${productId}`, { method: 'POST', headers, body: JSON.stringify({ quantity }) });
        const data = await res.json();
        if (data && data.success) {
            this.updateCartCount(data.count || 0);
            try { document.dispatchEvent(new CustomEvent('cartUpdated', { detail: { count: data.count } })); } catch (e) { }
            this.refreshMiniCart().catch(() => { });
        } else {
            this.showNotification('خطأ: ' + (data?.message || 'غير معروف'), 'error');
        }
        return data;
    },

    async getCartItems() { const res = await fetch('/cart/items'); return res.json(); },
    async getCartCount() { try { const res = await fetch('/cart/count'); const data = await res.json(); return data.count || 0; } catch (e) { return 0; } },

    updateCartCount(count) {
        document.querySelectorAll('.cart-count').forEach(el => { el.textContent = count; if (count > 0) { el.classList.remove('hidden'); if (el.parentElement) el.parentElement.classList.remove('hidden'); } else { el.classList.add('hidden'); if (el.parentElement) el.parentElement.classList.add('hidden'); } });
        const navbar = document.getElementById('cart-count'); if (navbar) { navbar.textContent = count; if (count > 0) { navbar.classList.remove('hidden'); navbar.style.display = 'inline-block'; } else { navbar.classList.add('hidden'); navbar.style.display = 'none'; } }
    },

    async refreshMiniCart() {
        try {
            const data = await this.getCartItems();
            const container = document.getElementById('cart-items'); if (!container) return;
            if (data.items && data.items.length) {
                container.innerHTML = ''; data.items.forEach(item => {
                    const priceFormatter = new Intl.NumberFormat('ar-EG', { style: 'currency', currency: 'EGP', currencyDisplay: 'narrowSymbol' });
                    const formattedPrice = priceFormatter.format((item.price || 0) * (item.quantity || 1));
                    const html = `<div class="cart-item flex items-center justify-between p-2 rounded hover:bg-base-200" data-product-id="${item.id}">` +
                        `<a href="/product/${item.slug || ''}" class="flex items-center gap-2 flex-1">` +
                        `<img src="${item.image || 'https://placehold.co/60x60'}" alt="${item.name}" class="w-12 h-12 object-cover rounded" />` +
                        `<div class="flex-1 min-w-0"><div class="text-sm font-semibold truncate">${item.name}</div>` +
                        `<div class="text-xs text-base-content/70">الكمية: ${item.quantity || 1}</div>` +
                        `<div class="text-xs flex items-center gap-1"><span class="font-medium">${formattedPrice}</span></div></div></a>` +
                        `<button class="btn btn-ghost btn-xs btn-circle remove-from-cart-navbar" data-product-id="${item.id}" title="إزالة من السلة"><i data-lucide="x" class="w-4 h-4"></i></button></div>`;
                    container.insertAdjacentHTML('beforeend', html);
                }); if (window.lucide && typeof window.lucide.createIcons === 'function') window.lucide.createIcons();
            } else { container.innerHTML = '<p class="text-base-content/70 text-sm">سلة التسوق فارغة</p>'; }
        } catch (e) { }
    },

    showNotification(message, type = 'info') { const notification = document.createElement('div'); notification.className = `alert alert-${type === 'error' ? 'error' : type === 'success' ? 'success' : 'info'} fixed top-4 right-4 z-[9999] max-w-xs transition-opacity duration-300`; notification.style.cssText = 'transform: translateX(0); opacity: 1;'; notification.innerHTML = `<div><span>${message}</span></div>`; document.body.appendChild(notification); setTimeout(() => { notification.style.transition = 'opacity 0.3s, transform 0.3s'; notification.style.opacity = '0'; notification.style.transform = 'translateX(100%)'; setTimeout(() => { if (notification.parentNode) notification.parentNode.removeChild(notification); }, 300); }, 3000); }
};

// DOM bindings
document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (e) {
        const addBtn = e.target.closest('.add-to-cart-btn'); if (addBtn) { const productId = addBtn.dataset.productId; if (productId) { e.preventDefault(); Cart.addToCart(productId).catch(() => { }); } return; }
        const removeBtn = e.target.closest('.remove-from-cart-navbar'); if (removeBtn) { const productId = removeBtn.getAttribute('data-product-id'); if (productId) { e.preventDefault(); Cart.removeFromCart(productId).catch(() => { }); } return; }
    });
    Cart.getCartCount().then(count => Cart.updateCartCount(count));
});
