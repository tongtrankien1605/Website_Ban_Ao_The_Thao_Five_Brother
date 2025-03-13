@extends('admin.layouts.index')

@section('title')
    Chi tiết đơn hàng
@endsection

@section('content')
    @php
        use App\Enums\OrderStatus;
    @endphp
    <div class="wrapper">

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Chi tiết đơn hàng</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                                <li class="breadcrumb-item active">Chi tiết đơn hàng</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">


                            <!-- Main content -->
                            <div class="invoice p-3 mb-3">
                                <!-- title row -->
                                <div class="row">
                                    <div class="col-12">
                                        <h4>
                                            <i class="fas fa-globe"></i> 5Brother
                                            <small class="float-right">Create at:
                                                {{ $order->created_at->format('d/m/Y') }}</small>
                                        </h4>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- info row -->
                                <div class="row invoice-info">
                                    <div class="col-sm-6 invoice-col">
                                        Người đặt:
                                        <address>
                                            <strong>{{ $order->users->name }}</strong><br>
                                            Địa chỉ:<br>
                                            {{ $order->address_users->address }}
                                        </address>

                                        <address>
                                            <strong>Trạng thái đơn hàng: {{ $order->order_statuses->name }}</strong>
                                        </address>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-6 invoice-col">
                                        Lịch sử đơn hàng:
                                        <strong></strong><br>
                                        795 Folsom Ave, Suite 600<br>
                                        San Francisco, CA 94107<br>
                                        Phone: (555) 539-1037<br>
                                        Email: john.doe@example.com
                                    </div>
                                    <!-- /.col -->
                                    {{-- <div class="col-sm-4 invoice-col">
                                        <b>Invoice #007612</b><br>
                                        <br>
                                        <b>Order ID:</b> 4F3S8J<br>
                                        <b>Payment Due:</b> 2/22/2014<br>
                                        <b>Account:</b> 968-34567
                                    </div> --}}
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->
                                <div class="row">
                                    <div class="mb-3">
                                        <h5 class="fs-15">Lịch sử đơn hàng:</h5>
                                    </div>
                                    <div class="col-12 table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>STT</th>
                                                    <th>Trạng thái thay đổi</th>
                                                    <th>Ghi chú</th>
                                                    <th>Người thay đổi</th>
                                                    <th>Thời gian</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($order->order_status_histories))
                                                    @foreach ($order->order_status_histories as $index => $orderStatusHistory)
                                                        <tr>
                                                            <td class="fw-medium">{{ $index + 1 }}</td>
                                                            <td>
                                                                {{-- Kiểm tra nếu old_status và new_status đều là 'pending' --}}
                                                                @if ($orderStatusHistory->old_status == OrderStatus::PENDING && $orderStatusHistory->new_status == OrderStatus::PENDING)
                                                                    {{-- Chỉ hiển thị new_status --}}
                                                                    <span class="badge bg-danger">Chờ xác nhận</span>
                                                                @else
                                                                    {{-- Hiển thị old_status --}}
                                                                    @if ($orderStatusHistory->old_status == OrderStatus::PENDING)
                                                                        <span class="badge bg-danger">Chờ xác nhận</span>
                                                                    @elseif($orderStatusHistory->old_status == OrderStatus::CONFIRM)
                                                                        <span class="badge bg-success">Đã xác nhận</span>
                                                                    @elseif($orderStatusHistory->old_status == OrderStatus::DELIVERING)
                                                                        <span class="badge bg-primary">Đang giao</span>
                                                                    @elseif($orderStatusHistory->old_status == OrderStatus::WAITING_FOR_DELIVERING)
                                                                        <span class="badge bg-primary">Chờ lấy hàng</span>
                                                                    @elseif($orderStatusHistory->old_status == OrderStatus::SUCCESS)
                                                                        <span class="badge bg-success">Giao hàng thành
                                                                            công</span>
                                                                    @elseif($orderStatusHistory->old_status == OrderStatus::FAILED)
                                                                        <span class="badge bg-danger">Giao hàng thất
                                                                            bại</span>
                                                                    @elseif($orderStatusHistory->old_status == OrderStatus::CANCEL)
                                                                        <span class="badge bg-danger">Đã hủy</span>
                                                                    @elseif($orderStatusHistory->old_status == OrderStatus::REFUND)
                                                                        <span class="badge bg-warning">Hoàn trả</span>
                                                                    @endif

                                                                    {{-- Hiển thị mũi tên nếu old_status khác new_status --}}
                                                                    <i class="ri-arrow-right-line"></i>

                                                                    {{-- Hiển thị new_status --}}
                                                                    @if ($orderStatusHistory->new_status == OrderStatus::PENDING)
                                                                        <span class="badge bg-danger">Chờ xác nhận</span>
                                                                    @elseif($orderStatusHistory->new_status == OrderStatus::CONFIRM)
                                                                        <span class="badge bg-success">Đã xác nhận</span>
                                                                    @elseif($orderStatusHistory->new_status == OrderStatus::DELIVERING)
                                                                        <span class="badge bg-primary">Đang giao</span>
                                                                    @elseif($orderStatusHistory->new_status == OrderStatus::WAITING_FOR_DELIVERING)
                                                                        <span class="badge bg-primary">Chờ lấy hàng</span>
                                                                    @elseif($orderStatusHistory->new_status == OrderStatus::SUCCESS)
                                                                        <span class="badge bg-success">Giao hàng thành
                                                                            công</span>
                                                                    @elseif($orderStatusHistory->new_status == OrderStatus::FAILED)
                                                                        <span class="badge bg-danger">Giao hàng thất
                                                                            bại</span>
                                                                    @elseif($orderStatusHistory->new_status == OrderStatus::CANCEL)
                                                                        <span class="badge bg-danger">Đã hủy</span>
                                                                    @elseif($orderStatusHistory->new_status == OrderStatus::REFUND)
                                                                        <span class="badge bg-warning">Hoàn hàng</span>
                                                                    @endif
                                                                @endif
                                                            </td>
                                                            <td>{{ !empty($orderStatusHistory->note) ? $orderStatusHistory->note : '' }}
                                                            </td>
                                                            <td>{{ !empty($orderStatusHistory->user_id) ? $orderStatusHistory->users->name : '' }}
                                                            </td>
                                                            <td>{{ !empty($orderStatusHistory->updated_at) ? $orderStatusHistory->updated_at->format('H:i:s d/m/Y') : '' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.col -->
                                </div>

                                <!-- Table row -->
                                <div class="row">
                                    <div class="mb-3">
                                        <h5 class="fs-15">Chi tiết đơn hàng:</h5>
                                    </div>
                                    <div class="col-12 table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>STT</th>
                                                    <th>Product</th>
                                                    <th>Barcode</th>
                                                    <th>Price</th>
                                                    <th>Quantity</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($order->order_details))
                                                    @foreach ($order->order_details as $index => $orderDetail)
                                                        <tr>
                                                            <td class="fw-medium">{{ $index + 1 }}</td>
                                                            <td>
                                                                {{ !empty($orderDetail->product_variants->name) ? $orderDetail->product_variants->name : '' }}
                                                            </td>
                                                            <td>
                                                                {{ !empty($orderDetail->product_variants->barcode) ? $orderDetail->product_variants->barcode : '' }}
                                                            </td>
                                                            <td>{{ !empty($orderDetail->unit_price) ? number_format($orderDetail->unit_price, 0, '', ',') : '' }}
                                                                VND</td>
                                                            <td>{{ !empty($orderDetail->quantity) ? $orderDetail->quantity : '' }}
                                                            </td>
                                                            <td>
                                                                {{ !empty($orderDetail->total_price) ? number_format($orderDetail->total_price, 0, '', ',') : '' }}
                                                                VND
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->

                                <div class="row">
                                    <!-- accepted payments column -->
                                    <div class="col-6">
                                        <p class="lead">Payment Methods: {{ $order->payment_methods->name }}</p>
                                        <p class="lead">Shipping Methods: {{ $order->shipping_methods->name }}</p>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-6">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tr>
                                                    <th style="width:50%">Subtotal:</th>
                                                    <td>{{ number_format($order->total_amount, 0, '', ',') }} VND</td>
                                                </tr>
                                                {{-- <tr>
                                                    <th>Shipping:</th>
                                                    <td>$5.80</td>
                                                </tr> --}}
                                                <tr>
                                                    <th>Total:</th>
                                                    <td>{{ number_format($order->total_amount, 0, '', ',') }} VND</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <a href="{{ route('admin.orders.index') }}" class="btn btn-danger my-5">Quay
                                            lại</a>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->
                            </div>
                            <!-- /.invoice -->
                        </div><!-- /.col -->

                    </div><!-- /.row -->

                </div><!-- /.container-fluid -->

            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
    </div>
@endsection
