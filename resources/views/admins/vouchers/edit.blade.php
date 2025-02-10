
    <h1>Chỉnh sửa Voucher</h1>
    
    <form action="{{ route('admin.vouchers.update', $voucher->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Mã Voucher:</label>
        <input type="text" name="code" value="{{ $voucher->code }}" required><br>

        <label>Loại giảm giá:</label>
        <select name="discount_type">
            <option value="percentage" {{ $voucher->discount_type == 'percentage' ? 'selected' : '' }}>Phần trăm</option>
            <option value="fixed" {{ $voucher->discount_type == 'fixed' ? 'selected' : '' }}>Tiền mặt</option>
        </select><br>

        <label>Giá trị giảm giá:</label>
        <input type="number" step="0.01" name="discount_value" value="{{ $voucher->discount_value }}" required><br>

        <label>Tổng số lần sử dụng:</label>
        <input type="number" name="total_usage" value="{{ $voucher->total_usage }}" required><br>

        <label>Ngày bắt đầu:</label>
        <input type="datetime-local" name="start_date" value="{{ date('Y-m-d\TH:i', strtotime($voucher->start_date)) }}" required><br>

        <label>Ngày kết thúc:</label>
        <input type="datetime-local" name="end_date" value="{{ date('Y-m-d\TH:i', strtotime($voucher->end_date)) }}" required><br>

        <label>Trạng thái:</label>
        <input type="checkbox" name="status" value="1" {{ $voucher->status ? 'checked' : '' }}>
         @if ($voucher->status==0)
            Hết hiệu lực
        @else
Còn hiệu lực          
@endif  
        <br>

        <button type="submit">Cập nhật</button>
    </form>

    <a href="{{ route('admin.vouchers.index') }}">Quay lại danh sách</a>

