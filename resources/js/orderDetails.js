
// Click nút Chi tiết trong Order để Hiển thị Order Detail

    $(document).ready(function() {
        $('.order-details-btn').click(function() {
            $(this).closest('tr').next('.order-details-content').toggle();
        });
    });


    //  Chuyển đổi Nút Click từ V sang X và ngược lại

    document.querySelectorAll('.dropbox-arrow-icon.order-details-btn').forEach(button => {
        button.addEventListener('click', function() {
            const state = this.getAttribute('data-state');
            const arrowIcon = this.querySelector('.arrow-icon');
            const closeIcon = this.querySelector('.close-icon');
    
            if (state === 'closed') {
                // Hiển thị chi tiết đơn hàng
                // Giả sử bạn có một hàm để hiển thị chi tiết đơn hàng, ví dụ: showOrderDetails();
                // showOrderDetails();
    
                // Đổi biểu tượng thành chữ "X"
                arrowIcon.style.display = 'none';
                closeIcon.style.display = 'block';
                this.setAttribute('data-state', 'open');
            } else {
                // Ẩn chi tiết đơn hàng
                // Giả sử bạn có một hàm để ẩn chi tiết đơn hàng, ví dụ: hideOrderDetails();
                // hideOrderDetails();
    
                // Đổi lại thành mũi tên
                arrowIcon.style.display = 'block';
                closeIcon.style.display = 'none';
                this.setAttribute('data-state', 'closed');
            }
        });
    });