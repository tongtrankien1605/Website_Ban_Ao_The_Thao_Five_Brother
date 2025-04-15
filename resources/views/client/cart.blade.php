@extends('client.layouts.master')
@section('content')
    <!-- Page Banner Section Start -->
    @php
        $total = 0;
    @endphp
    <div class="page-banner-section section" style="background-image: url(/client/assets/images/hero/hero-1.jpg)">
        <div class="container">
            <div class="row">
                <div class="page-banner-content col">

                    <h1>Cart</h1>
                    <ul class="page-breadcrumb">
                        <li><a href="{{ route('index') }}">Home</a></li>
                        <li><a href="{{ route('show.cart') }}">Cart</a></li>
                    </ul>

                </div>
            </div>
        </div>
    </div><!-- Page Banner Section End -->

    <!-- Page Section Start -->
    <div class="page-section section section-padding">
        <div class="container">

            <form action="#">
                <div class="row mbn-40">
                    <div class="col-12 mb-40">
                        <div class="cart-table table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="pro-select">Select</th>
                                        <th class="pro-thumbnail">Image</th>
                                        <th class="pro-title">Product</th>
                                        <th class="pro-price">Price</th>
                                        <th class="pro-quantity">Quantity</th>
                                        <th class="pro-subtotal">Total</th>
                                        <th class="pro-remove">Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!$cartItem->isEmpty())
                                        @foreach ($cartItem as $cart)
                                            <tr>
                                                @php
                                                    $subtotal = floatval($cart->price) * intval($cart->quantity);
                                                    $total += $subtotal;
                                                    foreach ($inventory as $item){
                                                        if ($item->id == $cart->id) {
                                                            $cart->quantity = $item->quantity;
                                                        }
                                                    }
                                                @endphp
                                                <td class="pro-select">
                                                    <input type="checkbox" class="cart-checkbox" name="selected_items[]"
                                                        value="{{ $cart->id }}" data-price="{{ $cart->price }}"
                                                        data-quantity="{{ $cart->quantity }}">
                                                </td>

                                                <td class="pro-thumbnail"><a href="#"><img
                                                            src="{{ Storage::url($cart->skuses->image) }}"
                                                            alt="" /></a>
                                                </td>
                                                <td class="pro-title"><a href="#">{{ $cart->skuses->name }}</a></td>
                                                <td class="pro-price" data-price="{{ $cart->price }}">
                                                    <span class="amount">{{ number_format($cart->price) }} đồng</span>
                                                </td>
                                                <td class="pro-quantity">
                                                    <div class="pro-qty"><input   data-max="{{ $item->quantity }}"  data-id="{{ $cart->id }}"
                                                            class="dataInput" type="text" value="{{ $cart->quantity }}">
                                                    </div>
                                                </td>
                                                    

                                                <td class="pro-subtotal" id="subtotal-{{ $cart->id }}">
                                                    {{ number_format($subtotal) }} đồng
                                                </td>
                                                <input type="hidden" id="cart-total" value="{{ $total }}">
                                                <td class="pro-remove"><a
                                                        data-url="{{ route('remove.cart', ['id' => $cart->id]) }}"
                                                        href="{{ route('remove.cart', $cart->id) }}"
                                                        class="remove-btn">×</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan='7' class='text-center'><strong>Giỏ hàng trống</strong></td>
                                        </tr>
                                    @endif
                                </tbody>

                            </table>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-7 col-12 mb-40">
                        <div class="cart-buttons mb-30">
                            <a href="{{ route('index') }}">Continue Shopping</a>
                        </div>
                        {{-- <div class="cart-coupon">
                            <h4>Coupon</h4>
                            <p>Choose your coupon code if you have one.</p>
                            <div class="cuppon-form border-0 d-flex gap-3">
                                <div>
                                    <select id="voucher" class="form-control text-center"
                                        style="height: 40px;width: 300px; border-radius: 50px;">
                                        <option value="">--Chọn voucher--</option>
                                        @foreach ($listVoucher as $voucher)
                                            <option value="{{ $voucher->id }}" 
                                                data-code="{{$voucher->vouchers->code}}" 
                                                data-id="{{$voucher->vouchers->id}}"
                                                data-discount="{{$voucher->vouchers->discount_value}}"
                                                data-type="{{$voucher->vouchers->discount_type}}" 
                                                data-max-discount="{{$voucher->vouchers->max_discount_amount}}" 
                                                data-usage-left="{{$voucher->vouchers->total_usage}}"
                                                data-start-date="{{$voucher->vouchers->start_date}}" 
                                                data-end-date="{{$voucher->vouchers->end_date}}">
                                                Giảm
                                                {{ number_format($voucher->vouchers->discount_value) }}{{ $voucher->vouchers->discount_type == 'percentage' ? '%' : 'đ' }}
                                            Tối đa {{ number_format($voucher->vouchers->max_discount_amount) }}đ
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <input id="apply-voucher" type="submit" value="Apply Coupon" disabled />
                                </div>
                            </div>
                        </div> --}}
                    </div>
                    <div class="col-lg-4 col-md-5 col-12 mb-40">
                        @if (!$cartItem->isEmpty())
                            <div class="cart-total fix">
                                <h3>Cart Totals</h3>
                                <table>
                                    <tbody>
                                        <tr class="cart-subtotal">
                                            <th>Subtotal</th>
                                            <td>
                                                <span class="amount">{{ number_format($total) }} Đồng</span>
                                            </td>
                                        </tr>
                                        <tr class="order-total">
                                            <th>Total</th>
                                            <td>
                                                <strong><span class="amount">{{ number_format($total) }}
                                                        Đồng</span></strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="proceed-to-checkout section mt-30">
                                    <a href="{{ route('indexPayment') }}" class="checkout-btn">Proceed to Checkout</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </form>

        </div>
    </div><!-- Page Section End -->

@endsection