
    <h1>Thêm Voucher</h1>
    <form action="{{ route('admin.vouchers.store') }}" method="POST">
        @csrf
        Mã: <input type="text" name="code" required><br>
        Loại: <select name="discount_type">
            <option value="percentage">Phần trăm</option>
            <option value="fixed">Tiền mặt</option>
        </select><br>
        Giá trị: <input type="number" name="discount_value" required><br>
        Tổng số lần sử dụng: <input type="number" name="total_usage" required><br>
        Ngày bắt đầu: <input type="datetime-local" name="start_date" required><br>
        Ngày kết thúc: <input type="datetime-local" name="end_date" required><br>
        Trạng thái: <input type="checkbox" name="status" value="0"><br>
        <button type="submit">Thêm</button>
    </form>

