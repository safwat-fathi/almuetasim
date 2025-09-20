<!DOCTYPE html>
<html lang="en" data-theme="light">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Admin Panel - Ecommerce Dashboard</title>
		<script src="https://cdn.tailwindcss.com"></script>
		<link
			href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css"
			rel="stylesheet"
			type="text/css"
		/>
		<script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.js"></script>
		<style>
			.sidebar-transition {
				transition: transform 0.3s ease-in-out;
			}
			@media (max-width: 768px) {
				.sidebar-hidden {
					transform: translateX(-100%);
				}
			}
		</style>
	</head>
	<body class="bg-base-100">
		<div class="drawer lg:drawer-open">
			<input id="drawer-toggle" type="checkbox" class="drawer-toggle" />

			<!-- Main Content -->
			<div class="drawer-content flex flex-col">
				<!-- Navbar -->
				<div class="navbar bg-base-200 shadow-sm">
					<div class="flex-none lg:hidden">
						<label for="drawer-toggle" class="btn btn-square btn-ghost">
							<i data-lucide="menu" class="w-5 h-5"></i>
						</label>
					</div>
					<div class="flex-1">
						<h1 class="text-xl font-bold">Admin Dashboard</h1>
					</div>
					<div class="flex-none gap-2">
						<div class="dropdown dropdown-end">
							<div
								tabindex="0"
								role="button"
								class="btn btn-ghost btn-circle avatar"
							>
								<div
									class="w-10 rounded-full bg-primary text-primary-content flex items-center justify-center"
								>
									<i data-lucide="user" class="w-5 h-5"></i>
								</div>
							</div>
							<ul
								tabindex="0"
								class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52"
							>
								<li><a>Profile</a></li>
								<li><a>Settings</a></li>
								<li><a>Logout</a></li>
							</ul>
						</div>
					</div>
				</div>

				<!-- Page Content -->
				<div class="flex-1 p-6">
					<!-- Stats Cards -->
					<div
						class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6"
					>
						<div
							class="stat bg-gradient-to-r from-primary to-primary-focus text-primary-content rounded-lg shadow-lg"
						>
							<div class="stat-figure">
								<i data-lucide="package" class="w-8 h-8"></i>
							</div>
							<div class="stat-title text-primary-content/80">
								Total Products
							</div>
							<div class="stat-value" id="total-products">156</div>
							<div class="stat-desc text-primary-content/60">
								↗︎ 12% from last month
							</div>
						</div>

						<div
							class="stat bg-gradient-to-r from-secondary to-secondary-focus text-secondary-content rounded-lg shadow-lg"
						>
							<div class="stat-figure">
								<i data-lucide="trending-up" class="w-8 h-8"></i>
							</div>
							<div class="stat-title text-secondary-content/80">
								Active Products
							</div>
							<div class="stat-value" id="active-products">142</div>
							<div class="stat-desc text-secondary-content/60">
								91% of total
							</div>
						</div>

						<div
							class="stat bg-gradient-to-r from-accent to-accent-focus text-accent-content rounded-lg shadow-lg"
						>
							<div class="stat-figure">
								<i data-lucide="alert-triangle" class="w-8 h-8"></i>
							</div>
							<div class="stat-title text-accent-content/80">Low Stock</div>
							<div class="stat-value" id="low-stock">8</div>
							<div class="stat-desc text-accent-content/60">
								Needs attention
							</div>
						</div>

						<div
							class="stat bg-gradient-to-r from-info to-info-focus text-info-content rounded-lg shadow-lg"
						>
							<div class="stat-figure">
								<i data-lucide="eye-off" class="w-8 h-8"></i>
							</div>
							<div class="stat-title text-info-content/80">Inactive</div>
							<div class="stat-value" id="inactive-products">14</div>
							<div class="stat-desc text-info-content/60">Hidden products</div>
						</div>
					</div>

					<!-- Products Management -->
					<div class="card bg-base-100 shadow-lg">
						<div class="card-body">
							<div
								class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6"
							>
								<h2 class="card-title text-2xl">Product Management</h2>
								<button class="btn btn-primary" onclick="openAddProductModal()">
									<i data-lucide="plus" class="w-4 h-4 mr-2"></i>
									Add Product
								</button>
							</div>

							<!-- Search and Filter -->
							<div class="flex flex-col sm:flex-row gap-4 mb-6">
								<div class="form-control flex-1">
									<div class="input-group">
										<input
											type="text"
											placeholder="Search products..."
											class="input input-bordered flex-1"
											id="search-input"
										/>
										<button class="btn btn-square">
											<i data-lucide="search" class="w-4 h-4"></i>
										</button>
									</div>
								</div>
								<select
									class="select select-bordered w-full sm:w-auto"
									id="category-filter"
								>
									<option value="">All Categories</option>
									<option value="electronics">Electronics</option>
									<option value="clothing">Clothing</option>
									<option value="books">Books</option>
									<option value="home">Home & Garden</option>
								</select>
								<select
									class="select select-bordered w-full sm:w-auto"
									id="status-filter"
								>
									<option value="">All Status</option>
									<option value="active">Active</option>
									<option value="inactive">Inactive</option>
									<option value="low-stock">Low Stock</option>
								</select>
							</div>

							<!-- Products Table -->
							<div class="overflow-x-auto">
								<table class="table table-zebra">
									<thead>
										<tr>
											<th>
												<label>
													<input
														type="checkbox"
														class="checkbox"
														id="select-all"
													/>
												</label>
											</th>
											<th>Product</th>
											<th>Category</th>
											<th>Price</th>
											<th>Stock</th>
											<th>Status</th>
											<th>Actions</th>
										</tr>
									</thead>
									<tbody id="products-table-body">
										<!-- Products will be populated here -->
									</tbody>
								</table>
							</div>

							<!-- Pagination -->
							<div class="flex justify-center mt-6">
								<div class="btn-group">
									<button class="btn" id="prev-page">«</button>
									<button class="btn btn-active" id="current-page">1</button>
									<button class="btn" id="next-page">»</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Sidebar -->
			<div class="drawer-side">
				<label
					for="drawer-toggle"
					aria-label="close sidebar"
					class="drawer-overlay"
				></label>
				<aside class="min-h-full w-64 bg-base-200 text-base-content">
					<div class="p-4">
						<div class="flex items-center gap-3 mb-8">
							<div
								class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center"
							>
								<i data-lucide="store" class="w-6 h-6 text-primary-content"></i>
							</div>
							<span class="text-xl font-bold">EcomAdmin</span>
						</div>
					</div>
					<ul class="menu p-4 space-y-2">
						<li>
							<a class="active">
								<i data-lucide="layout-dashboard" class="w-5 h-5"></i>
								Dashboard
							</a>
						</li>
						<li>
							<a>
								<i data-lucide="package" class="w-5 h-5"></i>
								Products
								<span class="badge badge-primary">156</span>
							</a>
						</li>
						<li>
							<a>
								<i data-lucide="shopping-cart" class="w-5 h-5"></i>
								Orders
							</a>
						</li>
						<li>
							<a>
								<i data-lucide="users" class="w-5 h-5"></i>
								Customers
							</a>
						</li>
						<li>
							<a>
								<i data-lucide="bar-chart-3" class="w-5 h-5"></i>
								Analytics
							</a>
						</li>
						<li>
							<a>
								<i data-lucide="tag" class="w-5 h-5"></i>
								Categories
							</a>
						</li>
						<li>
							<a>
								<i data-lucide="settings" class="w-5 h-5"></i>
								Settings
							</a>
						</li>
					</ul>
				</aside>
			</div>
		</div>

		<!-- Add Product Modal -->
		<dialog id="add_product_modal" class="modal">
			<div class="modal-box w-11/12 max-w-2xl">
				<form method="dialog">
					<button
						class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
					>
						✕
					</button>
				</form>
				<h3 class="font-bold text-lg mb-4">Add New Product</h3>

				<div class="space-y-4">
					<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
						<div class="form-control">
							<label class="label">
								<span class="label-text">Product Name</span>
							</label>
							<input
								type="text"
								class="input input-bordered"
								id="product-name"
								placeholder="Enter product name"
							/>
						</div>
						<div class="form-control">
							<label class="label">
								<span class="label-text">SKU</span>
							</label>
							<input
								type="text"
								class="input input-bordered"
								id="product-sku"
								placeholder="Product SKU"
							/>
						</div>
					</div>

					<div class="form-control">
						<label class="label">
							<span class="label-text">Description</span>
						</label>
						<textarea
							class="textarea textarea-bordered h-24"
							id="product-description"
							placeholder="Product description"
						></textarea>
					</div>

					<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
						<div class="form-control">
							<label class="label">
								<span class="label-text">Price ($)</span>
							</label>
							<input
								type="number"
								step="0.01"
								class="input input-bordered"
								id="product-price"
								placeholder="0.00"
							/>
						</div>
						<div class="form-control">
							<label class="label">
								<span class="label-text">Stock Quantity</span>
							</label>
							<input
								type="number"
								class="input input-bordered"
								id="product-stock"
								placeholder="0"
							/>
						</div>
						<div class="form-control">
							<label class="label">
								<span class="label-text">Category</span>
							</label>
							<select class="select select-bordered" id="product-category">
								<option value="">Select category</option>
								<option value="electronics">Electronics</option>
								<option value="clothing">Clothing</option>
								<option value="books">Books</option>
								<option value="home">Home & Garden</option>
							</select>
						</div>
					</div>

					<div class="form-control">
						<label class="label">
							<span class="label-text">Product Image URL</span>
						</label>
						<input
							type="url"
							class="input input-bordered"
							id="product-image"
							placeholder="https://example.com/image.jpg"
						/>
					</div>

					<div class="form-control">
						<label class="label cursor-pointer">
							<span class="label-text">Active Product</span>
							<input
								type="checkbox"
								class="toggle toggle-primary"
								id="product-active"
								checked
							/>
						</label>
					</div>
				</div>

				<div class="modal-action">
					<button class="btn btn-ghost" onclick="closeAddProductModal()">
						Cancel
					</button>
					<button class="btn btn-primary" onclick="addProduct()">
						Add Product
					</button>
				</div>
			</div>
		</dialog>

		<script>
			// Sample product data
			let products = [
				{
					id: 1,
					name: "Wireless Headphones",
					sku: "WH-001",
					category: "electronics",
					price: 99.99,
					stock: 25,
					status: "active",
					image:
						"https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=100&h=100&fit=crop",
				},
				{
					id: 2,
					name: "Cotton T-Shirt",
					sku: "CT-002",
					category: "clothing",
					price: 29.99,
					stock: 5,
					status: "low-stock",
					image:
						"https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=100&h=100&fit=crop",
				},
				{
					id: 3,
					name: "Programming Book",
					sku: "PB-003",
					category: "books",
					price: 45.0,
					stock: 12,
					status: "active",
					image:
						"https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=100&h=100&fit=crop",
				},
				{
					id: 4,
					name: "Garden Tools Set",
					sku: "GT-004",
					category: "home",
					price: 75.5,
					stock: 0,
					status: "inactive",
					image:
						"https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=100&h=100&fit=crop",
				},
				{
					id: 5,
					name: "Smartphone Case",
					sku: "SC-005",
					category: "electronics",
					price: 19.99,
					stock: 50,
					status: "active",
					image:
						"https://images.unsplash.com/photo-1556656793-08538906a9f8?w=100&h=100&fit=crop",
				},
			];

			let currentPage = 1;
			let productsPerPage = 10;

			// Initialize Lucide icons
			lucide.createIcons();

			// Render products table
			function renderProducts(productsToRender = products) {
				const tableBody = document.getElementById("products-table-body");
				tableBody.innerHTML = "";

				productsToRender.forEach(product => {
					const row = document.createElement("tr");

					let statusBadge = "";
					if (product.status === "active") {
						statusBadge = '<span class="badge badge-success">Active</span>';
					} else if (product.status === "low-stock") {
						statusBadge = '<span class="badge badge-warning">Low Stock</span>';
					} else {
						statusBadge = '<span class="badge badge-error">Inactive</span>';
					}

					row.innerHTML = `
                    <td>
                        <label>
                            <input type="checkbox" class="checkbox product-checkbox" data-id="${
															product.id
														}">
                        </label>
                    </td>
                    <td>
                        <div class="flex items-center space-x-3">
                            <div class="avatar">
                                <div class="mask mask-squircle w-12 h-12">
                                    <img src="${product.image}" alt="${
						product.name
					}" />
                                </div>
                            </div>
                            <div>
                                <div class="font-bold">${product.name}</div>
                                <div class="text-sm opacity-50">${
																	product.sku
																}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-ghost">${
													product.category
												}</span>
                    </td>
                    <td>$${product.price.toFixed(2)}</td>
                    <td>${product.stock}</td>
                    <td>${statusBadge}</td>
                    <td>
                        <div class="dropdown dropdown-end">
                            <div tabindex="0" role="button" class="btn btn-ghost btn-xs">
                                <i data-lucide="more-horizontal" class="w-4 h-4"></i>
                            </div>
                            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                                <li><a onclick="editProduct(${
																	product.id
																})"><i data-lucide="edit" class="w-4 h-4"></i> Edit</a></li>
                                <li><a onclick="duplicateProduct(${
																	product.id
																})"><i data-lucide="copy" class="w-4 h-4"></i> Duplicate</a></li>
                                <li><a onclick="deleteProduct(${
																	product.id
																})" class="text-error"><i data-lucide="trash-2" class="w-4 h-4"></i> Delete</a></li>
                            </ul>
                        </div>
                    </td>
                `;
					tableBody.appendChild(row);
				});

				// Re-initialize Lucide icons for dynamically added content
				lucide.createIcons();
			}

			// Filter products
			function filterProducts() {
				const searchTerm = document
					.getElementById("search-input")
					.value.toLowerCase();
				const categoryFilter = document.getElementById("category-filter").value;
				const statusFilter = document.getElementById("status-filter").value;

				let filtered = products.filter(product => {
					const matchesSearch =
						product.name.toLowerCase().includes(searchTerm) ||
						product.sku.toLowerCase().includes(searchTerm);
					const matchesCategory =
						!categoryFilter || product.category === categoryFilter;
					const matchesStatus =
						!statusFilter || product.status === statusFilter;

					return matchesSearch && matchesCategory && matchesStatus;
				});

				renderProducts(filtered);
			}

			// Add event listeners for filters
			document
				.getElementById("search-input")
				.addEventListener("input", filterProducts);
			document
				.getElementById("category-filter")
				.addEventListener("change", filterProducts);
			document
				.getElementById("status-filter")
				.addEventListener("change", filterProducts);

			// Modal functions
			function openAddProductModal() {
				document.getElementById("add_product_modal").showModal();
			}

			function closeAddProductModal() {
				document.getElementById("add_product_modal").close();
			}

			// Add product function
			function addProduct() {
				const name = document.getElementById("product-name").value;
				const sku = document.getElementById("product-sku").value;
				const description = document.getElementById(
					"product-description"
				).value;
				const price = parseFloat(
					document.getElementById("product-price").value
				);
				const stock = parseInt(document.getElementById("product-stock").value);
				const category = document.getElementById("product-category").value;
				const image = document.getElementById("product-image").value;
				const active = document.getElementById("product-active").checked;

				if (!name || !sku || !price || !stock || !category) {
					alert("Please fill in all required fields");
					return;
				}

				const newProduct = {
					id: products.length + 1,
					name,
					sku,
					category,
					price,
					stock,
					status: active ? (stock > 10 ? "active" : "low-stock") : "inactive",
					image:
						image ||
						"https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=100&h=100&fit=crop",
				};

				products.push(newProduct);
				renderProducts();
				updateStats();
				closeAddProductModal();

				// Clear form
				document.getElementById("product-name").value = "";
				document.getElementById("product-sku").value = "";
				document.getElementById("product-description").value = "";
				document.getElementById("product-price").value = "";
				document.getElementById("product-stock").value = "";
				document.getElementById("product-category").value = "";
				document.getElementById("product-image").value = "";
				document.getElementById("product-active").checked = true;
			}

			// Product actions
			function editProduct(id) {
				alert(`Edit product with ID: ${id}`);
			}

			function duplicateProduct(id) {
				const product = products.find(p => p.id === id);
				if (product) {
					const newProduct = {
						...product,
						id: products.length + 1,
						name: product.name + " (Copy)",
						sku: product.sku + "-COPY",
					};
					products.push(newProduct);
					renderProducts();
					updateStats();
				}
			}

			function deleteProduct(id) {
				if (confirm("Are you sure you want to delete this product?")) {
					products = products.filter(p => p.id !== id);
					renderProducts();
					updateStats();
				}
			}

			// Update statistics
			function updateStats() {
				const totalProducts = products.length;
				const activeProducts = products.filter(
					p => p.status === "active"
				).length;
				const lowStockProducts = products.filter(
					p => p.status === "low-stock"
				).length;
				const inactiveProducts = products.filter(
					p => p.status === "inactive"
				).length;

				document.getElementById("total-products").textContent = totalProducts;
				document.getElementById("active-products").textContent = activeProducts;
				document.getElementById("low-stock").textContent = lowStockProducts;
				document.getElementById("inactive-products").textContent =
					inactiveProducts;
			}

			// Select all functionality
			document
				.getElementById("select-all")
				.addEventListener("change", function () {
					const checkboxes = document.querySelectorAll(".product-checkbox");
					checkboxes.forEach(checkbox => {
						checkbox.checked = this.checked;
					});
				});

			// Initialize the app
			renderProducts();
			updateStats();
		</script>
	</body>
</html>
