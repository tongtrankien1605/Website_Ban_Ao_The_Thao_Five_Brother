@extends('admin.layouts.index')

@section('title')
    Dashboard
@endsection



@section('content')
    <div class="content-wrapper bg-light">
        <section class="content">
            <div class="container-fluid">
                <h2 class="mb-4 text-dark">Dashboard</h2>


                <!-- Bộ lọc thời gian -->
                <form method="GET" class="row mb-4">
                    <div class="col-md-2">
                        <select name="month" class="form-control">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>Tháng
                                    {{ $m }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="year" class="form-control">
                            @for ($y = 2023; $y <= now()->year; $y++)
                                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                    {{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Lọc</button>
                    </div>
                </form>

                <!-- Tổng quan -->
                <div class="row">
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-primary text-white">
                            <div class="inner">
                                <h3>14</h3>
                                <p>Đơn hàng hôm nay</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-warning text-dark">
                            <div class="inner">
                                <h3>12</h3>
                                <p>Đơn chờ xác nhận</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-secondary text-white">
                            <div class="inner">
                                <h3>300</h3>
                                <p>Tổng số đơn hàng</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-success text-white">
                            <div class="inner">
                                <h3>255</h3>
                                <p>Đơn hàng thành công</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-danger text-dark">
                            <div class="inner">
                                <h3>12</h3>
                                <p>Đơn hàng bị hủy</p>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-info text-dark">
                            <div class="inner">
                                <h3>12025355</h3>
                                <p>Tổng doanh thu</p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Tỷ lệ đơn hàng -->
                <div class="row">
                    <!-- Biểu đồ tròn -->
                    <div class="col-md-6">
                        <h4 class="text-dark">Tỷ lệ đơn hàng theo trạng thái</h4>
                        <canvas id="orderStatusChart"></canvas>
                    </div>

                    <!-- Biểu đồ cột ngang (Top 5 sản phẩm doanh thu cao nhất) -->
                    <div class="col-md-6">
                        <h4 class="text-dark">5 sản phẩm có doanh thu cao nhất</h4>
                        <canvas id="topProductsChart"></canvas>
                    </div>

                </div>



                <div class="row mt-4">
                    <!-- Biểu đồ khách hàng mới -->
                    <div class="col-md-6">
                        <h4 class="text-dark">Số lượng khách hàng mới 30 ngày qua</h4>
                        <canvas id="newCustomersChart" height="100"></canvas>
                    </div>

                    <!-- Danh sách 5 khách hàng gần đây -->
                    <div class="col-md-6">
                        <h4 class="text-dark">5 khách hàng mới</h4>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Nguyễn Văn A
                                <span class="badge bg-primary">03/04/2025</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Trần Thị B
                                <span class="badge bg-primary">02/04/2025</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Lê Văn C
                                <span class="badge bg-primary">01/04/2025</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Phạm Thị D
                                <span class="badge bg-primary">31/03/2025</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Hoàng Văn E
                                <span class="badge bg-primary">30/03/2025</span>
                            </li>
                        </ul>
                    </div>
                </div>


                <!-- THỐNG KÊ ĐƠN HÀNG DÙNG TABS -->
                <h5 class="text-center text-dark mb-4">Thống kê đơn hàng</h5>

                <!-- Tabs nav -->
                <ul class="nav nav-tabs" id="orderStatsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="day-tab" data-bs-toggle="tab" data-bs-target="#day"
                            type="button" role="tab" aria-controls="day" aria-selected="true">Theo ngày</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="week-tab" data-bs-toggle="tab" data-bs-target="#week" type="button"
                            role="tab" aria-controls="week" aria-selected="false">Theo tuần</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="month-tab" data-bs-toggle="tab" data-bs-target="#month" type="button"
                            role="tab" aria-controls="month" aria-selected="false">Theo tháng</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="year-tab" data-bs-toggle="tab" data-bs-target="#year" type="button"
                            role="tab" aria-controls="year" aria-selected="false">Theo năm</button>
                    </li>
                </ul>

                <!-- Tabs content -->
                <div class="tab-content pt-3" id="orderStatsTabContent">
                    <div class="tab-pane fade show active" id="day" role="tabpanel" aria-labelledby="day-tab">
                        <canvas id="ordersChartDay" style="height: 280px;"></canvas>
                    </div>
                    <div class="tab-pane fade" id="week" role="tabpanel" aria-labelledby="week-tab">
                        <canvas id="ordersChartWeek" style="height: 280px;"></canvas>
                    </div>
                    <div class="tab-pane fade" id="month" role="tabpanel" aria-labelledby="month-tab">
                        <canvas id="ordersChartMonth" style="height: 280px;"></canvas>
                    </div>
                    <div class="tab-pane fade" id="year" role="tabpanel" aria-labelledby="year-tab">
                        <canvas id="ordersChartYear" style="height: 280px;"></canvas>
                    </div>
                </div>



                <!-- Biểu đồ doanh thu, Select để chọn kiểu hiển thị -->

                <div class="row mt-4">
                    <div class="col-md-12">
                        <h4 class="text-dark">Doanh thu</h4>
                        <select id="revenueFilter" class="form-select w-25 mb-3">
                            <option value="day">Theo Ngày</option>
                            <option value="month" selected>Theo Tháng</option>
                            <option value="year">Theo Năm</option>
                        </select>
                        <canvas id="revenueChart" height="60"></canvas>
                    </div>
                </div>


            </div>
        </section>
    </div>






    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById("newCustomersChart").getContext("2d");

            const today = new Date();
            const labels = [];
            const data = [];

            for (let i = 29; i >= 0; i--) {
                const date = new Date(today);
                date.setDate(today.getDate() - i);
                const label =
                    `${date.getDate().toString().padStart(2, '0')}/${(date.getMonth()+1).toString().padStart(2, '0')}`;
                labels.push(label);
                data.push(Math.floor(Math.random() * 100)); // dữ liệu mẫu
            }

            new Chart(ctx, {
                type: "line",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Khách hàng mới",
                        data: data,
                        borderColor: "rgb(54, 162, 235)",
                        backgroundColor: "rgba(54, 162, 235, 0.2)",
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointBackgroundColor: "rgb(54, 162, 235)"
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });


        // TỈ LỆ ĐƠN HÀNG, PIE CHART biểu đồ tròn 

        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById("orderStatusChart").getContext("2d");

            var orderData = {
                labels: ["Xác nhận", "Bị hủy", "Thành công"],
                datasets: [{
                    data: [40, 20, 40], // Dữ liệu phần trăm
                    backgroundColor: ["#fbc02d", "#d32f2f", "#388e3c"],
                    hoverOffset: 5
                }]
            };

            new Chart(ctx, {
                type: "pie",
                data: orderData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: "bottom"
                        }
                    }
                }
            });
        });

        // Biểu đồ cột ngang - Top 5 sản phẩm có doanh thu cao nhất
        var ctxBar = document.getElementById("topProductsChart").getContext("2d");
        new Chart(ctxBar, {
            type: "bar",
            data: {
                labels: ["Sản phẩm A", "Sản phẩm B", "Sản phẩm C", "Sản phẩm D", "Sản phẩm E"],
                datasets: [{
                    label: "Doanh thu (VNĐ)",
                    data: [50000000, 45000000, 40000000, 35000000,
                        30000000
                    ], // Doanh thu sản phẩm
                    backgroundColor: "#3498db"
                }]
            },
            options: {
                indexAxis: 'y', // Hiển thị cột ngang
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });





        //------------------ END THỐNG KÊ ĐƠN HÀNG THEO NGÀY VÀ TUẦN


        // KHÁCH HÀNG MỚI 30 NGÀY QUA

        const customersByMonthChart = new Chart(document.getElementById('customersByMonthChart'), {
            type: 'bar',
            data: {
                labels: ['01', '02', '03', '04', '05', '06', '07'],
                datasets: [{
                    label: 'Khách hàng mới',
                    data: [20, 30, 25, 40, 35, 50, 45],
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            }
        });




        // DOANH THU THEO NGÀY THÁNG NĂM 
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById("revenueChart").getContext("2d");
            const revenueFilter = document.getElementById("revenueFilter");

            // Dữ liệu mẫu
            const revenueData = {
                day: {
                    labels: ["01", "02", "03", "04", "05", "06", "07"],
                    data: [500, 700, 800, 600, 900, 750, 880]
                },
                month: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
                    data: [15000, 17000, 18000, 16000, 19000, 17500, 18800]
                },
                year: {
                    labels: ["2021", "2022", "2023", "2024"],
                    data: [200000, 250000, 230000, 270000]
                }
            };

            // Khởi tạo biểu đồ
            let revenueChart = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: revenueData["month"].labels,
                    datasets: [{
                        label: "Doanh thu ( VNĐ )",
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
                            beginAtZero: true
                        }
                    }
                }
            });

            // Cập nhật biểu đồ khi chọn ngày / tháng / năm
            revenueFilter.addEventListener("change", function() {
                let selectedType = revenueFilter.value;
                revenueChart.data.labels = revenueData[selectedType].labels;
                revenueChart.data.datasets[0].data = revenueData[selectedType].data;
                revenueChart.update();
            });
        });


        // biểu đồ đơn hàng

        document.addEventListener('DOMContentLoaded', function() {
            const orderChartData = {
                day: {
                    labels: ["T2", "T3", "T4", "T5", "T6", "T7", "CN"],
                    data: [10, 12, 14, 8, 11, 6, 9]
                },
                week: {
                    labels: ["Tuần 1", "Tuần 2", "Tuần 3", "Tuần 4"],
                    data: [42, 50, 47, 39]
                },
                month: {
                    labels: ["Th1", "Th2", "Th3", "Th4", "Th5", "Th6", "Th7", "Th8", "Th9", "Th10", "Th11",
                        "Th12"
                    ],
                    data: [120, 132, 101, 134, 90, 230, 210, 180, 175, 160, 145, 170]
                },
                year: {
                    labels: ["2021", "2022", "2023", "2024"],
                    data: [1450, 1620, 1710, 1530]
                }
            };

            function createChart(canvasId, chartData) {
                const ctx = document.getElementById(canvasId).getContext("2d");
                return new Chart(ctx, {
                    type: "bar",
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                                label: "Số đơn hàng",
                                data: chartData.data,
                                backgroundColor: "rgba(75, 192, 192, 0.6)",
                                borderColor: "rgba(75, 192, 192, 1)",
                                borderWidth: 1
                            },
                            {
                                type: 'line',
                                label: 'Xu hướng',
                                data: chartData.data,
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 2,
                                fill: false,
                                tension: 0.3
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            const charts = {
                day: createChart("ordersChartDay", orderChartData.day),
                week: createChart("ordersChartWeek", orderChartData.week),
                month: createChart("ordersChartMonth", orderChartData.month),
                year: createChart("ordersChartYear", orderChartData.year)
            };
        });
    </script>
@endsection
