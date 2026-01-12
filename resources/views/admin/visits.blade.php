<x-layouts.admin>
    <div class="drawer lg:drawer-open">
        <input id="drawer-toggle" type="checkbox" class="drawer-toggle" />
        <!-- Main Content -->
        <div class="drawer-content flex flex-col">
            <!-- Page Content -->
            <div class="flex-1 p-6">
                <div class="hero bg-base-200 rounded-lg p-8 min-h-[calc(100vh-110px)]">
                    <div class="hero-content flex-col w-full">
                        <h1 class="text-3xl font-bold mb-6">إحصائيات الزيارات (آخر 30 يوم)</h1>
                        
                        <div class="w-full bg-base-100 p-6 rounded-lg shadow-lg">
                            @if($data->sum() > 0)
                                <canvas id="visitsChart"></canvas>
                            @else
                                <div class="flex flex-col items-center justify-center py-10 text-base-content/50">
                                    <i data-lucide="bar-chart-2" class="w-16 h-16 mb-4 opacity-20"></i>
                                    <h3 class="text-lg font-semibold">لا توجد زيارات حتى الآن</h3>
                                    <p class="text-sm">لم يتم تسجيل أي زيارات للمتجر خلال الـ 30 يوم الماضية.</p>
                                </div>
                            @endif
                        </div>

                        <a href="{{ route('dashboard') }}" class="btn btn-primary mt-6">عودة للرئيسية</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot:scripts>
			<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
			<script>
				
            document.addEventListener('DOMContentLoaded', function() {
        


                @if($data->sum() > 0)
                const ctx = document.getElementById('visitsChart').getContext('2d');
                const visitsChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($labels) !!},
                        datasets: [{
                            label: 'عدد الزيارات',
                            data: {!! json_encode($data) !!},
                            borderColor: 'rgb(75, 192, 192)',
                            tension: 0.1,
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
                @endif
            });
        </script>
    </x-slot:scripts>
</x-layouts.admin>
