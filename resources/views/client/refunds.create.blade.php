@extends('client.layouts.master')
@section('content')
    <!-- Page Banner Section Start -->
    <div class="page-banner-section section" style="background-image: url(/client/assets/images/hero/hero-1.jpg)">
        <div class="container">
            <div class="row">
                <div class="page-banner-content col">

                    <h1>Register</h1>
                    <ul class="page-breadcrumb">
                        <li><a href="{{ route('index') }}">Home</a></li>
                        <li><a href="{{ route('index_wishlist') }}">Wishlist</a></li>
                    </ul>

                </div>
            </div>
        </div>
    </div><!-- Page Banner Section End -->

    <!-- Page Section Start -->
    <div class="page-section section section-padding">
        <div class="container">
            <div class="row mbn-40">
                <div class="col-lg-6 col-12 mb-40 m-auto">
                    <div class="login-refund-form-wrap">
                        <h3 class="text-center">Yêu cầu hoàn hàng cho Đơn hàng #{{ $order->id }}</h3>
                        <form action="{{ route('refunds.store', $order->id) }}" method="post">
                            @csrf
                            <div>
                                <label>Lý do hoàn hàng:</label>
                                <textarea name="reason" required></textarea>
                            </div>
                            <div>
                                <label>Số lượng hoàn hàng:</label>
                                <input type="number" name="refund_amount" min="1" required>
                            </div>
                            <button type="submit">Gửi yêu cầu hoàn hàng</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div><!-- Page Section End -->
@endsection
