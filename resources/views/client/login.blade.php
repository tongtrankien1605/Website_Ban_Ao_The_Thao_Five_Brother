@extends('client.layouts.master')
@section('content')
    <!-- Page Banner Section Start -->
    <div class="page-banner-section section" style="background-image: url(/client/assets/images/hero/hero-1.jpg)">
        <div class="container">
            <div class="row">
                <div class="page-banner-content col">

                    <h1>Login</h1>
                    <ul class="page-breadcrumb">
                        <li><a href="{{ route('index') }}">Home</a></li>
                        <li><a href="{{ route('wishlist') }}">Wishlist</a></li>
                    </ul>

                </div>
            </div>
        </div>
    </div><!-- Page Banner Section End -->

    <!-- Page Section Start -->
    <div class="page-section section section-padding">
        <div class="container">
            <div class="row mbn-40">

                <div class="col-lg-4 col-12 mb-40 m-auto">
                    <div class="login-register-form-wrap">
                        <h3 class="text-center">Login</h3>
                        <form action="{{ route('login') }}" class="mb-30" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-12 mb-15">
                                    <input type="text" name="email" placeholder="Email" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mb-15">
                                    <input type="password" name="password" placeholder="Password">
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <p class=" text-end "><a href="{{ route('reset-password') }}">Quên mật khẩu?</a> </p>
                                <div class="col-12 text-center 98px"><input type="submit" value="Login" class="w-100">
                                </div>
                            </div>
                        </form>
                        <h4 class="text-center ">You can also login with...</h4>
                        <div class="social-login d-flex justify-content-center  mb-15">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-google-plus"></i></a>
                            <a href="#"><i class="fa fa-pinterest"></i></a>
                            <a href="#"><i class="fa fa-linkedin"></i></a>
                        </div>
                        <p class=" text-center ">Chưa có tài khoản, <a href="{{ route('register') }}">Đăng kí ngay </a> </p>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Page Section End -->
@endsection
