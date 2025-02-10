
    <h1>Danh sách Vouchers</h1>
    <a href="{{ route('admin.vouchers.create')}}">Thêm mới Voucher</a>
    <table border="1">
        <tr>
            <th>Mã Voucher</th>
            <th>Loại</th>
            <th>Giá trị</th>
            <th>Số lần sử dụng</th>
            <th>Ngày bắt đầu</th>
            <th>Ngày kết thúc</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
        @foreach ($vouchers as $voucher)
            <tr>
                <td>{{ $voucher->code }}</td>
                <td>{{ $voucher->discount_type }}</td>
                <td>{{ $voucher->discount_value }}</td>
                <td>{{ $voucher->total_usage }}</td>
                <td>{{ $voucher->start_date }}</td>
                <td>{{ $voucher->end_date }}</td>
                <td>{{ $voucher->status ? 'Còn' : 'Hết' }}</td>
                <td>
                    <a href="{{ route('admin.vouchers.edit', $voucher->id) }}">Sửa</a>
                    <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Xóa</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

