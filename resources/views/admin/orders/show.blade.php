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
                                    <div class="col-sm-6 invoice-col pb-3">
                                        <address> Người đặt: {{ $order->users->name }}</address>
                                        <address>

                                            Địa chỉ: {{ $order->address_users->address }}
                                        </address>

                                        <address>
                                            Trạng thái đơn hàng: {{ $order->order_statuses->name }}
                                        </address>
                                    </div>
                                    <!-- /.col -->
                                    {{-- <div class="col-sm-6 invoice-col">
                                        Lịch sử đơn hàng:
                                        <strong></strong><br>
                                        795 Folsom Ave, Suite 600<br>
                                        San Francisco, CA 94107<br>
                                        Phone: (555) 539-1037<br>
                                        Email: john.doe@example.com
                                    </div> --}}
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
                                                                    @elseif($orderStatusHistory->old_status == OrderStatus::DELIVERED)
                                                                        <span class="badge bg-success">Giao hàng thành
                                                                            công</span>
                                                                    @elseif($orderStatusHistory->old_status == OrderStatus::FAILED)
                                                                        <span class="badge bg-danger">Giao hàng thất
                                                                            bại</span>
                                                                    @elseif($orderStatusHistory->old_status == OrderStatus::CANCEL)
                                                                        <span class="badge bg-danger">Đã hủy</span>
                                                                    @elseif($orderStatusHistory->old_status == OrderStatus::REFUND)
                                                                        <span class="badge bg-warning">Hoàn trả</span>
                                                                    @elseif($orderStatusHistory->old_status == OrderStatus::SUCCESS)
                                                                        <span class="badge bg-warning">Hoàn thành / Đã nhận
                                                                            được hàng</span>
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
                                                                    @elseif($orderStatusHistory->new_status == OrderStatus::DELIVERED)
                                                                        <span class="badge bg-success">Giao hàng thành
                                                                            công</span>
                                                                    @elseif($orderStatusHistory->new_status == OrderStatus::FAILED)
                                                                        <span class="badge bg-danger">Giao hàng thất
                                                                            bại</span>
                                                                    @elseif($orderStatusHistory->new_status == OrderStatus::CANCEL)
                                                                        <span class="badge bg-danger">Đã hủy</span>
                                                                    @elseif($orderStatusHistory->new_status == OrderStatus::REFUND)
                                                                        <span class="badge bg-warning">Hoàn hàng</span>
                                                                    @elseif($orderStatusHistory->new_status == OrderStatus::SUCCESS)
                                                                        <span class="badge bg-warning">Hoàn thành / Đã nhận
                                                                            được hàng</span>
                                                                    @endif
                                                                @endif
                                                            </td>
                                                            <td>{{ !empty($orderStatusHistory->note) ? $orderStatusHistory->note : '' }}
                                                            </td>
                                                            <td>{{ !empty($orderStatusHistory->user_id) ? $orderStatusHistory->users->name : 'Đơn hàng được cập nhật tự động' }}
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
                                        <ul>
                                            <li>
                                                <p class="lead">
                                                    <strong>
                                                        Payment Methods: {{ $order->payment_methods->name }}
                                                    </strong>
                                                </p>
                                            </li>
                                            <li>
                                                <p class="lead">
                                                    <strong>
                                                        Payment Method Status: {{ $order->payment_method_statuses->name }}
                                                    </strong>
                                                </p>
                                            </li>
                                            <li>
                                                <p class="lead">
                                                    <strong>
                                                        Shipping Methods: {{ $order->shipping_methods->name }}
                                                    </strong>
                                                </p>
                                            </li>
                                            <li>
                                                <p class="lead">
                                                    @if ($order->id_shipping_method == 1)
                                                        <strong>
                                                            Thời gian dự kiến nhận hàng:
                                                            {{ $order->created_at->addDay(1)->format('d/m/Y') }}
                                                        </strong>
                                                    @elseif($order->id_shipping_method == 2)
                                                        <strong>
                                                            Thời gian dự kiến nhận hàng:
                                                            {{ $order->created_at->addDay(3)->format('d/m/Y') }}
                                                        </strong>
                                                    @elseif($order->id_shipping_method == 3)
                                                        <strong>
                                                            Thời gian dự kiến nhận hàng:
                                                            {{ $order->created_at->addDay(5)->format('d/m/Y') }}

                                                        </strong>
                                                    @endif
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-6">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tr>
                                                    <th style="width:50%">Subtotal:</th>
                                                    <td>{{ number_format($order->total_amount - $order->shipping_methods->cost, 0, '', ',') }}
                                                        VND</td>
                                                </tr>
                                                <tr>
                                                    <th>Shipping:</th>
                                                    <td>{{ number_format($order->shipping_methods->cost, 0, '', ',') }} VND
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Total:</th>
                                                    <td>{{ number_format($order->total_amount, 0, '', ',') }}
                                                        VND</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <a href="{{ route('admin.orders.index') }}" class="btn btn-danger my-5">Quay
                                            lại</a>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#myModal" @if (in_array($order->id_order_status, [OrderStatus::CANCEL, OrderStatus::DELIVERED, OrderStatus::REFUND])) disabled @endif>
                                            Cập nhật đơn hàng
                                        </button>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->
                            </div>
                            <!-- /.invoice -->
                        </div><!-- /.col -->

                    </div><!-- /.row -->

                </div><!-- /.container-fluid   data-bs-backdrop="static"-->

                <div class="modal fade" id="myModal">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">Cập nhật đơn hàng #{{ $order->id }}</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <!-- Modal body -->
                            <div class="modal-body">
                                <form id="editOrderForm" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" id="order_id" name="order_id" value="{{ $order->id }}">

                                    <div class="mb-3">
                                        <h6 class="fs-15">#1.Thông tin đơn hàng:</h6>
                                        <div class="row">
                                            <div class="col-4">
                                                <label for="user_name" class="form-label">Người đặt:</label>
                                                <p class="form-control">{{ $order->users->name }}</p>
                                            </div>
                                            <div class="col-4">
                                                <label for="user_name" class="form-label">Số điện thoại:</label>
                                                <p class="form-control">{{ $order->users->phone_number }}</p>
                                            </div>
                                            <div class="col-4">
                                                <label for="user_name" class="form-label">Địa chỉ giao hàng:</label>
                                                <p class="form-control">{{ $order->address_users->address }}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <label for="user_name" class="form-label">Phương thức thanh toán:</label>
                                                <p class="form-control">{{ $order->payment_methods->name }}</p>
                                            </div>
                                            <div class="col-4">
                                                <label for="user_name" class="form-label">Phương thức vận chuyển:</label>
                                                <p class="form-control">{{ $order->shipping_methods->name }}</p>
                                            </div>
                                            <div class="col-4">
                                                <label for="user_name" class="form-label">Trạng thái hiện tại:</label>
                                                <p class="form-control">{{ $order->order_statuses->name }}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <label for="payment_status" class="form-label">Trạng thái thanh
                                                    toán:</label>
                                                <p class="form-control">
                                                    {{ $order->payment_method_statuses->name }}</p>
                                            </div>

                                            <div class="col-4">
                                                <label for="created_at" class="form-label">Thời gian đặt:</label>
                                                <p class="form-control">{{ $order->created_at }}</p>
                                            </div>
                                            <div class="col-4">
                                                <label for="created_at" class="form-label">Thời gian dự kiến nhận
                                                    hàng:</label>
                                                <p class="form-control">
                                                    @if ($order->id_shipping_method == 1)
                                                        {{ $order->created_at->addDay(1)->format('d/m/Y') }}
                                                    @elseif($order->id_shipping_method == 2)
                                                        {{ $order->created_at->addDay(3)->format('d/m/Y') }}
                                                    @elseif($order->id_shipping_method == 3)
                                                        {{ $order->created_at->addDay(5)->format('d/m/Y') }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3">
                                            <h6 class="fs-15">#2.Lịch sử đơn hàng:</h6>
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
                                                                            <span class="badge bg-danger">Chờ xác
                                                                                nhận</span>
                                                                        @elseif($orderStatusHistory->old_status == OrderStatus::CONFIRM)
                                                                            <span class="badge bg-success">Đã xác
                                                                                nhận</span>
                                                                        @elseif($orderStatusHistory->old_status == OrderStatus::DELIVERING)
                                                                            <span class="badge bg-primary">Đang giao</span>
                                                                        @elseif($orderStatusHistory->old_status == OrderStatus::WAITING_FOR_DELIVERING)
                                                                            <span class="badge bg-primary">Chờ lấy
                                                                                hàng</span>
                                                                        @elseif($orderStatusHistory->old_status == OrderStatus::DELIVERED)
                                                                            <span class="badge bg-success">Giao hàng thành
                                                                                công</span>
                                                                        @elseif($orderStatusHistory->old_status == OrderStatus::FAILED)
                                                                            <span class="badge bg-danger">Giao hàng thất
                                                                                bại</span>
                                                                        @elseif($orderStatusHistory->old_status == OrderStatus::CANCEL)
                                                                            <span class="badge bg-danger">Đã hủy</span>
                                                                        @elseif($orderStatusHistory->old_status == OrderStatus::REFUND)
                                                                            <span class="badge bg-warning">Hoàn trả</span>
                                                                        @elseif($orderStatusHistory->old_status == OrderStatus::SUCCESS)
                                                                            <span class="badge bg-warning">Hoàn thành / Đã
                                                                                nhận được hàng</span>
                                                                        @endif

                                                                        {{-- Hiển thị mũi tên nếu old_status khác new_status --}}
                                                                        <i class="ri-arrow-right-line"></i>

                                                                        {{-- Hiển thị new_status --}}
                                                                        @if ($orderStatusHistory->new_status == OrderStatus::PENDING)
                                                                            <span class="badge bg-danger">Chờ xác
                                                                                nhận</span>
                                                                        @elseif($orderStatusHistory->new_status == OrderStatus::CONFIRM)
                                                                            <span class="badge bg-success">Đã xác
                                                                                nhận</span>
                                                                        @elseif($orderStatusHistory->new_status == OrderStatus::DELIVERING)
                                                                            <span class="badge bg-primary">Đang giao</span>
                                                                        @elseif($orderStatusHistory->new_status == OrderStatus::WAITING_FOR_DELIVERING)
                                                                            <span class="badge bg-primary">Chờ lấy
                                                                                hàng</span>
                                                                        @elseif($orderStatusHistory->new_status == OrderStatus::DELIVERED)
                                                                            <span class="badge bg-success">Giao hàng thành
                                                                                công</span>
                                                                        @elseif($orderStatusHistory->new_status == OrderStatus::FAILED)
                                                                            <span class="badge bg-danger">Giao hàng thất
                                                                                bại</span>
                                                                        @elseif($orderStatusHistory->new_status == OrderStatus::CANCEL)
                                                                            <span class="badge bg-danger">Đã hủy</span>
                                                                        @elseif($orderStatusHistory->new_status == OrderStatus::REFUND)
                                                                            <span class="badge bg-warning">Hoàn hàng</span>
                                                                        @elseif($orderStatusHistory->new_status == OrderStatus::SUCCESS)
                                                                            <span class="badge bg-warning">Hoàn thành / Đã
                                                                                nhận được hàng</span>
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
                                            <h6 class="fs-15">#3.Chi tiết đơn hàng:</h6>
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


                                    <div class="mb-3">
                                        <h6 class="fs-15">#4. Thay Đổi Trạng Thái Đơn Hàng (Lưu ý: Đơn hàng đã
                                            "Hoàn thành / Đã nhận được hàng, Đã hủy" thì không thể thay đổi trạng
                                            thái!)</h6>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-lg-12">
                                            <div class="form-floating">
                                                <select id="id_order_status" name="id_order_status"
                                                    class="form-control text-sm"
                                                    @if (in_array($order->id_order_status, [OrderStatus::CANCEL, OrderStatus::DELIVERED, OrderStatus::REFUND])) disabled @endif>
                                                    @php
                                                        // Danh sách trạng thái không thể thay đổi
                                                        $lockedStatuses = [
                                                            OrderStatus::CANCEL,
                                                            OrderStatus::DELIVERED,
                                                            OrderStatus::REFUND,
                                                        ];

                                                        // Trạng thái hiện tại của đơn hàng
                                                        $currentStatus = $order->id_order_status;

                                                        // Xác định các trạng thái có thể chọn dựa trên thứ tự
                                                        $validNextStatuses = match ($currentStatus) {
                                                            OrderStatus::PENDING => [
                                                                OrderStatus::CONFIRM,
                                                                OrderStatus::CANCEL,
                                                            ],
                                                            OrderStatus::CONFIRM => [
                                                                OrderStatus::DELIVERING,
                                                                OrderStatus::CANCEL,
                                                            ],
                                                            OrderStatus::DELIVERING => [
                                                                OrderStatus::WAITING_FOR_DELIVERING,
                                                            ],
                                                            OrderStatus::WAITING_FOR_DELIVERING => [
                                                                OrderStatus::DELIVERED,
                                                                OrderStatus::FAILED,
                                                            ],
                                                            OrderStatus::FAILED => [OrderStatus::REFUND],
                                                            default => [], // Trạng thái cuối không được thay đổi
                                                        };
                                                        // Đảm bảo trạng thái hiện tại luôn có trong danh sách hợp lệ để không bị mất
                                                        if (!in_array($currentStatus, $validNextStatuses)) {
                                                            array_unshift($validNextStatuses, $currentStatus);
                                                        }
                                                    @endphp

                                                    @foreach ($orderStatuses as $orderStatus)
                                                        <option value="{{ $orderStatus->id }}"
                                                            {{ $orderStatus->id == $currentStatus ? 'selected' : '' }}
                                                            {{ in_array($currentStatus, $lockedStatuses) || !in_array($orderStatus->id, $validNextStatuses) ? 'disabled' : '' }}>
                                                            {{ $orderStatus->name }}
                                                        </option>
                                                    @endforeach

                                                </select>

                                                <label for="floatingSelect">Trạng thái đơn hàng</label>
                                            </div>

                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-floating">
                                                <textarea name="note" id="note" class="form-control" rows="5" id="summernote"></textarea>
                                                <label for="firstnamefloatingInput">Ghi chú(nếu có):</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=" text-center">
                                        <button type="button" class="btn btn-danger"
                                            data-bs-dismiss="modal">Đóng</button>
                                        <button type="submit" class="btn btn-success" disabled>Lưu thay đổi</button>
                                    </div>
                                </form>
                                <div id="response-message"></div>
                            </div>

                            <!-- Modal footer -->
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
    </div>
    <script>
        let userId = {{ auth()->id() }};
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".form-floating select").forEach((select) => {
                const saveButton = select.closest(".modal").querySelector("button[type='submit']");
                let originalStatus = select.value;

                select.addEventListener("change", function() {
                    saveButton.disabled = select.value === originalStatus;
                });
            });
        });
    </script>
    @vite('resources/js/updateOrderDetail.js')
@endsection
