<!-- Footer Top Section Start -->
<div class="footer-top-section section bg-theme-two-light section-padding">
    <div class="container">
        <div class="row mbn-40">

            <div class="footer-widget col-lg-6 col-md-6 col-12 mb-40">
                <h4 class="title">Liên hệ với chúng tôi</h4>
                <p><b>Địa chỉ:</b> Tòa nhà FPT Polytechnic, 13 phố Trịnh Văn Bô, phường Phương Canh, quận Nam Từ Liêm,
                    TP Hà Nội</p>
                <p><b>Điện thoại:</b><a href="tel:02485820808"> <u>024 8582 0808</u> </a></p>
                <p><b>Email:</b><a href="mailto:caodang@fpt.edu.vn"><u> caodang@fpt.edu.vn</u></a></p>
                <p><b>Website:</b><a href="https://caodang.fpt.edu.vn/" target="_blank" rel="noopener noreferrer"><u> https://caodang.fpt.edu.vn/</u></a></p>
            </div>

            {{-- <div class="footer-widget col-lg-3 col-md-6 col-12 mb-40">
                <h4 class="title">PRODUCTS</h4>
                <ul>
                    <li><a href="#">New Arrivals</a></li>
                    <li><a href="#">Best Seller</a></li>
                    <li><a href="#">Trendy Items</a></li>
                    <li><a href="#">Best Deals</a></li>
                    <li><a href="#">On Sale Products</a></li>
                    <li><a href="#">Featured Products</a></li>
                </ul>
            </div>

            <div class="footer-widget col-lg-3 col-md-6 col-12 mb-40">
                <h4 class="title">INFORMATION</h4>
                <ul>
                    <li><a href="#">About us</a></li>
                    <li><a href="#">Terms & Conditions</a></li>
                    <li><a href="#">Payment Method</a></li>
                    <li><a href="#">Product Warranty</a></li>
                    <li><a href="#">Return Process</a></li>
                    <li><a href="#">Payment Security</a></li>
                </ul>
            </div> --}}


            <div class="footer-widget col-lg-3 col-md-6 col-12 mb-40">
                <div class="mb-30">
                    <h5 class="title">Sản Phẩm</h5>
                    <ul>
                        <li><a href="{{ route('products.index')}}">Danh Sách Sản Phẩm</a></li>
                        {{-- <li><a href="#featuredProducts">Sản Phẩm Nổi Bật</a></li> --}}
                        <li><a href="{{ route('index')}}#featuredProducts">Sản Phẩm Nổi Bật</a></li>

                    </ul>
                </div>
                <div>
                    <h5 class="title">Thông Tin</h5>
                    <ul>
                        <li><a href="https://maps.app.goo.gl/xofyS8yAVHthCMVJA" target="_blank" rel="noopener noreferrer">Địa Chỉ</a></li>
                        <li><a href="https://caodang.fpt.edu.vn/" target="_blank" rel="noopener noreferrer">Website</a></li>
                        <li><a href="mailto:caodang@fpt.edu.vn" target="_blank" rel="noopener noreferrer">Email</a></li>
                        <li><a href="tel:02485820808" target="_blank" rel="noopener noreferrer">Số Điện Thoại</a></li>
                    </ul>
                </div>
            </div>



            <div class="footer-widget col-lg-3 col-md-6 col-12 mb-40">
                <h4 class="title">Bản Tin</h4>
                <p>Đăng ký bản tin của chúng tôi và nhận tất cả các cập nhật về sản phẩm của chúng tôi</p>

                <form id="mc-form" class="mc-form footer-subscribe-form">
                    <input id="mc-email" autocomplete="off" placeholder="Nhập email của bạn vào đây" name="EMAIL"
                        type="email">
                    <button id="mc-submit"><i class="fa fa-paper-plane-o"></i></button>
                </form>
                <!-- mailchimp-alerts Start -->
                <div class="mailchimp-alerts">
                    <div class="mailchimp-submitting"></div>
                    <!-- mailchimp-submitting end -->
                    <div class="mailchimp-success"></div>
                    <!-- mailchimp-success end -->
                    <div class="mailchimp-error"></div>
                    <!-- mailchimp-error end -->
                </div>
                <!-- mailchimp-alerts end -->

                <h5>Theo Dõi Chúng Tôi</h5>
                <p class="footer-social"><a href="https://www.facebook.com/fpt.poly">Facebook</a> - <a
                        href="https://www.youtube.com/@FPTPoly">Youtube</a></p>

            </div>

        </div>
    </div>
</div>
<!--Footer Top Section End-->
