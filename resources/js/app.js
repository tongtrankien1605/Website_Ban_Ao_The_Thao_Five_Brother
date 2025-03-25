import "./bootstrap";

if (window.userId) {
    window.Echo.private("orders." + window.userId)
        .listen("OrderStatusUpdate", (event) => {
            console.log("Sự kiện nhận được:", event);

            Swal.fire({
                title: "Cập nhật đơn hàng!",
                text: "Đơn hàng của bạn đã được cập nhật. Đến xem chi tiết ngay?",
                icon: "success",
                showCancelButton: true,
                confirmButtonText: "Xem đơn hàng",
                cancelButtonText: "Đóng",
            }).then((result) => {
                if (result.isConfirmed) {
                    // window.location.href = "get-order-details/" + event.order.id;
                    window.location.href = "http://datn.local/my-account";
                }
            });
        })
        .error((error) => {
            console.error("Lỗi kết nối tới kênh:", error);
        });
}
