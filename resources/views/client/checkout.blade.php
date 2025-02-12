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
            <form action="#" class="checkout-form">
                <div class="row row-50 mbn-40">

                    <div class="col-lg-7">

                        <!-- Billing Address -->
                        <div id="billing-form" class="mb-20">
                            <h4 class="checkout-title">Billing Address</h4>

                            <div class="row">

                                <div class="col-md-6 col-12 mb-5">
                                    <label>First Name*</label>
                                    <input type="text" placeholder="First Name">
                                </div>

                                <div class="col-md-6 col-12 mb-5">
                                    <label>Last Name*</label>
                                    <input type="text" placeholder="Last Name">
                                </div>

                                <div class="col-md-6 col-12 mb-5">
                                    <label>Email Address*</label>
                                    <input type="email" placeholder="Email Address">
                                </div>

                                <div class="col-md-6 col-12 mb-5">
                                    <label>Phone no*</label>
                                    <input type="text" placeholder="Phone number">
                                </div>

                                <div class="col-12 mb-5">
                                    <label>Company Name</label>
                                    <input type="text" placeholder="Company Name">
                                </div>

                                <div class="col-12 mb-5">
                                    <label>Address*</label>
                                    <input type="text" placeholder="Address line 1">
                                    <input type="text" placeholder="Address line 2">
                                </div>

                                <div class="col-md-6 col-12 mb-5">
                                    <label>Country*</label>
                                    <select class="nice-select">
                                        <option>Bangladesh</option>
                                        <option>China</option>
                                        <option>country</option>
                                        <option>India</option>
                                        <option>Japan</option>
                                    </select>
                                </div>

                                <div class="col-md-6 col-12 mb-5">
                                    <label>Town/City*</label>
                                    <input type="text" placeholder="Town/City">
                                </div>

                                <div class="col-md-6 col-12 mb-5">
                                    <label>State*</label>
                                    <input type="text" placeholder="State">
                                </div>

                                <div class="col-md-6 col-12 mb-5">
                                    <label>Zip Code*</label>
                                    <input type="text" placeholder="Zip Code">
                                </div>

                                <div class="col-12 mb-5">
                                    <div class="check-box mb-15">
                                        <input type="checkbox" id="create_account">
                                        <label for="create_account">Create an Acount?</label>
                                    </div>
                                    <div class="check-box mb-15">
                                        <input type="checkbox" id="shiping_address" data-shipping>
                                        <label for="shiping_address">Ship to Different Address</label>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <!-- Shipping Address -->
                        <div id="shipping-form" class="mb-20">
                            <h4 class="checkout-title">Shipping Address</h4>

                            <div class="row">

                                <div class="col-md-6 col-12 mb-5">
                                    <label>First Name*</label>
                                    <input type="text" placeholder="First Name">
                                </div>

                                <div class="col-md-6 col-12 mb-5">
                                    <label>Last Name*</label>
                                    <input type="text" placeholder="Last Name">
                                </div>

                                <div class="col-md-6 col-12 mb-5">
                                    <label>Email Address*</label>
                                    <input type="email" placeholder="Email Address">
                                </div>

                                <div class="col-md-6 col-12 mb-5">
                                    <label>Phone no*</label>
                                    <input type="text" placeholder="Phone number">
                                </div>

                                <div class="col-12 mb-5">
                                    <label>Company Name</label>
                                    <input type="text" placeholder="Company Name">
                                </div>

                                <div class="col-12 mb-5">
                                    <label>Address*</label>
                                    <input type="text" placeholder="Address line 1">
                                    <input type="text" placeholder="Address line 2">
                                </div>

                                <div class="col-md-6 col-12 mb-5">
                                    <label>Country*</label>
                                    <select class="nice-select">
                                        <option>Bangladesh</option>
                                        <option>China</option>
                                        <option>country</option>
                                        <option>India</option>
                                        <option>Japan</option>
                                    </select>
                                </div>

                                <div class="col-md-6 col-12 mb-5">
                                    <label>Town/City*</label>
                                    <input type="text" placeholder="Town/City">
                                </div>

                                <div class="col-md-6 col-12 mb-5">
                                    <label>State*</label>
                                    <input type="text" placeholder="State">
                                </div>

                                <div class="col-md-6 col-12 mb-5">
                                    <label>Zip Code*</label>
                                    <input type="text" placeholder="Zip Code">
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
                                        <li>Samsome Notebook Pro 5 X 01 <span>$295.00</span></li>
                                        <li>Aquet Drone D 420 X 02 <span>$550.00</span></li>
                                        <li>Play Station X 22 X 01 <span>$295.00</span></li>
                                        <li>Roxxe Headphone Z 75 X 01 <span>$110.00</span></li>
                                    </ul>

                                    <p>Sub Total <span>$1250.00</span></p>
                                    <p>Shipping Fee <span>$00.00</span></p>

                                    <h4>Grand Total <span>$1250.00</span></h4>

                                </div>

                            </div>

                            <!-- Payment Method -->
                            <div class="col-12 mb-40">

                                <h4 class="checkout-title">Payment Method</h4>

                                <div class="checkout-payment-method">

                                    <div class="single-method">
                                        <input type="radio" id="payment_check" name="payment-method" value="check">
                                        <label for="payment_check">Check Payment</label>
                                        <p data-method="check">Please send a Check to Store name with Store Street, Store
                                            Town, Store State, Store Postcode, Store Country.</p>
                                    </div>

                                    <div class="single-method">
                                        <input type="radio" id="payment_bank" name="payment-method" value="bank">
                                        <label for="payment_bank">Direct Bank Transfer</label>
                                        <p data-method="bank">Please send a Check to Store name with Store Street, Store
                                            Town, Store State, Store Postcode, Store Country.</p>
                                    </div>

                                    <div class="single-method">
                                        <input type="radio" id="payment_cash" name="payment-method" value="cash">
                                        <label for="payment_cash">Cash on Delivery</label>
                                        <p data-method="cash">Please send a Check to Store name with Store Street, Store
                                            Town, Store State, Store Postcode, Store Country.</p>
                                    </div>

                                    <div class="single-method">
                                        <input type="radio" id="payment_paypal" name="payment-method" value="paypal">
                                        <label for="payment_paypal">Paypal</label>
                                        <p data-method="paypal">Please send a Check to Store name with Store Street, Store
                                            Town, Store State, Store Postcode, Store Country.</p>
                                    </div>

                                    <div class="single-method">
                                        <input type="radio" id="payment_payoneer" name="payment-method"
                                            value="payoneer">
                                        <label for="payment_payoneer">Payoneer</label>
                                        <p data-method="payoneer">Please send a Check to Store name with Store Street,
                                            Store Town, Store State, Store Postcode, Store Country.</p>
                                    </div>

                                    <div class="single-method">
                                        <input type="checkbox" id="accept_terms">
                                        <label for="accept_terms">I’ve read and accept the terms & conditions</label>
                                    </div>

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
