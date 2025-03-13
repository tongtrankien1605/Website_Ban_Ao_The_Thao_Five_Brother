import "./bootstrap";

if (window.userId) {
    window.Echo.private("orders." + window.userId)
        .listen("OrderStatusUpdate", (event) => {
            console.log(" Sự kiện nhận được:", event);
            alert(`Đơn hàng của bạn đã được cập nhật, hãy kiểm tra nhé`);
        })
        .error((error) => {
            console.error("Lỗi kết nối tới kênh:", error);
        });
}
