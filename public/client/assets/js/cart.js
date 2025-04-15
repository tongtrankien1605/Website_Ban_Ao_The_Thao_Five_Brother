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
            Swal.fire({
                icon: 'warning',
                title: 'Chưa chọn biến thể',
                text: 'Vui lòng chọn ít nhất một biến thể!',
                timer: 1500,
                showConfirmButton: false
            });
            return;
        }
        if (!quantity || quantity < 1) {
            Swal.fire({
                icon: 'warning',
                title: 'Số lượng không hợp lệ',
                text: 'Vui lòng nhập số lượng hợp lệ!',
                timer: 1500,
                showConfirmButton: false
            });
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
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                console.log("✅ Thành công:", response);
            },
            error: function (xhr) {
                let res = xhr.responseJSON;
                // Nếu có message từ server, hiển thị
                if (res && res.message) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Vượt quá tồn kho',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    alert('❌ Đã xảy ra lỗi không xác định!');
                }
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


// $(document).ready(function () {
//     $('.pro-qty').prepend('<span class="dec qtybtn"><i class="ti-minus"></i></span>');
//     $('.pro-qty').append('<span class="inc qtybtn"><i class="ti-plus"></i></span>');

//     $('.qtybtn').on('click', function () {
//         var $button = $(this);
//         var inputField = $button.siblings('input'); // Lấy input số lượng
//         var cartItemId = inputField.data('id'); // Lấy ID sản phẩm
//         var price = $button.closest('tr').find('.pro-price').data('price'); // Lấy giá sản phẩm
//         var checkbox = $button.closest('tr').find('.cart-checkbox'); // Lấy checkbox của sản phẩm

//         var oldValue = parseInt(inputField.val());
//         var newVal = oldValue;

//         if ($button.hasClass('inc')) {
//             newVal = oldValue + 1;
//         } else if ($button.hasClass('dec') && oldValue > 1) {
//             newVal = oldValue - 1;
//         }

//         inputField.val(newVal); // Cập nhật số lượng trên giao diện

//         // Nếu sản phẩm đã được chọn thì mới cập nhật tổng tiền
//         if (checkbox.prop("checked")) {
//             updateCartQuantity(cartItemId, newVal, price);
//         }
//     });

    $(document).ready(function () {
        // Thêm nút tăng giảm
        $('.pro-qty').prepend('<span class="dec qtybtn"><i class="ti-minus"></i></span>');
        $('.pro-qty').append('<span class="inc qtybtn"><i class="ti-plus"></i></span>');
    
        // Khi nhấn vào nút tăng giảm
        $(document).ready(function () {
            $('.qtybtn').on('click', function () {
                var $button = $(this);
                var inputField = $button.siblings('input'); // Lấy input số lượng
                var cartItemId = inputField.data('id'); // Lấy ID sản phẩm
                var price = $button.closest('tr').find('.pro-price').data('price'); // Lấy giá sản phẩm
                var checkbox = $button.closest('tr').find('.cart-checkbox'); // Lấy checkbox của sản phẩm
        
                var oldValue = parseInt(inputField.val());
                var maxQuantity = parseInt(inputField.data('max')); // Lấy số lượng tồn kho
                var newVal = oldValue;
        
                if ($button.hasClass('inc')) {
                    // Nếu giá trị cũ nhỏ hơn hoặc bằng số lượng tối đa, thì tăng thêm
                    if (oldValue < maxQuantity) {
                        newVal = oldValue + 1;
                    } else {
                        // Nếu vượt quá số lượng tồn kho, hiển thị cảnh báo
                        Swal.fire({
                            icon: 'warning',
                            title: 'Vượt quá tồn kho',
                            text: 'Chỉ còn tối đa ' + maxQuantity + ' sản phẩm.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        return; // Dừng lại, không thay đổi giá trị
                    }
                } else if ($button.hasClass('dec') && oldValue > 1) {
                    // Giảm số lượng xuống nếu còn lớn hơn 1
                    newVal = oldValue - 1;
                }
        
                inputField.val(newVal);
        
                // Nếu sản phẩm đã được chọn thì mới cập nhật tổng tiền
                if (checkbox.prop("checked")) {
                    updateCartQuantity(cartItemId, newVal, price);
                }
            });
        
            // Khi người dùng nhập số lượng trực tiếp
            $('.dataInput').on('input', function () {
                let $input = $(this);
                let val = parseInt($input.val());
                let max = parseInt($input.data('max')); // Lấy tồn kho
        
                // Kiểm tra nếu là số hợp lệ và trong phạm vi cho phép
                if (isNaN(val) || val < 1) {
                    $input.val(1);
                    return;
                }
        
                // Nếu nhập lớn hơn số lượng tồn kho, giới hạn lại
                if (val > max) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Vượt quá tồn kho',
                        text: 'Chỉ còn tối đa ' + max + ' sản phẩm trong kho!',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    $input.val(max);
                    val = max;
                }
        
                // Gửi AJAX cập nhật lại giỏ hàng
                let cartItemId = $input.data('id');
                let price = $input.closest('tr').find('.pro-price').data('price');
        
                updateCartQuantity(cartItemId, val, price);
            });
        });
        
    
        // Chặn các phím không hợp lệ như e, +, -, .
        $('.dataInput').on('keydown', function (e) {
            if (
                e.key === 'e' || e.key === 'E' ||
                e.key === '+' || e.key === '-' ||
                e.key === '.' || e.key === ','
            ) {
                e.preventDefault();
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
    let code = null;

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
        let discountValue = parseFloat(selectedOption.data("discount")) || 0;
        let discountType = selectedOption.data("type");
        let maxDiscount = parseFloat(selectedOption.data("max-discount")) || 0;
        let total = parseFloat($(".order-total .amount").text().replace(/\D/g, '')) || 0; // Lấy giá trị số từ HTML
        let discountAmount = (discountType === "percentage") ? (total * discountValue / 100) : discountValue;
        let code = selectedOption.data("code");
        console.log("Mã voucher gửi đi:", code);

        if (maxDiscount > 0) {
            discountAmount = Math.min(discountAmount, maxDiscount);
        }

        newTotal = Math.max(0, total - discountAmount); // 🟢 Cập nhật giá trị toàn cục

        console.log("New Total sau giảm:", newTotal);

        $(".order-total .amount").text(newTotal.toLocaleString() + " Đồng");

        // 🟢 Lưu `newTotal` vào sessionStorage để sử dụng khi thanh toán
        sessionStorage.setItem("newTotal", newTotal);
        sessionStorage.setItem("code", code);

        alert(`Bạn đã áp dụng mã giảm giá! Tổng tiền sau giảm: ${newTotal.toLocaleString()} Đồng`);
    });




    // 🟢 Xử lý khi nhấn nút Checkout
    $(".checkout-btn").on("click", function (e) {
        e.preventDefault();

        let selectedItems = [];
        let hasInvalidQuantity = false;

        $(".cart-checkbox:checked").each(function () {
            let row = $(this).closest("tr");
            let productId = $(this).val();
            let quantity = parseInt(row.find(".dataInput").val());
            let maxQuantity = parseInt(row.find(".dataInput").data("max"));

            if (quantity > maxQuantity) {
                hasInvalidQuantity = true;
                Swal.fire({
                    icon: 'warning',
                    title: 'Vượt quá số lượng tồn kho',
                    text: `Sản phẩm ${row.find('.pro-title a').text()} chỉ còn ${maxQuantity} sản phẩm trong kho`,
                    timer: 2000,
                    showConfirmButton: false
                });
                return false; // Break the each loop
            }

            selectedItems.push({
                id: productId,
                quantity: quantity
            });
        });

        if (hasInvalidQuantity) {
            return;
        }

        if (selectedItems.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Lỗi',
                text: 'Vui lòng chọn ít nhất một sản phẩm để thanh toán!',
                timer: 2000,
                showConfirmButton: false
            });
            Swal.fire({
                icon: 'warning',
                title: 'Lỗi',
                text: 'Vui lòng chọn ít nhất một sản phẩm để thanh toán!',
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }

        let code = $("#voucher option:selected").data("code") || ""; 
        let queryString = $.param({ items: selectedItems, new_total: newTotal, total: total, code: code });

        console.log("🟢 Chuyển hướng với URL:", "/payment?" + queryString);

        window.location.href = "/payment?" + queryString;
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