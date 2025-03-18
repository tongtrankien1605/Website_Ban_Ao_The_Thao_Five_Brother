@extends('client.layouts.master')
@section('content')
    <!-- Page Banner Section Start -->
    <div class="page-banner-section section" style="background-image: url(/client/assets/images/hero/hero-1.jpg)">
        <div class="container">
            <div class="row">
                <div class="page-banner-content col">

                    <h1>Login & Register</h1>
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

                <div class="col-lg-4 col-12 mb-40">
                    <div class="login-register-form-wrap">
                        <h3>Login</h3>
                        <form action="#" class="mb-30">
                            <div class="row">
                                <div class="col-12 mb-15"><input type="text" placeholder="Username or Email"></div>
                                <div class="col-12 mb-15"><input type="password" placeholder="Password"></div>
                                <div class="col-12"><input type="submit" value="Login"></div>
                            </div>
                        </form>
                        <h4>You can also login with...</h4>
                        <div class="social-login">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-google-plus"></i></a>
                            <a href="#"><i class="fa fa-pinterest"></i></a>
                            <a href="#"><i class="fa fa-linkedin"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-12 mb-40 text-center d-none d-lg-block">
                    <span class="login-register-separator"></span>
                </div>

                <div class="col-lg-6 col-12 mb-40 ms-auto">
                    <div class="login-register-form-wrap">
                        <h3>Register</h3>
                        <form action="#">
                            <div class="row">
                                <div class="col-md-6 col-12 mb-15"><input type="text" placeholder="Your Name"></div>
                                <div class="col-md-6 col-12 mb-15"><input type="text" placeholder="User Name"></div>
                                <div class="col-md-6 col-12 mb-15"><input type="email" placeholder="Email"></div>
                                <div class="col-md-6 col-12 mb-15"><input type="password" placeholder="Password"></div>
                                <div class="col-md-6 col-12 mb-15"><input type="password" placeholder="Confirm Password">
                                </div>
                                <div class="col-md-6 col-12"><input type="submit" value="Register"></div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div><!-- Page Section End -->
@endsection
