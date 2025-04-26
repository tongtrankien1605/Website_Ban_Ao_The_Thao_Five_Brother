$(document).ready(function() {
    // Kiểm tra nếu không có địa chỉ nào
    if ($('#selected-address').text().trim() === '') {
        // Tự động mở modal thêm địa chỉ mới
        $('#addressFormModal').modal('show');
        // Tự động check vào checkbox địa chỉ mặc định
        $('#addressFormAdd #is_default').prop('checked', true);
    }

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

        $.ajax({
            url: url,
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#addressFormModal').modal('hide');
                
                // Nếu là địa chỉ đầu tiên, tự động cập nhật UI mà không cần load lại trang
                if ($('#selected-address').text().trim() === '') {
                    $('#selected-name-phone').text(`${response.name} Số điện thoại: ${response.phone}`);
                    $('#selected-address').text(response.address);
                } else {
                    $('#addressModal').modal('show');
                }
                
                // Refresh danh sách địa chỉ
                loadAddressList();
                
                // Hiển thị thông báo thành công
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công',
                    text: 'Thêm địa chỉ mới thành công',
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Có lỗi xảy ra khi thêm địa chỉ. Vui lòng thử lại.',
                    timer: 2000,
                    showConfirmButton: false
                });
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
                        
                        // Lưu trạng thái mặc định hiện tại
                        $('#addressFormModalEdit #current_is_default').val(res.is_default);
                        
                        const $checkbox = $('#addressFormModalEdit #is_default');
                        if (res.is_default == 1) {
                            $checkbox.prop('checked', true);
                            $checkbox.prop('disabled', true);
                        } else {
                            $checkbox.prop('checked', false);
                            $checkbox.prop('disabled', false);
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
    
            const addressId = $('#addressFormModalEdit #address_id').val();
            const currentIsDefault = $('#addressFormModalEdit #current_is_default').val();
            const $checkbox = $('#addressFormModalEdit #is_default');
            
            // Tạo object data để gửi đi
            const data = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                address_id: addressId,
                fullname: $('#addressFormModalEdit #fullname').val(),
                phone: $('#addressFormModalEdit #phone').val(),
                address: $('#addressFormModalEdit #address').val(),
                is_default: currentIsDefault === '1' ? '1' : ($checkbox.prop('checked') ? '1' : '0')
            };

            // Log để kiểm tra dữ liệu trước khi gửi
            console.log('Data to be sent:', data);
    
            $.ajax({
                url: `/address-user/update/${addressId}`,
                method: 'PUT',
                data: data,
                success: function(response) {
                    $('#addressFormModalEdit').modal('hide');
                    $('#addressModal').modal('show');

                    // Nếu địa chỉ là mặc định hoặc đang sửa địa chỉ mặc định
                    if (data.is_default === '1' || currentIsDefault === '1') {
                        // Cập nhật UI cho tất cả địa chỉ trong modal
                        $('.address-item').each(function() {
                            $(this).find('.badge.bg-danger').remove();
                            $(this).find('.btn-outline-danger').removeClass('d-none').show();
                        });

                        // Cập nhật thông tin địa chỉ ở trang chính ngay lập tức
                        const fullname = data.fullname;
                        const phone = data.phone;
                        const address = data.address;
                        
                        // Cập nhật địa chỉ hiển thị ở trang chính
                        $('#selected-name-phone').text(`${fullname} Số điện thoại: ${phone}`);
                        $('#selected-address').text(address);

                        // Xóa tất cả badge mặc định cũ
                        $('.delivery-info .badge').remove();

                        // Thêm badge "Mặc định" nếu là địa chỉ mặc định
                        if (data.is_default === '1') {
                            // Thêm badge mới sau địa chỉ
                            $('#selected-address').after('<span class="ms-2 badge" style="background: #ee4d2d; font-size: 12px;">Mặc Định</span>');
                        }
                    }

                    // Cập nhật lại danh sách địa chỉ
                    loadAddressList();

                    // Hiển thị thông báo thành công
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        text: 'Cập nhật địa chỉ thành công',
                        timer: 1500,
                        showConfirmButton: false
                    });
                },
                error: function(xhr) {
                    console.error('AJAX Error:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Không thể cập nhật địa chỉ',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        });
    
        // Tải lại danh sách địa chỉ bằng AJAX

    });
    

    // Select Address
    $(document).on('click', '.select-address', function() {
        const addressId = $(this).data('id');
        const currentRow = $(this).closest('.address-item');
        const defaultBadge = '<span class="badge bg-danger ms-2">Mặc định</span>';
        
        // Xóa trạng thái mặc định từ tất cả các địa chỉ trước
        $('.address-item').each(function() {
            $(this).find('.badge.bg-danger').remove();
            $(this).find('.btn-outline-danger').removeClass('d-none').show();
            $(this).find('.select-address').text('Chọn');
        });

        // Cập nhật UI cho địa chỉ được chọn
        currentRow.find('.btn-outline-danger').addClass('d-none').hide();
        currentRow.find('.select-address').text('Đã chọn');
        if (!currentRow.find('.badge.bg-danger').length) {
            currentRow.find('.address-info').append(defaultBadge);
        }

        $.ajax({
            url: `/address-user/default/${addressId}`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Server response:', response); // Debug response

                // Cập nhật thông tin địa chỉ được chọn ở phần checkout
                const fullname = response.fullname || response.name; // Kiểm tra cả hai trường hợp
                const phone = response.phone;
                
                if (fullname && phone) {
                    $('#selected-name-phone').text(`${fullname} Số điện thoại: ${phone}`);
                    $('#selected-address').text(response.address);

                    // Đóng modal
                    $('#addressModal').modal('hide');

                    // Hiển thị thông báo thành công
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        text: 'Đã cập nhật địa chỉ mặc định',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    console.error('Invalid response format:', response);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Dữ liệu địa chỉ không hợp lệ',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }

                // Cập nhật lại danh sách địa chỉ
                loadAddressList();
            },
            error: function(xhr) {
                console.error('AJAX Error:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Không thể cập nhật địa chỉ mặc định',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    });

    // Delete Address
    $('.delete-address').click(function() {
        const addressId = $(this).data('id');
        
        Swal.fire({
            title: 'Xác nhận xóa',
            text: 'Bạn có chắc muốn xóa địa chỉ này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/address-user/delete/${addressId}`,
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        loadAddressList();
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công',
                            text: 'Đã xóa địa chỉ',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            }
        });
    });

    function loadAddressList() {
        $.get(`/address-user/list`, function(html) {
            $('.address-list').html(html);
            
            // Tìm địa chỉ mặc định và cập nhật UI
            const defaultAddress = $('.address-item[data-is-default="1"]');
            if (defaultAddress.length) {
                // Cập nhật UI trong modal
                defaultAddress.find('.btn-outline-danger').addClass('d-none');
                if (!defaultAddress.find('.badge.bg-danger').length) {
                    defaultAddress.find('.address-info').append('<span class="badge bg-danger ms-2">Mặc định</span>');
                }

                // Lấy thông tin từ địa chỉ mặc định từ data attributes
                const fullname = defaultAddress.data('fullname');
                const phone = defaultAddress.data('phone');
                const address = defaultAddress.data('address');

                // Cập nhật thông tin ở trang chính ngay lập tức
                if (fullname && phone) {
                    $('#selected-name-phone').text(`${fullname} Số điện thoại: ${phone}`);
                    $('#selected-address').text(address);
                }

                // Đảm bảo các địa chỉ khác không có badge mặc định
                $('.address-item').not(defaultAddress).each(function() {
                    $(this).find('.badge.bg-danger').remove();
                    $(this).find('.btn-outline-danger').removeClass('d-none').show();
                });
            }

            // Gán lại các event handlers
            attachAddressEventHandlers();
        });
    }

    // Hàm gán các event handlers cho địa chỉ
    function attachAddressEventHandlers() {
        // Select address handler
        $('.select-address').click(function() {
            const addressId = $(this).data('id');
            const currentRow = $(this).closest('.address-item');
            
            $.post(`/address-user/default/${addressId}`, {
                _token: $('meta[name="csrf-token"]').attr('content')
            }, function(response) {
                $('#selected-name-phone').text(`${response.fullname} (+84) ${response.phone}`);
                $('#selected-address').text(response.address);

                $('.address-item').each(function() {
                    $(this).find('.default-badge').remove();
                    $(this).removeClass('active-address');
                    $(this).find('.set-default-btn').show();
                });

                currentRow.addClass('active-address');
                currentRow.find('.address-actions').before('<span class="default-badge badge bg-danger ms-2">Mặc định</span>');
                currentRow.find('.set-default-btn').hide();

                $('#addressModal').modal('hide');

                Swal.fire({
                    icon: 'success',
                    title: 'Thành công',
                    text: 'Đã cập nhật địa chỉ mặc định',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
        });

        // Delete address handler
        $('.delete-address').click(function() {
            const addressId = $(this).data('id');
            
            Swal.fire({
                title: 'Xác nhận xóa',
                text: 'Bạn có chắc muốn xóa địa chỉ này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/address-user/delete/${addressId}`,
                        method: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function() {
                            loadAddressList();
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công',
                                text: 'Đã xóa địa chỉ',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        });
    }

    // Gọi hàm gán event handlers khi trang được load
    $(document).ready(function() {
        attachAddressEventHandlers();
    });

    // Kiểm tra và áp dụng voucher từ localStorage
    const voucherData = JSON.parse(localStorage.getItem("voucherData"));
    if (voucherData) {
        // Cập nhật hiển thị voucher
        $('#selectedVoucherCode').text(`Đã áp dụng: ${voucherData.code}`);
        
        // Cập nhật giá trị giảm giá
        $('#voucher-discount').text('₫' + voucherData.discountAmount.toLocaleString());
        
        // Cập nhật tổng tiền
        const subtotal = parseInt($('#subtotal').text().replace(/[₫,.]/g, '')) || 0;
        const shippingCost = parseInt($('#shipping-cost').text().replace(/[₫,.]/g, '')) || 0;
        const finalTotal = subtotal + shippingCost - voucherData.discountAmount;
        
        $('#final-total').text('₫' + finalTotal.toLocaleString());
        
        // Lưu voucher ID vào input hidden
        $('#voucher_id').val(voucherData.code);
    }

    // Xử lý khi chọn voucher mới
    $(document).on('click', '.select-voucher', function () {
        const voucherId = $(this).data('id');
        const voucherCode = $(this).data('code');
        const voucherItem = $(this).closest('.voucher-item');
        
        // Gán vào input ẩn
        $('#voucher_id').val(voucherId);
        
        // Gọi function apply với code này
        applyVoucher(voucherCode);
    });

    function applyVoucher(voucherCode) {
        const url = "/voucher/apply";
        const voucherId = $('#voucher_id').val();
        const subtotal = parseInt($('#subtotal').text().replace(/[₫,.]/g, ''));
        const shippingMethodId = $('#shipping_method_id').val();

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
                shipping_method_id: shippingMethodId
            },
            success: function(response) {
                // Cập nhật hiển thị
                $('#voucher-discount').text('₫' + response.voucher_discount.toLocaleString());
                $('#final-total').text('₫' + response.final_total.toLocaleString());
                $('#selectedVoucherCode').text(`Đã áp dụng: ${voucherCode}`);

                // Lưu thông tin voucher mới vào localStorage
                localStorage.setItem("voucherData", JSON.stringify({
                    code: voucherCode,
                    discountAmount: response.voucher_discount,
                    newTotal: response.final_total
                }));

                $('#voucherModal').modal('hide');
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Không thể áp dụng voucher',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
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
                // Xóa thông tin voucher khỏi localStorage
                localStorage.removeItem("voucherData");
                
                if (res.redirect_url) {
                    // Nếu server trả về link thanh toán (VNPay), chuyển hướng người dùng
                    window.location.href = res.redirect_url;
                } else if (res.success === true) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        text: res.message || 'Đặt hàng thành công!',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '/my-account';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: res.message || 'Có lỗi xảy ra. Vui lòng thử lại.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Lỗi server. Vui lòng thử lại.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    });
    

    });
    

    