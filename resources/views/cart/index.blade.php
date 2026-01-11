<x-layouts.app title="سلة التسوق">
    <div class="min-h-screen bg-base-200 py-8">
        <div class="container mx-auto px-4">
            <!-- Page Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-primary mb-2">سلة التسوق</h1>
                <p class="text-gray-600">إدارة المنتجات في سلة التسوق الخاصة بك</p>
            </div>

            <!-- Cart Items Container -->
            <div id="cart-container" class="max-w-4xl mx-auto">
                <!-- Cart will be dynamically loaded here -->
            </div>

            <!-- Empty Cart Message -->
            <div id="empty-cart-message" class="hidden text-center py-12">
                <i data-lucide="shopping-cart" class="w-16 h-16 mx-auto text-gray-400 mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">سلة التسوق فارغة</h3>
                <p class="text-gray-600 mb-6">لم تقم بإضافة أي منتجات إلى سلة التسوق بعد</p>
                <a href="{{ route('products.public.list') }}" class="btn btn-primary">
                    استكشف المنتجات
                </a>
            </div>
        </div>
    </div>

    <!-- Cart Item Template -->
    <template id="cart-item-template">
        <div class="mb-4 border rounded-lg p-4 bg-white shadow-sm">
            <div class="flex flex-col md:flex-row items-start md:items-center">
                <div class="flex-shrink-0 mb-4 md:mb-0 md:mr-4">
                    <img class="w-20 h-20 object-cover rounded" src="" alt="" id="item-image">
                </div>
                <div class="flex-grow w-full">
                    <h3 class="font-semibold text-lg mb-1" id="item-name"></h3>
                    <p class="text-primary font-bold mb-2" id="item-price"></p>
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center">
                            <button class="btn btn-sm btn-outline decrease-qty" type="button">-</button>
                            <input type="number" min="1" value="" class="w-16 text-center border-t border-b border-gray-300 py-1 mx-1 qty-input" id="item-quantity">
                            <button class="btn btn-sm btn-outline increase-qty" type="button">+</button>
                        </div>
                        <button class="btn btn-error btn-sm remove-item" type="button">إزالة</button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Cart Summary Template -->
    <template id="cart-summary-template">
        <div class="card bg-base-100 shadow-xl mt-6">
            <div class="card-body">
                <h3 class="font-bold text-lg mb-4">ملخص الطلب</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span>الإجمالي:</span>
                        <span class="font-bold" id="summary-total"></span>
                    </div>
                    <div class="divider"></div>
                    <div class="flex justify-between text-lg font-bold">
                        <span>المجموع النهائي:</span>
                        <span class="text-primary" id="final-total"></span>
                    </div>
                </div>
                <div class="card-actions mt-4">
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-block checkout-btn">الدفع الآن</a>
                    <a href="{{ route('products.public.list') }}" class="btn btn-ghost btn-block">متابعة التسوق</a>
                </div>
            </div>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Load cart items when page loads
            loadCart();

            // Event delegation for cart actions
            document.getElementById('cart-container').addEventListener('click', function(e) {
                const itemElement = e.target.closest('.mb-4.border');
                if (!itemElement) return;

                const productId = itemElement.dataset.productId;

                // Handle quantity decrease
                if (e.target.classList.contains('decrease-qty')) {
                    updateItemQuantity(productId, -1);
                }
                // Handle quantity increase
                else if (e.target.classList.contains('increase-qty')) {
                    updateItemQuantity(productId, 1);
                }
                // Handle item removal
                else if (e.target.classList.contains('remove-item')) {
                    removeItemFromCart(productId);
                }
            });

            // Handle manual quantity input
            document.getElementById('cart-container').addEventListener('change', function(e) {
                if (e.target.classList.contains('qty-input')) {
                    const itemElement = e.target.closest('.mb-4.border');
                    const productId = itemElement.dataset.productId;
                    const newQuantity = parseInt(e.target.value);
                    
                    if (newQuantity >= 1) {
                        updateItemQuantity(productId, newQuantity, true); // Third param indicates manual input
                    } else {
                        e.target.value = 1; // Reset to 1 if less than 1
                    }
                }
            });


        });

        // Load cart items from server
        function loadCart() {
            fetch('{{ route("cart.items") }}')
                .then(response => response.json())
                .then(data => {
                    const cartContainer = document.getElementById('cart-container');
                    const emptyMessage = document.getElementById('empty-cart-message');
                    
                    if (data.items.length === 0) {
                        // Show empty cart message
                        emptyMessage.classList.remove('hidden');
                        cartContainer.innerHTML = '';
                        return;
                    }
                    
                    // Hide empty cart message
                    emptyMessage.classList.add('hidden');
                    
                    // Clear container
                    cartContainer.innerHTML = '';

                    // Add cart items
                    data.items.forEach(item => {
                        const template = document.getElementById('cart-item-template').content.cloneNode(true);
                        const itemElement = template.querySelector('.mb-4.border');
                        
                        // Set data attributes
                        itemElement.dataset.productId = item.id;
                        
                        // Fill item details
                        const imgElement = template.querySelector('#item-image');
                        if (item.image) {
                            imgElement.src = '{{ asset("storage/") }}' + item.image;
                        } else {
                            imgElement.src = '{{ asset("storage/uploads/default-product.jpg") }}';
                        }
                        imgElement.alt = item.name;
                        
                        template.querySelector('#item-name').textContent = item.name;
											template.querySelector('#item-price').textContent = item.formatted_total_price; // Total price for this item line
                        template.querySelector('#item-quantity').value = item.quantity;

                        cartContainer.appendChild(template);
                    });

                    // Add cart summary
                    const summaryTemplate = document.getElementById('cart-summary-template').content.cloneNode(true);
                    
									summaryTemplate.querySelector('#summary-total').textContent = data.formatted_total;
                    
									summaryTemplate.querySelector('#final-total').textContent = data.formatted_total;
                    
                    cartContainer.appendChild(summaryTemplate);

                    // Update cart count in header
                    updateCartCount(data.count);
                })
                .catch(error => {
                    console.error('Error loading cart:', error);
                    // Show error message to user
                    const cartContainer = document.getElementById('cart-container');
                    cartContainer.innerHTML = '<div class="alert alert-error"><div><i class="w-6 h-6 lu-info mr-2"></i><span>حدث خطأ أثناء تحميل السلة</span></div></div>';
                });
        }

        // Update item quantity
        function updateItemQuantity(productId, change, isManualInput = false) {
            let newQuantity;
            
            if (isManualInput) {
                newQuantity = change; // change parameter is the new quantity when manual input
            } else {
							const itemElement = document.querySelector(`#cart-container [data-product-id="${productId}"]`);
                const qtyInput = itemElement.querySelector('.qty-input');
                const currentQty = parseInt(qtyInput.value);
                
                if (change === -1) {
                    newQuantity = Math.max(1, currentQty - 1);
                } else if (change === 1) {
                    newQuantity = currentQty + 1;
                } else {
                    newQuantity = currentQty; // Keep current quantity if change is invalid
                }
            }
            
            // Update the input field with new quantity
					const itemElement = document.querySelector(`#cart-container [data-product-id="${productId}"]`);
            const qtyInput = itemElement.querySelector('.qty-input');
            qtyInput.value = newQuantity;

            // Send request to update quantity in session
            fetch(`{{ route("cart.update", ":id") }}`.replace(':id', productId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    quantity: newQuantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload cart to reflect changes
                    loadCart();
                } else {
                    alert('خطأ في تحديث الكمية: ' + data.message);
                    // Reset input to previous value
                    loadCart(); // Reload to restore previous values
                }
            })
            .catch(error => {
                console.error('Error updating quantity:', error);
                alert('حدث خطأ أثناء تحديث الكمية');
                loadCart(); // Reload to restore previous values
            });
        }

        // Remove item from cart
        // Remove item from cart - Delegated to new implementation below using modal
        // function removeItemFromCart(productId) { ... }

        // Update cart count in header (assuming there's a global cart counter)
        function updateCartCount(count) {
            const cartCountElement = document.querySelector('.cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = count;
                if (count > 0) {
                    cartCountElement.classList.remove('hidden');
                } else {
                    cartCountElement.classList.add('hidden');
                }
            }
        }
    </script>

    <!-- Initialize lucide icons -->
    <script>
        if (window.lucide && typeof window.lucide.createIcons === 'function') {
            window.lucide.createIcons();
        }
    </script>
<x-confirm-modal id="remove-item-modal" title="إزالة المنتج"
	message="هل أنت متأكد من رغبتك في إزالة هذا المنتج من السلة؟" confirmText="نعم، قم بالإزالة" />

<script>
	let itemToRemoveId = null;

	document.addEventListener('DOMContentLoaded', function () {
		// Setup confirmation modal listener
		const confirmBtn = document.getElementById('remove-item-modal-confirm-btn');
		if (confirmBtn) {
			confirmBtn.addEventListener('click', function () {
				if (itemToRemoveId) {
					performRemoveItem(itemToRemoveId);
					itemToRemoveId = null;
					document.getElementById('remove-item-modal').close();
				}
			});
		}
	});

	// Original removeItemFromCart updated to show modal
		function removeItemFromCart(productId) {
				itemToRemoveId = productId;
				document.getElementById('remove-item-modal').showModal();
			}

			// Extracted logic to perform the actual fetch request
			function performRemoveItem(productId) {
				fetch(`{{ route("cart.remove", ":id") }}`.replace(':id', productId), {
					method: 'POST',
					headers: {
						'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
						'Accept': 'application/json'
					}
				})
					.then(response => response.json())
					.then(data => {
						if (data.success) {
					loadCart();
				} else {
					alert('خطأ في إزالة المنتج: ' + data.message);
				}
			})
			.catch(error => {
				console.error('Error removing item:', error);
				alert('حدث خطأ أثناء إزالة المنتج');
			});
	}
</script>
</x-layouts.app>