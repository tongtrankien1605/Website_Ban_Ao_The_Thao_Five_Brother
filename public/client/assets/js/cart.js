console.log("cart.js đã được load!");

$(document).ready(function () {
    console.log("Script đã load!");

    $(document).on("click", ".add_to_cart", function (e) {
        e.preventDefault();
        console.log("Nút Add to Cart đã được nhấn!");

        let url = $(this).data("url");
        console.log("URL từ data-url:", url);

        if (!url) {
            console.error("Lỗi: Không tìm thấy URL.");
            return;
        }

        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content")
            },
            success: function (response) {
                alert(response.message);
                console.log("Thành công:", response);
            },
            error: function (xhr, status, error) {
                console.error("Lỗi Ajax:", error, xhr.responseText);
                alert("Lỗi: " + (xhr.responseJSON?.message || "Không xác định"));
            }
        });
    });
});



$(document).ready(function () {
    console.log("Script xóa giỏ hàng đã load!");

    $(document).on("click", ".remove-btn", function (event) {
        event.preventDefault();
        let button = $(this);
        let url = button.data('url');

        console.log("Xóa sản phẩm, gửi request đến:", url);

        $.ajax({
            type: "DELETE",
            url: url,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: 'json',
            success: function (response) {
                alert(response.message);
                console.log("Sản phẩm đã được xóa:", response);

                // Xóa sản phẩm khỏi giao diện
                let cartRow = button.closest('tr'); // Lấy hàng chứa sản phẩm
                cartRow.remove();

                // Cập nhật tổng giỏ hàng
                updateTotalCart();

                // Nếu không còn sản phẩm nào, hiển thị thông báo
                if ($(".cart-table tbody tr").length === 0) {
                    $(".cart-table tbody").html("<tr><td colspan='6' class='text-center'><strong>Giỏ hàng trống</strong></td></tr>");
                }
            },
            error: function (xhr, status, error) {
                console.error("Lỗi xóa sản phẩm:", xhr.responseText);
                alert('Lỗi khi xóa sản phẩm');
            }
        });
    });

    function updateTotalCart() {
        let total = 0;
        $('.pro-subtotal').each(function () {
            let priceText = $(this).text().replace(/\D/g, '');
            let price = parseInt(priceText) || 0;
            total += price;
        });

        $(".order-total .amount").text(total.toLocaleString() + " Đồng");

        // Nếu tổng giỏ hàng = 0, ẩn phần tổng tiền
        if (total === 0) {
            $(".cart-total").hide();
        }
    }
});


$('.pro-qty').prepend('<span class="dec qtybtn"><i class="ti-minus"></i></span>');
$('.pro-qty').append('<span class="inc qtybtn"><i class="ti-plus"></i></span>');

$('.qtybtn').on('click', function () {
    var $button = $(this);
    var inputField = $button.siblings('input'); // Lấy input số lượng
    var cartItemId = inputField.data('id'); // Lấy ID sản phẩm
    var price = $button.closest('tr').find('.pro-price').data('price'); // Lấy giá sản phẩm
    var priceInput = inputField.data('dataInput'); 
    // Lấy giá sản phẩm
    var oldValue = parseInt(inputField.val());

    var newVal = oldValue;
    if ($button.hasClass('inc')) {
        newVal = oldValue + 1;
    } else if ($button.hasClass('dec') && oldValue > 1) {
        newVal = oldValue - 1;
    }

    inputField.val(newVal); // Cập nhật số lượng trên giao diện
    updateCartQuantity(cartItemId, newVal, price,priceInput); // Gọi AJAX để cập nhật

});

// Hàm cập nhật giỏ hàng
function updateCartQuantity(cartItemId, newQuantity, price) {
    $.ajax({
        url: "/cart/update/" + cartItemId,
        type: "POST",
        data: {
            quantity: newQuantity,
            _token: $('meta[name="csrf-token"]').attr("content")
        },
        success: function (response) {
            // Cập nhật subtotal của từng sản phẩm
            let newSubtotal = newQuantity * price;
            $('#subtotal-' + cartItemId).text(newSubtotal.toLocaleString() + " đồng");

            // Cập nhật tổng giỏ hàng
            updateTotalCart();

            console.log("Cập nhật thành công:", response);
        },
        error: function (xhr) {
            console.error("Lỗi AJAX:", xhr);
            alert("Lỗi: " + xhr.responseJSON.message);
        }
    });
}



// Hàm cập nhật tổng giỏ hàng mà không load lại trang
function updateTotalCart() {
    let total = 0;
    $('.pro-subtotal').each(function () {
        let price = parseInt($(this).text().replace(/\D/g, '')) || 0;
        total += price;
    });

    $('.cart-total strong .amount').text(total.toLocaleString() + " đồng");
}

