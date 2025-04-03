    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <h2 class="mb-4">Dashboard</h2>

                <!-- Thống kê đơn hàng -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>150</h3>
                                <p>Đơn hàng hôm nay</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>1,200</h3>
                                <p>Khách hàng mới</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Biểu đồ doanh thu -->
                <div class="row">
                    <div class="col-md-6">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Bảng đơn hàng -->
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Mã ĐH</th>
                                    <th>Khách hàng</th>
                                    <th>Ngày đặt</th>
                                    <th>Trạng thái</th>
                                    <th>Tổng tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#1001</td>
                                    <td>Nguyễn Văn A</td>
                                    <td>29/03/2025</td>
                                    <td><span class="badge bg-success">Đã giao</span></td>
                                    <td>500,000đ</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </section>
    </div>

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Tháng 1', 'Tháng 2', 'Tháng 3'],
                datasets: [{
                    label: 'Doanh thu',
                    data: [12000000, 15000000, 18000000],
                    backgroundColor: 'rgba(54, 162, 235, 0.5)'
                }]
            }
        });
    </script>
@endsection
