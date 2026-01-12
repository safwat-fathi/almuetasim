@section('title', 'Admin Dashboard')

@section('content')

<x-layouts.admin>
	<div class="drawer lg:drawer-open">
		<input id="drawer-toggle" type="checkbox" class="drawer-toggle" />
		<!-- Main Content -->
		<div class="drawer-content flex flex-col">
			<!-- Page Content -->
			<div class="flex-1 p-6">

				<!-- Content for other dashboard elements can go here -->
				<div class="hero bg-base-200 rounded-lg p-8 min-h-[calc(100vh-110px)]">
					<div class="hero-content text-center flex flex-col items-center justify-center">
						<div class="max-w-md">
							<h1 class="text-3xl font-bold">لوحة تحكم المدير</h1>
							<p class="py-6">مرحباً بك في لوحة التحكم. من هنا يمكنك إدارة جميع جوانب المتجر.</p>
						</div>

						<!-- Stats Cards -->
						<div>
							<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
								<div class="stat bg-gradient-to-r from-primary to-primary-focus rounded-lg shadow-lg relative">
									<div class="stat-figure text-primary-content absolute left-5">
										<i data-lucide="package" class="w-8 h-8"></i>
									</div>
									<div class="stat-title ">
										إجمالي المنتجات
									</div>
									<div class="stat-value ">{{ $stats['totalProducts'] ?? 0 }}</div>
									<div class="stat-desc ">
										@if ($stats['totalProducts'] > 0)
											↗︎ {{ number_format((($stats['totalProducts'] - 100) / 100) * 100, 0) }}% عن
											الشهر
											الماضي
										@else
											No change
										@endif
									</div>
								</div>
								{{--
								<div
									class="stat bg-gradient-to-r from-secondary to-secondary-focus text-secondary-content rounded-lg shadow-lg">
									<div class="stat-figure text-secondary-content">
										<i data-lucide="trending-up" class="w-8 h-8"></i>
									</div>
									<div class="stat-title text-secondary-content/80">
										Active Products
									</div>
									<div class="stat-value text-secondary-content">{{ $stats['activeProducts'] ?? 0 }}</div>
									<div class="stat-desc text-secondary-content/60">
										{{ $stats['totalProducts'] > 0 ? number_format(($stats['activeProducts'] / $stats['totalProducts'])
										* 100, 0) : 0 }}% of total
										</div>
										</div> --}}

								<div class="stat bg-gradient-to-r from-accent to-accent-focus text-accent-content rounded-lg shadow-lg relative">
									<div class="stat-figure text-accent-content absolute left-5">
										<i data-lucide="alert-triangle" class="w-8 h-8"></i>
									</div>
									<div class="stat-title text-accent-content/80">تعداد المنتجات</div>
									<div class="stat-value text-accent-content">{{ $stats['lowStockProducts'] ?? 0 }}
									</div>
									<div class="stat-desc text-accent-content/60">
										التي ستنتهي من المخزون
									</div>
								</div>

								{{-- <div class="stat bg-gradient-to-r from-info to-info-focus text-info-content rounded-lg shadow-lg">
									<div class="stat-figure text-info-content">
										<i data-lucide="eye-off" class="w-8 h-8"></i>
									</div>
									<div class="stat-title text-info-content/80">Inactive</div>
									<div class="stat-value text-info-content">{{ $stats['inactiveProducts'] ?? 0 }}</div>
									<div class="stat-desc text-info-content/60">Hidden products</div>
								</div> --}}

								<div class="stat bg-gradient-to-r from-success to-success-focus text-success-content rounded-lg shadow-lg relative">
									<div class="stat-figure text-success-content absolute left-5">
										<i data-lucide="mail" class="w-8 h-8"></i>
									</div>
									<div class="stat-title text-success-content/80">رسائل جديدة</div>
									<div class="stat-value text-success-content">{{ $stats['newMessages'] ?? 0 }}</div>
									<div class="stat-desc text-success-content/60">
										{{ $stats['totalMessages'] ?? 0 }} الرسائل الكلية
									</div>
								</div>
								<div class="stat bg-gradient-to-r from-info to-info-focus text-info-content rounded-lg shadow-lg relative">
									<div class="stat-figure text-info-content absolute left-5">
										<i data-lucide="users" class="w-8 h-8"></i>
									</div>
									<div class="stat-title text-info-content/80">زيارات المتجر</div>
									<div class="stat-value text-info-content">{{ $stats['totalVisits'] ?? 0 }}</div>
									<div class="stat-desc text-info-content/60">
										{{ $stats['todaysVisits'] ?? 0 }} زيارة اليوم
									</div>
								</div>
							</div>

						</div>
						</div>
						</div>
						</div>
						</div>
						</div>
						</x-layouts.admin>

@section('scripts')
<script>
	// Initialize Lucide icons
		document.addEventListener('DOMContentLoaded', function () {
			lucide.createIcons();
		});
	</script>