// console.log("cart.js đã được load!");
$(document).ready(function () {
    $('.add_to_cart').on('click', function () {
        let url = $(this).data('url');
        let quantity = $('#quantity').val(); // Lấy số lượng

        let selectedVariants = [];
        $('input[type="radio"]:checked').each(function () {
            selectedVariants.push($(this).val()); // ✅ Lưu tất cả ID vào mảng
        });

        console.log("🟢 Dữ liệu gửi lên:", {
            _token: $('meta[name="csrf-token"]').attr('content'),
            quantity: quantity,
            variant_ids: selectedVariants
        });

        // Kiểm tra lỗi
        if (selectedVariants.length === 0) {
            alert('❌ Vui lòng chọn ít nhất một biến thể!');
            return;
        }
        if (!quantity || quantity < 1) {
            alert('❌ Vui lòng nhập số lượng hợp lệ!');
            return;
        }

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                quantity: quantity,
                variant_ids: selectedVariants // ✅ Chuyển về mảng thay vì object
            },
            success: function (response) {
                console.log("✅ Thành công:", response);
                alert(response.message);
            },
            error: function (xhr) {
                alert('Bạn cần đăng nhập để thêm vào giỏ hàng!');
                console.log("❌ Lỗi:", xhr.responseText);
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


$(document).ready(function () {
    $('.pro-qty').prepend('<span class="dec qtybtn"><i class="ti-minus"></i></span>');
    $('.pro-qty').append('<span class="inc qtybtn"><i class="ti-plus"></i></span>');

    $('.qtybtn').on('click', function () {
        var $button = $(this);
        var inputField = $button.siblings('input'); // Lấy input số lượng
        var cartItemId = inputField.data('id'); // Lấy ID sản phẩm
        var price = $button.closest('tr').find('.pro-price').data('price'); // Lấy giá sản phẩm
        var checkbox = $button.closest('tr').find('.cart-checkbox'); // Lấy checkbox của sản phẩm

        var oldValue = parseInt(inputField.val());
        var newVal = oldValue;

        if ($button.hasClass('inc')) {
            newVal = oldValue + 1;
        } else if ($button.hasClass('dec') && oldValue > 1) {
            newVal = oldValue - 1;
        }

        inputField.val(newVal); // Cập nhật số lượng trên giao diện

        // Nếu sản phẩm đã được chọn thì mới cập nhật tổng tiền
        if (checkbox.prop("checked")) {
            updateCartQuantity(cartItemId, newVal, price);
        }
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
                if (xhr.status === 422) {
                    // Nếu lỗi do vượt quá số lượng tồn kho
                    alert(xhr.responseJSON.message);
                    // Reset số lượng về giá trị tối đa cho phép
                    let maxQuantity = xhr.responseJSON.max_quantity;
                    $('input[data-id="' + cartItemId + '"]').val(maxQuantity);
                    // Cập nhật lại với số lượng tối đa
                    updateCartQuantity(cartItemId, maxQuantity, price);
                } else {
                    alert("Lỗi: " + xhr.responseJSON.message);
                }
            }
        });
    }

    // Hàm cập nhật tổng giỏ hàng chỉ tính các sản phẩm được chọn
    function updateTotalCart() {
        let total = 0;
        $(".cart-checkbox:checked").each(function () {
            let row = $(this).closest("tr");
            let price = parseFloat($(this).data("price")); // Lấy giá từ checkbox
            let quantity = parseInt(row.find(".dataInput").val()); // Lấy số lượng
            total += price * quantity;
        });

        $(".cart-subtotal .amount").text(total.toLocaleString() + " Đồng");
        $(".order-total .amount").text(total.toLocaleString() + " Đồng");
    }

    // Cập nhật tổng tiền khi chọn/bỏ chọn sản phẩm
    $(".cart-checkbox").on("change", function () {
        updateTotalCart();
    });

    // Khi load trang, cập nhật tổng giá trị nếu có sản phẩm đã chọn
    updateTotalCart();
});

$(document).ready(function () {
    $(".cart-checkbox").on("change", function () {
        updateTotalPrice(); // Gọi hàm cập nhật tổng tiền ngay khi chọn checkbox
    });

    function updateTotalPrice() {
        let total = 0;

        $(".cart-checkbox:checked").each(function () {
            let row = $(this).closest("tr");
            let price = parseFloat($(this).data("price")); // Giá sản phẩm
            let quantity = parseInt(row.find(".dataInput").val()); // Số lượng

            total += price * quantity;
        });
        if (total !== 0) {
            $(".cart-coupon").show();
        } else (
            $(".cart-coupon").hide(),
            $("#voucher").val("")
        )

        // Cập nhật tổng giá vào giao diện
        $(".order-total .amount").text(total.toLocaleString() + " đồng");
    }
});


$(document).ready(function () {
    let total = 0; // Biến lưu tổng tiền
    let newTotal = 0; // Biến lưu tổng tiền mới sau khi áp voucher
    let discountValue = 0;
    let discountType = null;
    
    // 🟢 Hàm cập nhật tổng tiền giỏ hàng
    function updateCartSummary() {
        let subtotal = 0;

        $(".cart-checkbox:checked").each(function () {
            let row = $(this).closest("tr");
            let price = parseFloat($(this).data("price")); // Lấy giá từ checkbox
            let quantity = parseInt(row.find(".dataInput").val()); // Lấy số lượng

            subtotal += price * quantity;
        });

        total = subtotal; // Lưu giá trị mới
        console.log("1:", total, "2:", subtotal);

        $(".cart-subtotal .amount").text(subtotal.toLocaleString() + " Đồng");
        $(".order-total .amount").text(total.toLocaleString() + " Đồng"); // Cập nhật tổng
    }

    // 🟢 Cập nhật khi chọn checkbox hoặc thay đổi số lượng
    $(".cart-checkbox, .dataInput").on("change input", function () {
        updateCartSummary();
    });

    // 🟢 Xử lý khi áp voucher
    $("#apply-voucher").click(function (e) {
        e.preventDefault();

        let selectedOption = $("#voucher option:selected");
        discountValue = parseFloat(selectedOption.data("discount")) || 0;
        discountType = selectedOption.data("type");

        console.log("Total ban đầu:", total);
        console.log("Discount Value:", discountValue);
        console.log("Discount Type:", discountType);

        if (discountType === "percentage") {
            newTotal = total - (total * discountValue / 100);
        } else {
            newTotal = total - discountValue;
        }

        newTotal = Math.max(0, newTotal); // Không âm

        console.log("New Total sau giảm:", newTotal);
        $(".order-total .amount").text(newTotal.toLocaleString() + " Đồng");
    });

    // 🟢 Xử lý khi nhấn nút Checkout
    $(".checkout-btn").on("click", function (e) {
        e.preventDefault();

        let selectedItems = [];

        $(".cart-checkbox:checked").each(function () {
            let row = $(this).closest("tr");
            let productId = $(this).val();
            let quantity = row.find(".dataInput").val();

            selectedItems.push({
                id: productId,
                quantity: quantity
            });
        });

        if (selectedItems.length === 0) {
            alert("❌ Vui lòng chọn ít nhất một sản phẩm để thanh toán!");
            return;
        }

        console.log("🟢 Đang chuyển hướng với:", selectedItems, "New Total:", newTotal);

        let queryString = $.param({ items: selectedItems, new_total: newTotal, total: total, discount: discountValue, discountType: discountType });

        window.location.href = "/payment?" + queryString; // Chuyển hướng với new_total
    });

    updateCartSummary(); // Cập nhật khi load trang
});

$(document).ready(function () {
    $(".cart-coupon").hide();

    $("#apply-voucher").prop("disabled", true);

    $("#voucher").change(function () {
        if ($(this).val() !== "") {
            $("#apply-voucher").prop("disabled", false);
        } else {
            $("#apply-voucher").prop("disabled", true);
        }
    });

    $("#apply-voucher").click(function () {
        $(this).prop("disabled", true);
    });
});
