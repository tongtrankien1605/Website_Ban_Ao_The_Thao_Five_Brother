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

    <!-- Page Section Start -->
    <div class="page-section section section-padding">
        <div class="container">

            <!-- Checkout Form s-->
            <form action="{{ route('payOrder') }}" class="checkout-form" method="POST">
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

                                    <p>Sub Total <span id="sub-total">{{ number_format($new_total) }} Đồng</span></p>
                                    <p>Shipping Fee <span id="shipping-cost">0 Đồng</span></p>
                                    <h4>Grand Total <span id="grand-total">{{ number_format($new_total) }} Đồng</span></h4>

                                    <!-- Input ẩn để gửi shipping fee và grand total khi thanh toán -->
                                    <input type="hidden" name="shipping_cost" id="shipping-cost-input" value="0">
                                    <input type="hidden" name="grand_total" id="grand-total-input" value="{{ $new_total }}">
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
@endsection
