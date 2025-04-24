@extends('client.layouts.master')
@section('content')
    <!-- Page Banner Section Start -->
    <div class="page-banner-section section" style="background-image: url(/client/assets/images/hero/hero-1.jpg)">
        <div class="container">
            <div class="row">
                <div class="page-banner-content col">

                    <h1>Liên hệ</h1>
                    <ul class="page-breadcrumb">
                        <li><a href="{{ route('index') }}">Trang chủ</a></li>
                        <li><a href="{{ route('contact') }}">Liên hệ</a></li>
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
                    <h3>Liên hệ với chúng tôi</h3>
                    <p>Five Brother - Thể thao là đam mê, trang phục là phong cách – Khám phá ngay bộ sưu tập áo thể thao mới nhất!"</p>
                    <ul class="contact-info">
                        <li>
                            <i class="fa fa-map-marker"></i>
                            <p>Cổng Ong, Tòa nhà FPT Polytechnic, 13 phố Trịnh Văn Bô, phường Phương Canh, quận Nam Từ Liêm, TP Hà Nội</p>
                        </li>
                        <li>
                            <i class="fa fa-phone"></i>
                            <p><a href="tel:02485820808">(024) 8582 0808</a></p>
                        </li>
                        <li>
                            <i class="fa fa-globe"></i>
                            <p><a href="mailto:caodang@fpt.edu.vn">caodang@fpt.edu.vn</a><a href="https://caodang.fpt.edu.vn/">https://caodang.fpt.edu.vn/</a></p>
                        </li>
                    </ul>
                </div>

                <div class="contact-form-wrap col-md-6 col-12 mb-40">
                    <h3>Để lại lời nhắn tại đây</h3>
                    <form id="contact-form" action="https://whizthemes.com/mail-php/other/mail.php" method="post">
                        <div class="contact-form">
                            <div class="row">
                                <div class="col-lg-6 col-12 mb-30"><input type="text" name="con_name"
                                        placeholder="Tên của bạn"></div>
                                <div class="col-lg-6 col-12 mb-30"><input type="email" name="con_email"
                                        placeholder="Địa chỉ email"></div>
                                <div class="col-12 mb-30">
                                    <textarea name="con_message" placeholder="Lời nhắn tại đây"></textarea>
                                </div>
                                <div class="col-12"><input type="submit" value="Gửi"></div>
                            </div>
                        </div>
                    </form>
                    <div class="form-message mt-3"></div>
                </div>

            </div>
        </div>
    </div><!-- Page Section End -->
@endsection
