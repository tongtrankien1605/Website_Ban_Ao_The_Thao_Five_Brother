import "./bootstrap";

if (window.userId) {
    window.Echo.private("orders." + window.userId)
        .listen("OrderStatusUpdate", (event) => {
            console.log("Sự kiện nhận được:", event);

            // Cập nhật trạng thái đơn hàng trên UI
            updateOrderStatus(event.order);

            Swal.fire({
                title: "Cập nhật đơn hàng!",
                text: "Đơn hàng của bạn đã được cập nhật thành \"" + event.order.order_status_name + "\"",
                icon: "success",
                confirmButtonText: "Đóng",
            });
        })
        .error((error) => {
            console.error("Lỗi kết nối tới kênh:", error);
        });
}

// Hàm cập nhật UI
function updateOrderStatus(order) {
    // Cập nhật trạng thái trong bảng và chi tiết đơn hàng
    const statusElements = document.querySelectorAll(`.order-status-text[data-order-id="${order.id}"]`);
    if (statusElements.length > 0) {
        statusElements.forEach(element => {
            element.textContent = order.order_status_name;
        });
        
        // Highlight phần tử đã thay đổi
        const row = statusElements[0].closest('tr');
        if (row) {
            row.style.transition = "background-color 1s";
            row.style.backgroundColor = "#fffde7";
            setTimeout(() => {
                row.style.backgroundColor = "";
            }, 3000);
        }
        
        // Cập nhật UI khác dựa trên trạng thái mới
        updateOrderActions(order.id, order.id_order_status);
    }
}

// Hàm xử lý hiển thị/ẩn các nút tùy theo trạng thái
function updateOrderActions(orderId, newStatusId) {
    // Tìm phần tử chi tiết đơn hàng đang mở
    const orderDetailRow = document.querySelector(`tr.order-details-content[style*="display: table-row"]`);
    if (!orderDetailRow) return;
    
    // Kiểm tra nếu đơn hàng hiện tại đang được mở
    const orderDetails = orderDetailRow.querySelector(`.order-details h3`);
    if (!orderDetails || !orderDetails.textContent.includes(`#${orderId}`)) return;
    
    // Cập nhật UI dựa trên trạng thái mới
    const confirmSection = orderDetailRow.querySelector('#confirm-section');
    const notReceivedForm = orderDetailRow.querySelector('#not-received-form');
    
    if (newStatusId == 5) { // Đã giao hàng
        if (confirmSection) confirmSection.style.display = 'block';
        if (notReceivedForm) notReceivedForm.style.display = 'none';
    } else if (newStatusId == 9) { // Hoàn thành
        if (confirmSection) confirmSection.style.display = 'none';
        
        // Thêm thông báo hoàn thành
        const actionSection = orderDetailRow.querySelector('.card.p-4.mt-4');
        if (actionSection) {
            actionSection.innerHTML = `
                <div class="alert alert-success text-center">
                    Cảm ơn quý khách đã mua hàng của shop chúng tôi!
                </div>
            `;
        }
    }
    // Thêm xử lý cho các trạng thái khác nếu cần
}
