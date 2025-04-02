@extends('client.layouts.master')
@section('content')
    <!-- Page Banner Section Start -->
    <div class="page-banner-section section" style="background-image: url(/client/assets/images/hero/hero-1.jpg)">
        <div class="container">
            <div class="row">
                <div class="page-banner-content col">

                    <h1>Checkout</h1>
                    <ul class="page-breadcrumb">
                        <li><a href="{{ route('index') }}">Home</a></li>
                        <li><a href="{{ route('checkout') }}">Checkout</a></li>
                    </ul>

                </div>
            </div>
        </div>
    </div><!-- Page Banner Section End -->

    <!-- Countdown Timer -->
    <div class="countdown-timer" id="countdown"
        style="background-color: #f8f9fa; padding: 10px; text-align: center; position: fixed; top: 0; left: 0; right: 0; z-index: 1000; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <span style="font-weight: bold;">Time remaining: </span>
        <span id="timer" style="color: #e83e8c; font-size: 1.2em; font-weight: bold;"></span>
        <span id="attempts" style="margin-left: 20px; color: #dc3545;"></span>
    </div>

    <!-- Page Section Start -->
    <div class="page-section section section-padding" style="margin-top: 60px;">
        <div class="container">

            <!-- Checkout Form s-->
            <form action="{{ route('payOrder') }}" class="checkout-form" method="POST" id="checkoutForm">
                @csrf
                @method('POST')
                <div class="row row-50 mbn-40">

                    <div class="col-lg-7">

                        <!-- Billing Address -->
                        <div id="billing-form" class="mb-20">
                            <h4 class="checkout-title">Billing Address</h4>

                            <div class="row">

                                <div class="col-md-6 col-12 mb-5">
                                    <label>Full Name*</label>
                                    <input type="text" name="fullname" placeholder="First Name"
                                        value="{{ Auth::user()->name }}">
                                </div>

                                <div class="col-md-6 col-12 mb-5">
                                    <label>Email Address*</label>
                                    <input type="email" name="email" placeholder="Email Address"
                                        value="{{ Auth::user()->email }}">
                                </div>

                                <div class="col-12 mb-5">
                                    <label>Phone Number</label>
                                    <input type="text" name="phone_number"
                                        placeholder="Phone number"value="{{ Auth::user()->phone_number }}">
                                </div>

                                {{-- <div class="col-12 mb-5">
                                    <label>Địa chỉ (Tỉnh/Huyện/Xã)*</label>
                                    <select id="location-select" class="nice-select">
                                        <option value="" data-type="province">Chọn tỉnh/thành phố</option>
                                    </select>
                                </div>
                                 --}}
                                <div class="col-12 mb-5">
                                    <label>Address*</label>
                                    <select class="nice-select" name="address_id">
                                        @foreach ($address_user as $ad)
                                            <option value="{{ $ad->id }}">{{ $ad->address }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- <div class="col-12 mb-5">
                                    <label>Town/City*</label>
                                    <input type="text" placeholder="Town/City">
                                </div> --}}
                                <div class="col-md-6 col-12 mb-5">
                                    <label>Shipping Method*</label>
                                    <select id="shipping-method" class="nice-select" name="shipping_id">
                                        @foreach ($shipping as $sm)
                                            <option value="{{ $sm->id_shipping_method }}" data-cost="{{ $sm->cost }}">
                                                {{ $sm->name }} - {{ number_format($sm->cost) }} Đồng
                                            </option>
                                        @endforeach
                                    </select>

                                </div>


                            </div>

                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="row">

                            <!-- Cart Total -->
                            <div class="col-12 mb-40">

                                <h4 class="checkout-title">Cart Total</h4>

                                <div class="checkout-cart-total">
                                    <h4>Product <span>Total</span></h4>
                                    <ul>
                                        @foreach ($cartItem as $item)
                                            <li>{{ $item->skuses->name }} x {{ $item->quantity }}
                                                <span>{{ number_format($item->price * $item->quantity) }} Đồng</span>
                                            </li>
                                            <input type="hidden" name="cart_item_ids[]" value="{{ $item->id }}">
                                        @endforeach
                                    </ul>

                                    <p>Sub Total <span>{{ number_format($total) }} Đồng</span></p>
                                    @if ($new_total)
                                        <p hidden><span id="sub-total">{{ number_format($new_total) }} Đồng</span></p>
                                    @else
                                        <p hidden><span id="sub-total">{{ number_format($total) }} Đồng</span></p>
                                    @endif

                                    @if ($voucher)
                                        <input type="hidden" name="id_voucher" value="{{ $voucher->id }}">
                                        <p>Voucher
                                            <span>Giảm
                                                @if ($voucher->discount_type == 'percentage')
                                                    {{ number_format($voucher->discount_value) }}%
                                                @else
                                                    {{ number_format($voucher->discount_value) }} Đồng
                                                @endif
                                            </span>
                                        </p>
                                        @if ($voucher->discount_type == 'percentage')
                                            <p>Sale total
                                                <span>
                                                    {{ number_format($saleTotal) }} Đồng
                                                </span>
                                            </p>
                                        @endif
                                        <p>Total
                                            <span>
                                                {{ number_format($new_total) }} Đồng
                                            </span>
                                        </p>
                                    @endif
                                    <p>Shipping Fee <span id="shipping-cost">0 Đồng</span></p>
                                    <h4 class=" mt-2">Grand Total <span id="grand-total"></span></h4>


                                    <!-- Input ẩn để gửi shipping fee và grand total khi thanh toán -->
                                    <input type="hidden" name="shipping_cost" id="shipping-cost-input" value="0">
                                    <input type="hidden" name="grand_total" id="grand-total-input">
                                </div>


                            </div>

                            <!-- Payment Method -->
                            <div class="col-12 mb-40">

                                <h4 class="checkout-title">Payment Method</h4>

                                <div class="checkout-payment-method">
                                    @foreach ($paymentMethods as $method)
                                        <div class="single-method">
                                            <input type="radio" id="payment_{{ $method->id_payment_method }}"
                                                name="payment_method" value="{{ $method->id_payment_method }}" required>
                                            <label
                                                for="payment_{{ $method->id_payment_method }}">{{ $method->name }}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <button class="place-order">Place order</button>

                            </div>

                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div><!-- Page Section End -->

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const timerElement = document.getElementById('timer');
                const attemptsElement = document.getElementById('attempts');
                const countdown = document.getElementById('countdown');
                const checkoutForm = document.getElementById('checkoutForm');
                let attemptsRemaining = {{ Auth::user()->failed_attempts ?? 0 }};
                let timeLeft = 10; // 60 seconds timeout

                // Kiểm tra hàng tồn kho
                $.ajax({
                    url: '/check-stock',
                    type: 'GET',
                    success: function(response) {
                        if (response.out_of_stock) {
                            console.log('Sản phẩm đã hết hàng. Kích hoạt đồng hồ đếm ngược.');
                            timerElement.style.display = 'block'; // Hiện đồng hồ
                            attemptsElement.style.display = 'block';
                            startCountdown();
                        } else {
                            console.log('Sản phẩm còn hàng, không kích hoạt đồng hồ.');
                           countdown.style.display = 'none'; // Ẩn đồng hồ
                            // Cho phép người dùng đặt hàng
                            checkoutForm.querySelectorAll('input, button').forEach(element => {
                                element.disabled = false;
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('Lỗi khi kiểm tra tồn kho:', xhr.responseText);
                    }
                });

                function startCountdown() {
                    // Hiển thị số lần thử còn lại
                    attemptsElement.textContent = `Số lần thử còn lại: ${3 - attemptsRemaining}`;

                    function updateTimerDisplay() {
                        const minutes = Math.floor(timeLeft / 60);
                        const seconds = timeLeft % 60;
                        timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

                        if (timeLeft <= 30) {
                            timerElement.style.color = '#dc3545';
                            timerElement.style.fontWeight = 'bold';
                        }

                        if (timeLeft <= 10) {
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
                    checkoutForm.querySelectorAll('input, button').forEach(element => {
                        element.disabled = true;
                    });

                    alert('Phiên thanh toán đã hết hạn. Vui lòng thử lại.');

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

                    setTimeout(() => {
                        window.location.href = "/cart";
                    }, 2000);
                }
            });
        </script>
    @endpush

@endsection
