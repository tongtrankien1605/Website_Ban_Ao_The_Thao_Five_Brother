            <!-- Header Top Start -->
            <div class="header-top header-top-one bg-theme-two">
                <div class="container-fluid">
                    <div class="row align-items-center justify-content-center">

                        <div class="col mt-10 mb-10 d-none d-md-flex">
                            <!-- Header Top Left Start -->
                            <div class="header-top-left">
                                <p>Chào Mừng Đến Với Shop Quần Áo Thể Thao FIVE BROTHER</p>
                                <p>Hotline: <a href="tel:0983456456">0983 456 456</a></p>
                            </div>
                            <!-- Header Top Left End -->
                        </div>

                        {{-- <div class="col mt-10 mb-10">
                            <!-- Header Language Currency Start -->
                            <ul class="header-lan-curr">

                                <li><a href="#">eng</a>
                                    <ul>
                                        <li><a href="#">english</a></li>
                                        <li><a href="#">spanish</a></li>
                                        <li><a href="#">france</a></li>
                                        <li><a href="#">russian</a></li>
                                        <li><a href="#">chinese</a></li>
                                    </ul>
                                </li>

                                <li><a href="#">$usd</a>
                                    <ul>
                                        <li><a href="#">pound</a></li>
                                        <li><a href="#">dollar</a></li>
                                        <li><a href="#">euro</a></li>
                                        <li><a href="#">yen</a></li>
                                    </ul>
                                </li>

                            </ul>
                            <!-- Header Language Currency End -->
                        </div> --}}

                        <div class="col mt-10 mb-10">
                            <!-- Header Shop Links Start -->
                            <div class="header-top-right">


                                {{-- <p><a href="{{ route('login-register') }}">Register</a><a href="{{ route('login-register') }}">Login</a></p> --}}
                                @if (Auth::check())
                                    <p><a href="{{ route('my-account') }}">Tài khoản của tôi</a></p>
                                @else
                                    <p><a href="{{ route('register') }}">Đăng ký</a><a
                                            href="{{ route('login') }}">Đăng nhập</a></p>
                                @endif

                            </div>
                            <!-- Header Shop Links End -->
                        </div>

                    </div>
                </div>
            </div>
            <!-- Header Top End -->
