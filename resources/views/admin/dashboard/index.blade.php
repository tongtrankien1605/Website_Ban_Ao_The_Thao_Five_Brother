@extends('admin.layouts.index')

@section('title')
    Dashboard
@endsection

@section('content')
    <div class="content-wrapper bg-light">
        <section class="content">
            <div class="container-fluid">
                <h2 class="mb-4 text-dark">Dashboard</h2>


                <!-- Tổng quan - done -->
                <div class="row">
                    <div class="col-lg-3 col-6 mb-3">
                        <div class="small-box bg-primary text-white">
                            <div class="inner">
                                <h3> {{ $ordersToday }} </h3>
                                <p>Đơn hàng hôm nay</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6 mb-3">
                        <div class="small-box bg-warning text-dark">
                            <div class="inner">
                                <h3> {{ $ordersPending }} </h3>
                                <p>Chờ xác nhận</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6 mb-3">
                        <div class="small-box bg-info text-white">
                            <div class="inner">
                                <h3> {{ $ordersConfirmed }} </h3>
                                <p>Đã xác nhận</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6 mb-3">
                        <div class="small-box bg-secondary text-dark">
                            <div class="inner">
                                <h3> {{ $ordersWaitingPickup }} </h3>
                                <p>Chờ lấy hàng</p>
                            </div>
                        </div>
                    </div>


                </div>


                <div class="row">


                    <div class="col-lg-3 col-6 mb-3">
                        <div class="small-box bg-success text-white">
                            <div class="inner">
                                <h3> {{ $ordersSuccess }} </h3>
                                <p>Giao hàng thành công</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6 mb-3">
                        <div class="small-box bg-danger text-white">
                            <div class="inner">
                                <h3> {{ $ordersCanceled }} </h3>
                                <p>Đơn hàng bị hủy</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6 mb-3">
                        <div class="small-box bg-dark text-white">
                            <div class="inner">
                                <h3> {{ $totalOrders }} </h3>
                                <p>Tổng số đơn hàng</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6 mb-3">
                        <div class="small-box bg-purple text-white">
                            <div class="inner">
                                <h3> {{ number_format($totalRevenue) }} </h3>
                                <p>Tổng doanh thu</p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <!-- lệ đơn hàng Biểu đồ tròn - done -->
                    <div class="col-md-4">
                        <h4 class="text-dark">Tỷ lệ đơn hàng theo trạng thái</h4>
                        <canvas id="orderStatusChart" width="400" height="400"></canvas>
                    </div>

                    <!-- Biểu đồ cột ngang (Top sản phẩm doanh thu cao nhất) - done -->
                    <div class="col-md-8">
                        <h4 class="text-dark">Top sản phẩm có doanh thu cao nhất</h4>
                        <canvas id="topProductsChart"></canvas>
                    </div>

                </div>



                <div class="row mt-4">

                    <!-- Biểu đồ khách hàng mới - done -->
                    <div class="col-md-6">
                        <h4 class="text-dark">Số lượng khách hàng mới 30 ngày qua</h4>
                        <canvas id="newCustomersChart" height="100"></canvas>
                    </div>

                    <!-- Danh sách 5 khách hàng gần đây - done -->
                    <div class="col-md-6">
                        <h4 class="text-dark">5 khách hàng mới</h4>
                        <ul class="list-group">
                            @foreach ($latestCustomers as $customer)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $customer->name }}
                                    <span class="badge bg-primary">
                                        {{ \Carbon\Carbon::parse($customer->created_at)->format('d/m/Y H:i:s') }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>


                <!-- THỐNG KÊ ĐƠN HÀNG DÙNG TABS -->

                <div class="container-fluid mt-4 px-4">
                    <h4 class="text-center text-dark mb-4">Thống Kê Đơn Hàng</h4>

                    <!-- Bộ lọc theo khoảng ngày -->
                    <div class="mb-3 text-start">
                        <label class="form-label fw-bold">Chọn khoảng ngày:</label>
                        <input type="date" id="startDate" class="form-control d-inline-block w-auto" />
                        <span class="mx-2">đến</span>
                        <input type="date" id="endDate" class="form-control d-inline-block w-auto" />
                        <button id="filterBtn" class="btn btn-primary ms-2">Lọc</button>
                    </div>

                    <!-- Select chế độ hiển thị mặc định -->
                    <div class="mb-3 text-start">
                        <label for="chartTypeSelect" class="form-label fw-bold">Chọn chế độ:</label>
                        <select id="chartTypeSelect" class="form-select w-auto d-inline-block">
                            <option value="day" selected>Theo ngày</option>
                            <option value="week">Theo tuần</option>
                        </select>
                    </div>

                    <!-- Biểu đồ -->
                    <div class="bg-white rounded shadow-sm p-3" style="position: relative; height: 400px; width: 100%;">
                        <canvas id="ordersChart"></canvas>
                    </div>
                </div>



                <!-- DOANH THU tính theo ngày / tháng / năm - done -->

                <div class="row mt-4">
                    <div class="col-md-12">
                        <h4 class="text-dark">Doanh thu</h4>
                        <select id="revenueFilter" class="form-select w-25 mb-3">
                            <option value="day">Theo Ngày ( 7 ngày gần nhất )</option>
                            <option value="month" selected>Theo Tháng ( 7 tháng gần nhất )</option>
                            <option value="year">Theo Năm ( 4 năm gần nhất )</option>
                        </select>
                        <canvas id="revenueChart" height="100"></canvas>
                    </div>
                </div>









            </div>

        </section>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script>
        // START Tỷ lệ đơn hàng theo trạng thái 

        const ctxStatus = document.getElementById('orderStatusChart').getContext('2d');
        const orderStatusData = @json($orderStatusChart);

        const dataValues = Object.values(orderStatusData);
        const total = dataValues.reduce((sum, val) => sum + val, 0);
        const hasData = total > 0;

        const chartData = {
            labels: hasData ? Object.keys(orderStatusData) : ['Không có dữ liệu'],
            datasets: [{
                label: hasData ? 'Số lượng' : 'Không có dữ liệu',
                data: hasData ? dataValues : [1],
                backgroundColor: hasData ? [
                    'rgba(255, 205, 86, 0.7)', // Chờ xác nhận
                    'rgba(54, 162, 235, 0.7)', // Đã xác nhận
                    'rgba(153, 102, 255, 0.7)', // Chờ lấy hàng
                    'rgba(75, 192, 192, 0.7)', // Giao thành công
                    'rgba(255, 99, 132, 0.7)' // Bị hủy
                ] : ['rgba(220, 220, 220, 0.5)'],
                borderWidth: 1
            }]
        };

        const chartOptions = {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            if (!hasData) return 'Không có dữ liệu';
                            const value = context.raw;
                            const percent = ((value / total) * 100).toFixed(1);
                            return `${context.label}: ${value} (${percent}%)`;
                        }
                    }
                }
            }
        };

        const orderStatusChart = new Chart(ctxStatus, {
            type: 'doughnut',
            data: chartData,
            options: chartOptions,
            plugins: [{
                id: 'centerText',
                beforeDraw: function(chart) {
                    if (!hasData) {
                        const width = chart.width,
                            height = chart.height,
                            ctx = chart.ctx;
                        ctx.restore();
                        ctx.font = '16px Arial';
                        ctx.textBaseline = 'middle';
                        ctx.fillStyle = '#999';
                        const text = 'Không có dữ liệu',
                            textX = Math.round((width - ctx.measureText(text).width) / 2),
                            textY = height / 2;
                        ctx.fillText(text, textX, textY);
                        ctx.save();
                    }
                }
            }]
        });


        // END Tỷ lệ đơn hàng theo trạng thái -------------------------------------------------------------


        // START 5 sản phẩm có doanh thu cao nhất

        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('topProductsChart').getContext('2d');

            const data = {
                labels: @json($topProducts->pluck('variant_name')),
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: @json($topProducts->pluck('revenue')),
                    backgroundColor: [
                        '#4e73df',
                        '#1cc88a',
                        '#36b9cc',
                        '#f6c23e',
                        '#e74a3b'
                    ],
                    borderWidth: 1
                }]
            };

            const config = {
                type: 'bar',
                data: data,
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let value = context.raw;
                                    return new Intl.NumberFormat('vi-VN', {
                                        style: 'currency',
                                        currency: 'VND'
                                    }).format(value);
                                }
                            }
                        },
                        legend: {
                            display: false
                        },
                        title: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('vi-VN').format(value);
                                }
                            }
                        }
                    }
                }
            };

            new Chart(ctx, config);
        });

        // END 5 sản phẩm có doanh thu cao nhất -------------------------------------------------------------


        // START số lượng khách hàng mới 30 ngày qua

        const ctxNewCustomers = document.getElementById('newCustomersChart').getContext('2d');
        const newCustomersChart = new Chart(ctxNewCustomers, {
            type: 'line',
            data: {
                labels: {!! json_encode($newCustomers->pluck('date')) !!},
                datasets: [{
                    label: 'Khách hàng mới',
                    data: {!! json_encode($newCustomers->pluck('count')) !!},
                    borderColor: 'green',
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

        // END số lượng khách hàng mới 30 ngày qua -------------------------------------------------------------



        // START thống kê đơn hàng


        document.addEventListener('DOMContentLoaded', function() {
            const ordersByDay = @json($ordersByDayFormatted);
            const ordersByWeek = @json($ordersByWeekFormatted);

            const ctx = document.getElementById('ordersChart').getContext('2d');
            let currentChart;

            function createChart(type, customLabels = null, customData = null, customLabel = null) {
                if (currentChart) {
                    currentChart.destroy();
                }

                let labels = [],
                    data = [],
                    label = '';

                if (type === 'day') {
                    labels = ordersByDay.map(item => item.date);
                    data = ordersByDay.map(item => item.total);
                    label = 'Số đơn hàng theo ngày';
                } else if (type === 'week') {
                    labels = ordersByWeek.map(item => item.week);
                    data = ordersByWeek.map(item => item.total);
                    label = 'Số đơn hàng theo tuần';
                }

                // Nếu có dữ liệu lọc thì dùng cái đó
                if (customLabels && customData) {
                    labels = customLabels;
                    data = customData;
                    label = customLabel;
                }

                currentChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: label,
                            data: data,
                            borderColor: 'rgb(75, 192, 192)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderWidth: 2,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                precision: 0
                            }
                        }
                    }
                });
            }

            // Mặc định hiển thị biểu đồ theo ngày
            createChart('day');

            // Thay đổi khi chọn từ select
            document.getElementById('chartTypeSelect').addEventListener('change', function() {
                createChart(this.value);
            });

            // Lọc theo khoảng ngày
            document.getElementById('filterBtn').addEventListener('click', function() {
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;

                if (!startDate || !endDate) {
                    alert('Vui lòng chọn đầy đủ ngày bắt đầu và kết thúc.');
                    return;
                }

                // fetch(`/orders/filter?start=${startDate}&end=${endDate}`)
                fetch(`/admin/orders/filter?start=${startDate}&end=${endDate}`)

                    .then(response => response.json())
                    .then(data => {
                        const labels = data.map(item => item.date);
                        const chartData = data.map(item => item.total);
                        const label = `Số đơn hàng từ ${startDate} đến ${endDate}`;
                        createChart('custom', labels, chartData, label);
                    });
            });
        });

        // END thống kê đơn hàng --------------------------------------------------------------------------------


        // START doanh thu theo ngày / tháng / năm

        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById("revenueChart").getContext("2d");
            const revenueFilter = document.getElementById("revenueFilter");

            const revenueData = @json($revenueData);

            let revenueChart = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: revenueData["month"].labels, // Bắt đầu với doanh thu theo tháng
                    datasets: [{
                        label: "Doanh thu (VNĐ)",
                        data: revenueData["month"].data,
                        backgroundColor: "rgba(54, 162, 235, 0.5)",
                        borderColor: "rgb(54, 162, 235)",
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1, // Đảm bảo rằng các giá trị trên trục Y là các số nguyên
                            }
                        }
                    }
                }
            });

            revenueFilter.addEventListener("change", function() {
                const selected = revenueFilter.value;
                revenueChart.data.labels = revenueData[selected].labels;
                revenueChart.data.datasets[0].data = revenueData[selected].data;
                revenueChart.update();
            });
        });


        // END doanh thu theo ngày / tháng / năm ------------------------------------------------------------------
    </script>
@endsection
