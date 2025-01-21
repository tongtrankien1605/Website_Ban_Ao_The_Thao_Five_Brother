@extends('client.layouts.master')
@section('content')
    <!-- Page Banner Section Start -->
    <div class="page-banner-section section" style="background-image: url(/client/assets/images/hero/hero-1.jpg)">
        <div class="container">
            <div class="row">
                <div class="page-banner-content col">

                    <h1>Contact us</h1>
                    <ul class="page-breadcrumb">
                        <li><a href="{{ route('index') }}">Home</a></li>
                        <li><a href="{{ route('contact') }}">Contact us</a></li>
                    </ul>

                </div>
            </div>
        </div>
    </div><!-- Page Banner Section End -->

    <!-- Page Section Start -->
    <div class="page-section section section-padding">
        <div class="container">
            <div class="row row-30 mbn-40">

                <div class="contact-info-wrap col-md-6 col-12 mb-40">
                    <h3>Get in Touch</h3>
                    <p>Jadusona is the best theme for elit, sed do eiusmod tempor dolor sit ame tse ctetur adipiscing elit,
                        sed do eiusmod tempor incididunt ut labore et lorna aliquatd minim veniam,</p>
                    <ul class="contact-info">
                        <li>
                            <i class="fa fa-map-marker"></i>
                            <p>256, 1st AVE, You address <br>will be here</p>
                        </li>
                        <li>
                            <i class="fa fa-phone"></i>
                            <p><a href="#">+01 235 567 89</a><a href="#">+01 235 286 65</a></p>
                        </li>
                        <li>
                            <i class="fa fa-globe"></i>
                            <p><a href="#">info@example.com</a><a href="#">www.example.com</a></p>
                        </li>
                    </ul>
                </div>

                <div class="contact-form-wrap col-md-6 col-12 mb-40">
                    <h3>Leave a Message</h3>
                    <form id="contact-form" action="https://whizthemes.com/mail-php/other/mail.php" method="post">
                        <div class="contact-form">
                            <div class="row">
                                <div class="col-lg-6 col-12 mb-30"><input type="text" name="con_name"
                                        placeholder="Your Name"></div>
                                <div class="col-lg-6 col-12 mb-30"><input type="email" name="con_email"
                                        placeholder="Email Address"></div>
                                <div class="col-12 mb-30">
                                    <textarea name="con_message" placeholder="Message"></textarea>
                                </div>
                                <div class="col-12"><input type="submit" value="send"></div>
                            </div>
                        </div>
                    </form>
                    <div class="form-message mt-3"></div>
                </div>

            </div>
        </div>
    </div><!-- Page Section End -->
@endsection
