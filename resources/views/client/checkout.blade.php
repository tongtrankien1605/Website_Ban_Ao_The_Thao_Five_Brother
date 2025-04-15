@extends('client.layouts.master')

@section('content')
<div class="countdown-timer" id="countdown"
style="background-color: #f8f9fa; padding: 10px; text-align: center; position: fixed; top: 0; left: 0; right: 0; z-index: 1000; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: none;">
<span style="font-weight: bold;">Time remaining: </span>
<span id="timer" style="color: #e83e8c; font-size: 1.2em; font-weight: bold;"></span>
<span id="attempts" style="margin-left: 20px; color: #dc3545;"></span>
</div>
    <div class="page-section section" style="background-color: #f5f5f5; min-height: 100vh; padding-top: 20px;">
        <div class="container">
            <!-- Delivery Address Section -->
            <div class="checkout-section mb-3" style="background: #fff; border-radius: 3px; padding: 20px;">
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-map-marker-alt me-2" style="color: #ee4d2d;"></i>
                    <h4 style="margin: 0; font-size: 18px; color: #222;">Địa Chỉ Nhận Hàng</h4>
                </div>
                <div class="delivery-info">
                    <div class="d-flex justify-content-between align-items-center">
                        @foreach ($address_user as $address)
                            @if ($address->is_default)
                                <div>
                                    <span style="font-weight: 500; margin-right: 15px;"
                                        id="selected-name-phone">{{ $address->name }} Số điện thoại:
                                        {{ $address->phone }}</span>
                                    <span id="selected-address">{{ $address->address }}</span>
                                    <span class="ms-2 badge" style="background: #ee4d2d; font-size: 12px;">Mặc Định</span>
                                </div>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#addressModal"
                                    style="color: #ee4d2d; text-decoration: none;">Thay Đổi</a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div class="checkout-section mb-3" style="background: #fff; border-radius: 3px;">
                <div class="d-flex justify-content-between p-3"
                    style="background: #fafafa; border-bottom: 1px solid #efefef;">
                    <div class="d-flex align-items-center">
                        <span style="color: #ee4d2d; margin-right: 10px;">Sản phẩm</span>
                    </div>
                    <div class="d-flex" style="color: #888;">
                        <div style="width: 110px; text-align: center;">Đơn giá</div>
                        <div style="width: 110px; text-align: center;">Số lượng</div>
                        <div style="width: 110px; text-align: center;">Thành tiền</div>
                    </div>
                </div>

                <!-- Product Items -->
                @foreach ($cartItem as $item)
                    <div class="product-item p-3" style="border-bottom: 1px solid #efefef;" 
                        data-item-id="{{ $item->id }}" 
                        data-variant-id="{{ $item->id_product_variant }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center" style="flex: 1;">
                                <img src="{{ Storage::url($item->skuses->image) }}" alt="Product"
                                    style="width: 80px; height: 80px; object-fit: cover; margin-right: 10px;">
                                <div>
                                    <div style="font-size: 14px; margin-bottom: 5px;">{{ $item->skuses->name }}</div>
                                    <div style="font-size: 12px; color: #888;">Loại: {{ $item->skuses->variant_name }}</div>
                                </div>
                            </div>
                            <div class="d-flex" style="color: #888;">
                                <div style="width: 110px; text-align: center;">₫{{ number_format($item->price) }}</div>
                                <div style="width: 110px; text-align: center;">{{ $item->quantity }}</div>
                                <div style="width: 110px; text-align: center; color: #ee4d2d;">
                                    ₫{{ number_format($item->price * $item->quantity) }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Message to Seller -->
                <div class="p-3" style="border-bottom: 1px solid #efefef;">
                    <div class="d-flex align-items-center">
                        <span style="color: #888; margin-right: 10px;">Lời nhắn:</span>
                        <input type="text" class="form-control" id="message" placeholder="Lưu ý cho Người bán..."
                            style="border: 1px solid #efefef; font-size: 14px; padding: 5px 10px;">
                    </div>
                </div>

                <!-- Shipping Method -->
                <div class="p-3" style="border-bottom: 1px solid #efefef;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <span style="margin-right: 50px;">Đơn vị vận chuyển:</span>
                            <div>
                                @if(isset($shipping_methods) && count($shipping_methods) > 0)
                                <div style="font-weight: 500;" id="shipping-name" data-cost="{{$shipping_methods[0]->cost}}">{{ $shipping_methods[0]->name }}</div>
                                <div style="color: #888; font-size: 12px;">
                                    Nhận hàng vào <span id="shipping-time">{{ $shipping_methods[0]->estimated_time }}</span>
                                </div>
                                @else
                                <div style="font-weight: 500;" id="shipping-name">Chưa có phương thức vận chuyển</div>
                                <div style="color: #888; font-size: 12px;">
                                    Nhận hàng vào <span id="shipping-time">--</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            @if(isset($shipping_methods) && count($shipping_methods) > 0)
                            <span style="color: #ee4d2d; margin-right: 10px;" id="shipping-cost">₫{{ number_format($shipping_methods[0]->cost, 0, ',', '.') }}</span>
                            @else
                            <span style="color: #ee4d2d; margin-right: 10px;" id="shipping-cost">₫0</span>
                            @endif
                            <a href="#" data-bs-toggle="modal" data-bs-target="#shippingModal"
                                style="color: #ee4d2d; text-decoration: none;">Thay Đổi</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vouchers Section -->
            <div class="checkout-section mb-3" style="background: #fff; border-radius: 3px; padding: 20px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-ticket-alt me-2" style="color: #ee4d2d;"></i>
                        <span>Shopee Voucher</span>
                    </div>
                    <div>
                        <span id="selectedVoucherCode" class="me-2" style="color: green; font-weight: bold;"></span>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#voucherModal"
                            style="color: #ee4d2d; text-decoration: none;">Chọn Voucher</a>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="checkout-section mb-3" style="background: #fff; border-radius: 3px; padding: 20px;">
                <h4 style="margin: 0 0 10px; font-size: 16px;">Phương thức thanh toán</h4>
            
                @foreach ($paymentMethods as $pm)
                    <div>
                        <label>
                            <input type="radio" name="payment_method_id" value="{{ $pm->id_payment_method }}">
                            {{ $pm->name }}
                        </label>
                    </div>
                @endforeach
            
                <input type="hidden" id="payment_method_id" name="payment_method_id">
            </div>
            
            <!-- Order Summary -->
            <div class="checkout-section" style="background: #fff; border-radius: 3px; padding: 20px;">
                <div class="d-flex justify-content-between mb-2">
                    <span>Tổng tiền hàng</span>
                    <span id="subtotal">₫{{ number_format($total) }}</span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Phí vận chuyển</span>
                    @if(isset($shipping_methods) && count($shipping_methods) > 0)
                    <span class="shipping-cost-summary">₫{{ number_format($shipping_methods[0]->cost, 0, ',', '.') }}</span>
                    @else
                    <span class="shipping-cost-summary">₫0</span>
                    @endif
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Giảm voucher</span>
                    <span id="voucher-discount">₫0</span>
                </div>

                <div class="d-flex justify-content-between mb-3" style="padding: 15px 0; border-top: 1px solid #efefef;">
                    <span>Tổng thanh toán</span>
                    <span id="final-total" class="final-total" style="color: #ee4d2d; font-size: 20px; font-weight: 500;">
                        @if(isset($shipping_methods) && count($shipping_methods) > 0)
                        ₫{{ number_format($total + $shipping_methods[0]->cost) }}
                        @else
                        ₫{{ number_format($total) }}
                        @endif
                    </span>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" id="btn-place-order" class="btn" data-url="{{ route('payOrder') }}"
                        style="background-color: #ee4d2d; color: #fff; padding: 10px 50px; border-radius: 3px;">
                        Đặt hàng
                    </button>
                </div>

                <!-- Hidden inputs -->
                <input type="hidden" id="voucher_id" value="">
                @if(isset($shipping_methods) && count($shipping_methods) > 0)
                <input type="hidden" id="shipping_method_id" value="{{ $shipping_methods[0]->id_shipping_method }}">
                @else
                <input type="hidden" id="shipping_method_id" value="">
                @endif
            </div>
        </div>
    </div>

    <!-- Modal chọn Voucher -->
    <div class="modal fade" id="voucherModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">
                <h5>Chọn Voucher</h5>
                <ul class="list-group" id="voucher-list">
                    @foreach ($userVouchers as $item)
                        <li class="list-group-item voucher-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $item['vouchers']['code'] }}</strong><br>
                                @if ($item['vouchers']['discount_type'] == 'percentage')
                                    Giảm {{ $item['vouchers']['discount_value'] }}%
                                @else
                                    Giảm ₫{{ number_format($item['vouchers']['discount_value']) }}
                                @endif
                            </div>
                            <button class="btn btn-sm btn-outline-danger select-voucher"
                                data-id="{{ $item['vouchers']['id'] }}" data-code="{{ $item['vouchers']['code'] }}"
                                data-type="{{ $item['vouchers']['discount_type'] }}"
                                data-value="{{ $item['vouchers']['discount_value'] }}">
                                Dùng
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- Modal Chọn Phương Thức Vận Chuyển -->
    <div class="modal fade" id="shippingModal" tabindex="-1" aria-labelledby="shippingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">
                <div class="modal-header">
                    <h5 class="modal-title">Chọn đơn vị vận chuyển</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    @foreach ($shipping_methods as $method)
                        <div class="form-check mb-3">
                            <input class="form-check-input shipping-option" type="radio" name="shipping_method"
                                id="shipping_{{ $method->id_shipping_method }}"
                                value="{{ $method->id_shipping_method }}" data-name="{{ $method->name }}"
                                data-cost="{{ number_format($method->cost, 0, ',', '.') }}"
                                data-time="{{ $method->estimated_time }}" {{ $loop->first ? 'checked' : '' }}>
                            <label class="form-check-label" for="shipping_{{ $method->id_shipping_method }}">
                                <strong>{{ $method->name }}</strong> - ₫{{ number_format($method->cost, 0, ',', '.') }}
                                <br><small>{{ $method->estimated_time }}</small>
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary choose-shipping"
                        style="background-color: #ee4d2d; border: none;">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Address Modal -->
    <div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #fff; border-bottom: 1px solid #efefef;">
                    <h5 class="modal-title" id="addressModalLabel" style="font-size: 18px; color: #222;">Địa Chỉ Của Tôi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 0;">
                    <!-- Address List -->
                    <div class="address-list" style="max-height: 400px; overflow-y: auto;">
                        @include('client.layouts.partials.address_item')
                    </div>

                    <!-- Add New Address Button -->
                    <div class="p-3">
                        <button class="btn w-100" id="addNewAddress" data-bs-toggle="modal"
                            data-bs-target="#addressFormModal"
                            style="border: 1px dashed #ee4d2d; color: #ee4d2d; background: #fff; padding: 10px;">
                            <i class="fas fa-plus me-2"></i>Thêm Địa Chỉ Mới
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Address Form Modal -->
    <div class="modal fade" id="addressFormModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addressFormTitle">Thêm Địa Chỉ Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addressFormAdd">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" id="fullname" name="fullname" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ cụ thể</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_default" name="is_default">
                            <label class="form-check-label" for="is_default">Đặt làm địa chỉ mặc định</label>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal" data-bs-toggle="modal"
                                data-bs-target="#addressModal">Hủy</button>
                            <button type="submit" class="btn btn-primary"
                                style="background-color: #ee4d2d; border: none;">
                                Hoàn thành
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addressFormModalEdit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addressFormTitle">Sửa địa chỉ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addressFormEdit">
                        @csrf
                        <input type="hidden" id="address_id" name="address_id">
                        <div class="mb-3">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" id="fullname" name="fullname" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ cụ thể</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="hidden" name="is_default" value="0">
                            <input type="checkbox" class="form-check-input" id="is_default" name="is_default"
                                value="1" {{ $address->is_default ? 'checked disabled' : '' }}>
                            <label class="form-check-label" for="is_default">Đặt làm địa chỉ mặc định</label>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal" data-bs-toggle="modal"
                                data-bs-target="#addressModal">Hủy</button>
                            <button type="submit" class="btn btn-primary"
                                style="background-color: #ee4d2d; border: none;">
                                Hoàn thành
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Khởi tạo Laravel Echo
        // window.Echo = new Echo({
        //     broadcaster: 'pusher',
        //     key: '{{ env("PUSHER_APP_KEY") }}',
        //     cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
        //     encrypted: true
        // });



        document.addEventListener('DOMContentLoaded', function() {
            const timerElement = document.getElementById('timer');
            const attemptsElement = document.getElementById('attempts');
            const countdown = document.getElementById('countdown');
            let attemptsRemaining = {{ Auth::user()->failed_attempts ?? 0 }};
            let timeLeft = 1000; // 15 minutes (900 seconds) timeout

            // Kiểm tra hàng tồn kho
            $.ajax({
                url: '/check-stock',
                type: 'GET',
                success: function(response) {
                    console.log('Stock check response:', response);
                    if (response.out_of_stock) {
                        console.log(response.out_of_stock)
                        console.log('Sản phẩm đã hết hàng. Kích hoạt đồng hồ đếm ngược.');
                        countdown.style.display = 'block'; // Hiện đồng hồ
                        timerElement.style.display = 'inline'; // Hiện đồng hồ
                        attemptsElement.style.display = 'inline'; // Hiện số lần thử
                        startCountdown();
                    } else {
                        console.log('Sản phẩm còn hàng, không kích hoạt đồng hồ.');
                        countdown.style.display = 'none'; // Ẩn đồng hồ
                    }
                },
                error: function(xhr) {
                    console.error('Lỗi khi kiểm tra tồn kho:', xhr.responseText);
                }
            });

            function startCountdown() {
                console.log('Starting countdown with timeLeft:', timeLeft);
                // Hiển thị số lần thử còn lại
                attemptsElement.textContent = `Số lần thử còn lại: ${3 - attemptsRemaining}`;

                function updateTimerDisplay() {
                    const minutes = Math.floor(timeLeft / 60);
                    const seconds = timeLeft % 60;
                    timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                    console.log('Timer updated:', timerElement.textContent);

                    if (timeLeft <= 300) { // 5 minutes remaining
                        timerElement.style.color = '#dc3545';
                        timerElement.style.fontWeight = 'bold';
                    }

                    if (timeLeft <= 60) { // 1 minute remaining
                        timerElement.style.animation = "blink 1s infinite";
                    }
                }

                updateTimerDisplay();

                const timer = setInterval(function() {
                    if (timeLeft > 0) {
                        timeLeft--;
                        updateTimerDisplay();
                    } else {
                        clearInterval(timer);
                        handleTimeout();
                    }
                }, 1000);
            }

            function handleTimeout() {
                // Disable all form elements
                document.querySelectorAll('input, button').forEach(element => {
                    element.disabled = true;
                });

                alert('Phiên thanh toán đã hết hạn. Vui lòng thử lại.');

                // Create a payment attempt record
                $.ajax({
                    url: '/create-payment-attempt',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (data.attempts_remaining <= 0) {
                            alert('Tài khoản của bạn đã bị khóa!');
                            window.location.href = data.redirect;
                        } else {
                            window.location.href = "/cart";
                        }
                    },
                    error: function(xhr) {
                        console.error('Lỗi:', xhr.responseText);
                        alert('Lỗi xảy ra! Kiểm tra console.');
                    }
                });
            }
        });
<<<<<<< HEAD

        // Khởi tạo Echo instance
       
=======
>>>>>>> 7e4a9e15afdb669463b561f09a6807268efd4efe
    </script>
      @vite('resources/js/updateCheckout.js')

    <style>
        .form-control:focus {
            border-color: #ee4d2d;
            box-shadow: none;
        }

        .checkout-section {
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .05);
        }

        .address-item:hover {
            background-color: #fffefb;
        }

        .btn-outline-primary:hover {
            background-color: #ee4d2d;
            border-color: #ee4d2d;
            color: #fff !important;
        }

        .form-check-input:checked {
            background-color: #ee4d2d;
            border-color: #ee4d2d;
        }
        
        @keyframes blink {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    </style>
@endsection
