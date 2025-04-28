@extends('client.layouts.master')
@section('content')
    <!-- Page Banner Section Start -->
    <div class="page-banner-section section" style="background-image: url(/client/assets/images/hero/hero-1.jpg)">
        <div class="container">
            <div class="row">
                <div class="page-banner-content col">

                    <h1>Đăng ký</h1>
                    <ul class="page-breadcrumb">
                        <li><a href="{{ route('index') }}">Trang chủ</a></li>
                        <li><a href="{{ route('index_wishlist') }}">Yêu thích</a></li>
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
                    <div class="login-register-form-wrap">
                        <h3 class="text-center">Đăng ký</h3>
                        <form action="{{ route('register') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-12 mb-15">
                                    <input type="text" name="name" placeholder="Họ và tên"
                                        value="{{ old('name') }}">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mb-15">
                                    <input type="phone_number" name="phone_number" placeholder="Số điện thoại" value="{{ old('phone_number') }}">
                                    @error('phone_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mb-15">
                                    <input type="email" name="email" placeholder="Địa chỉ email" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mb-15">
                                    <input type="text" name="address" placeholder="Địa chỉ" value="{{ old('address') }}">
                                    @error('address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mb-15">
                                    <input type="password" name="password" placeholder="Mật khẩu">
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mb-15">
                                    <input type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu">
                                    @error('password_confirmation')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="text-center col-12  mb-15"><input type="submit" value="Đăng ký"
                                        class="w-100"></div>
                                <p class=" text-center ">Bạn đã có tài khoản?, <a href="{{ route('login') }}">Đăng nhập
                                        ngay </a> </p>

                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div><!-- Page Section End -->
@endsection