<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Đơn hàng #{{ $order->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .company-info {
            margin-bottom: 20px;
        }

        .order-info {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
        }

        .total-section {
            text-align: right;
            margin-top: 20px;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
        }
    </style>
</head>

<body>
    @php
        use App\Enums\OrderStatus;
    @endphp
    <div class="header">
        <h1>5Brother</h1>
        <p>Đơn hàng #{{ $order->id }}</p>
        <p>Ngày tạo: {{ $order->created_at->format('d/m/Y H:i:s') }}</p>
    </div>
    @php
        $address = collect($order->users->address_users)->where('is_default', 1)->first();
    @endphp
    <div class="order-info">
        <h3>Thông tin đơn hàng</h3>
        <table style="width: 100%; margin-bottom: 20px; border:none;">
            <tr>
                <td style="width: 50%; vertical-align: top; border:none;">
                    <p><strong>Người đặt:</strong> {{ $order->users->name }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $order->users->phone_number }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $address->address }}</p>
                </td>
                <td style="width: 50%; vertical-align: top;border:none;">
                    <p><strong>Người nhận:</strong> {{ $order->receiver_name }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $order->phone_number }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
                </td>
            </tr>
        </table>

        <p><strong>Phương thức thanh toán:</strong> {{ $order->payment_methods->name }}</p>
        <p><strong>Trạng thái thanh toán:</strong> {{ $order->payment_method_statuses->name }}</p>
        <p><strong>Phương thức vận chuyển:</strong> {{ $order->shipping_methods->name }}</p>
        <p><strong>Trạng thái:</strong> {{ $order->order_statuses->name }}</p>
    </div>

    <h3>Chi tiết đơn hàng</h3>
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Sản phẩm</th>
                <th>Mã vạch</th>
                <th>Đơn giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->order_details as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $detail->product_variants->name }}</td>
                    <td>{{ $detail->product_variants->barcode }}</td>
                    <td>{{ number_format($detail->unit_price, 0, '', ',') }} VND</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ number_format($detail->total_price, 0, '', ',') }} VND</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <p><strong>Tổng tiền hàng:</strong>
            {{ number_format($order->total_amount + ($order->vouchers->max_discount_amount ?? 0) - $order->shipping_methods->cost, 0, '', ',') }}
            VND</p>
        <p><strong>Giảm giá:</strong> {{ number_format($order->vouchers->max_discount_amount ?? 0, 0, '', ',') }} VND
        </p>
        <p><strong>Phí vận chuyển:</strong> {{ number_format($order->shipping_methods->cost, 0, '', ',') }} VND</p>
        <p><strong>Tổng cộng:</strong> {{ number_format($order->total_amount, 0, '', ',') }} VND</p>
    </div>

    <div class="footer">
        <p>Cảm ơn quý khách đã sử dụng dịch vụ của chúng tôi!</p>
        <p>5Brother - Đồng hành cùng thể thao</p>
    </div>
</body>

</html>
