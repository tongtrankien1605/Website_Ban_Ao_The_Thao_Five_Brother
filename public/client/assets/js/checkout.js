$(document).ready(function() {

    $('.choose-shipping').click(function() {
        const selected = $('input[name="shipping_method"]:checked');

        const name = selected.data('name');
        const cost = selected.data('cost');
        const time = selected.data('time');

        $('#shipping-name').text(name);
        $('#shipping-cost').text(`₫${cost}`);
        $('#shipping-time').text(time);

        $('#shippingModal').modal('hide');
    });
    // Open Add New Address Form
    $('#addNewAddress').click(function() {
        $('#addressFormTitle').text('Thêm Địa Chỉ Mới');
        $('#addressForm')[0].reset();
        $('#address_id').val('');
        $('#addressModal').modal('hide');
        $('#addressFormModal').modal('show');
    });

    // Edit Address


    // Submit Address Form
    $('#addressFormAdd').submit(function(e) {
        e.preventDefault();
        const url = '/address-user/add';
        // const method = addressId ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#addressFormModal').modal('hide');
                $('#addressModal').modal('show');
                // Refresh the page or update the address list
                loadAddressList(); // cập nhật lại danh sách địa chỉ
                
            },
            error: function(xhr) {
                alert('Có lỗi xảy ra. Vui lòng thử lại.');
            }
        });
    });


        // Bấm nút "Sửa" -> đổ dữ liệu vào form
        $(document).ready(function () {
            // Gắn sự kiện click một lần duy nhất
            $(document).on('click', '.edit-address', function () {
                const addressId = $(this).data('id');
        
                $.ajax({
                    url: '/address-user/edit/' + addressId,
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (res) {
                        $('#addressFormModalEdit #address_id').val(res.id);
                        $('#addressFormModalEdit #fullname').val(res.name);
                        $('#addressFormModalEdit #phone').val(res.phone);
                        $('#addressFormModalEdit #address').val(res.address);
        
                        const $checkbox = $('#addressFormModalEdit #is_default');
                        if (res.is_default == 1) {
                            $checkbox.prop('checked', true).prop('disabled', true);
                        } else {
                            $checkbox.prop('checked', false).prop('disabled', false);
                        }
        
                        $('#addressFormModalEdit').modal('show');
                    },
                    error: function () {
                        alert('Không thể tải địa chỉ. Vui lòng thử lại!');
                    }
                });
            });
    
        // Submit form sửa địa chỉ
        $('#addressFormEdit').submit(function (e) {
            e.preventDefault();
    
            const addressId = $('#address_id').val();
            const formData = $(this).serialize();
    
            $.ajax({
                url: `/address-user/update/${addressId}`,
                method: 'PUT',
                data: formData,
                success: function () {
                    $('#addressFormModalEdit').modal('hide');
                    $('#addressModal').modal('show');

                    // toastr.success('Cập nhật địa chỉ thành công');
                    loadAddressList(); // cập nhật lại danh sách địa chỉ
                },
                error: function () {
                    toastr.error('Có lỗi xảy ra khi cập nhật địa chỉ');
                }
            });
        });
    
        // Tải lại danh sách địa chỉ bằng AJAX

    });
    

    // Select Address
    $('.select-address').click(function() {
        const addressId = $(this).data('id');
        $.post(`/address-user/default/${addressId}`, {
            _token: $('meta[name="csrf-token"]').attr('content')
        }, function(response) {
            $('#selected-name-phone').text(`${response.fullname} (+84) ${response.phone}`);
            $('#selected-address').text(response.address);
            $('#addressModal').modal('hide');
        });
    });

    // Delete Address
    $('.delete-address').click(function() {
        if(confirm('Bạn có chắc muốn xóa địa chỉ này?')) {
            const addressId = $(this).data('id');
            $.ajax({
                url: `/address-user/delete/${addressId}`,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    loadAddressList(); // cập nhật lại danh sách địa chỉ
                }
            });
        }
    });

    function loadAddressList() {
        $.get(`/address-user/list`, function (html) {
            $('.address-list').html(html);
        });
    }
});


$(document).ready(function () {
    // Chọn voucher
    $(document).on('click', '.select-voucher', function () {
        const voucherId = $(this).data('id');
        const voucherItem = $(this).closest('.voucher-item');
        console.log(voucherItem);
        const voucherCode = $(this).data('code'); // từ data-code trên nút
    
        // Gán vào input ẩn
        $('#voucher_id').val(voucherId);
    
        // Gọi function apply với code này
        applyVoucher(voucherCode);
    });
    

    // Chọn phương thức vận chuyển
    $('.choose-shipping').on('click', function () {
        const selected = $('input[name="shipping_method"]:checked');
        const id = selected.val();
        const name = selected.data('name');
        const cost = selected.data('cost').replace(/\./g, ''); // loại bỏ dấu chấm ngăn cách
        const time = selected.data('time');
    
        $('#shipping_method_id').val(id);
        $('#shipping-name').text(name);
        $('#shipping-time').text(time);
    
        const formattedCost = '₫' + parseInt(cost).toLocaleString('vi-VN');
    
        // Cập nhật tất cả nơi hiển thị phí ship
        $('.shipping-cost-display').text(formattedCost);
        $('.shipping-cost-summary').text(formattedCost);
    
        $('#shippingModal').modal('hide');
    
        updateOrderSummary();
    });
     

    function updateOrderSummary() {
        const subtotal = parseInt($('#subtotal').text().replace(/[₫,.]/g, '')) || 0;
        const shippingCost = parseInt($('#shipping-cost').text().replace(/[₫,.]/g, '')) || 40000;
        console.log(shippingCost);
        const voucherDiscount = parseInt($('#voucher-discount').text().replace(/[₫,.]/g, '')) || 0;
    
        const finalTotal = subtotal + shippingCost - voucherDiscount;
    
        $('#final-total').text('₫' + finalTotal.toLocaleString());
    }
    
    $(document).ready(function () {
        // Khi người dùng chọn phương thức thanh toán
        $('input[name="payment_method_id"]').on('change', function () {
            const selectedId = $(this).val();
            console.log('Selected payment method ID:', selectedId);
            $('#payment_method_id').val(selectedId); // Gán vào input hidden
        });
    
        // Nếu có radio được check sẵn (ví dụ reload lại), gán luôn
        const checkedPayment = $('input[name="payment_method_id"]:checked').val();
        if (checkedPayment) {
            $('#payment_method_id').val(checkedPayment);
        }
    });
    

    $('#btn-place-order').on('click', function (e) {
        e.preventDefault();
        const url = $(this).data('url');
    
        const address = $('#selected-address').text().trim();
    
        const namePhone = $('#selected-name-phone').text().trim();
        const [nameRaw, phoneRaw] = namePhone.split('Số điện thoại:');
        const name = nameRaw.trim();
        const phone = phoneRaw.trim();
        const message = $('#message').val().trim();
    
        const paymentMethodId = $('#payment_method_id').val();
        const shippingMethodId = $('#shipping_method_id').val();
        const voucherId = $('#voucher_id').val() || null;
        const total = parseInt($('#final-total').text().replace(/[₫,.]/g, '')) || 0;
    
        // Lấy dữ liệu từ URL
        const urlParams = new URLSearchParams(window.location.search);
    
        // ✅ Kết hợp DOM + URL
        const items = [];
        $('.product-item').each(function (index) {
            const name = $(this).find('.d-flex.align-items-center div:first-child').text().trim();
            const priceText = $(this).find('div[style*="width: 110px; text-align: center;"]:eq(0)').text().trim();
            const quantityText = $(this).find('div[style*="width: 110px; text-align: center;"]:eq(1)').text().trim();
    
            const price = parseInt(priceText.replace(/[₫,.]/g, '')) || 0;
            const quantity = parseInt(quantityText) || 0;
    
            // Lấy id từ URL theo chỉ số (index)
            const id = parseInt(urlParams.get(`items[${index}][id]`)) || null;
    
            items.push({ id, name, price, quantity });
        });
    
        const data = {
            name,
            phone,
            address,
            shipping_method_id: shippingMethodId,
            payment_method_id: paymentMethodId,
            voucher_id: voucherId,
            message: message,
            total,
            items
        };
    
        $.ajax({
            url: url,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            data,
            success: function (res) {
                if (res.redirect_url) {
                    // Nếu server trả về link thanh toán (VNPay), chuyển hướng người dùng
                    window.location.href = res.redirect_url;
                } else if (res.success === true) {
                    alert(res.message || 'Đặt hàng thành công!');
                    window.location.href = '/my-account';
                } else {
                    alert(res.message || 'Có lỗi xảy ra. Vui lòng thử lại.');
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert('Lỗi server. Vui lòng thử lại.');
            }
        });
        
    });
    

     });

     function applyVoucher(voucherCode) {
        const url = "/voucher/apply"; // URL của API áp dụng voucher
        // console.log(url);
        
        const voucherId = $('#voucher_id').val();
        const subtotal = parseInt($('#subtotal').text().replace(/[₫,.]/g, ''));
        const shippingMethodId = $('#shipping_method_id').val(); // Lấy ID phương thức vận chuyển đã chọn
        // console.log(shippingMethodId);
        

        $.ajax({
            url: url,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            data: {
                voucher_id: voucherId,
                subtotal: subtotal,
                shipping_method_id: shippingMethodId // Gửi ID phương thức vận chuyển
            },
            success: function(response) {
                $('#voucher-discount').text('₫' + response.voucher_discount.toLocaleString());
                $('#final-total').text('₫' + response.final_total.toLocaleString());
                // console.log(response.final_total.data);
                
                $('#selectedVoucherCode').text(`Đã áp dụng: ${voucherCode}`);

                $('#discountAmount').text('-' + response.voucher_discount + 'đ');
                $('#finalTotal').text(response.final_total + 'đ');

                $('#voucherModal').modal('hide');
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    }