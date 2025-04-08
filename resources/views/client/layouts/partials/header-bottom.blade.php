<!-- Header Bottom Start -->
<div class="header-bottom header-bottom-one header-sticky">
    <div class="container-fluid">
        <div class="row menu-center align-items-center justify-content-between">

            <div class="col mt-15 mb-15">
                <!-- Logo Start -->
                <div class="header-logo">
                    <a href="{{ route('index') }}">
                        <img src="/client/assets/images/logo.png" alt="Jadusona" style="height: 80px !important;">
                    </a>
                </div>
                <!-- Logo End -->
            </div>

            <div class="col order-2 order-lg-3">
                <!-- Header Advance Search Start -->
                <div class="header-shop-links">

                    <div class="header-search">
                        <button class="search-toggle"><img src="/client/assets/images/icons/search.png"
                                alt="Search Toggle"><img class="toggle-close"
                                src="/client/assets/images/icons/close.png" alt="Search Toggle"></button>
                        <div class="header-search-wrap">
                            <form action="#">
                                <input type="text" placeholder="Type and hit enter">
                                <button><img src="/client/assets/images/icons/search.png" alt="Search"></button>
                            </form>
                        </div>
                    </div>

                    <div class="header-wishlist">
                        <a href="{{ route('index_wishlist') }}"><img src="/client/assets/images/icons/wishlist.png"
                                alt="Wishlist">
                            <span>02</span></a>
                    </div>

                    <div class="header-mini-cart">
                        <a href="{{ route('show.cart') }}"><img src="/client/assets/images/icons/cart.png" alt="Cart">
                            <span>{{$quantity}}({{number_format($total)}} Đồng)</span></a>
                    </div>

                </div>
                <!-- Header Advance Search End -->
            </div>

            <div class="col order-3 order-lg-2">
                <div class="main-menu">
                    <nav>
                        <ul>
                            <li class="active"><a href="{{ route('index') }}">HOME</a></li>
                            <li><a href="{{ route('product.index') }}">SHOP</a></li>
                            {{-- <li><a href="#">PAGES</a>
                                <ul class="sub-menu">
                                    <li><a href="{{ route('show.cart') }}">Cart</a></li>
                                    <li><a href="{{ route('checkout') }}">Checkout</a></li>
                                    <li><a href="{{ route('login-register') }}">Login & Register</a></li>
                                    <li><a href="{{ route('login') }}">Login</a></li>
                                    <li><a href="{{ route('register') }}">Register</a></li>
                                    <li><a href="{{ route('my-account') }}">My Account</a></li>
                                    <li><a href="{{ route('wishlist') }}">Wishlist</a></li>
                                    <li><a href="{{ route('404') }}">404 Error</a></li>
                                </ul>
                            </li> --}}
                            <li><a href="{{ route('post.index') }}">BLOG</a>
                                {{-- <ul class="sub-menu">
                                    <li><a href="{{ route('post.index') }}">Blog</a></li>
                                    <li><a href="{{ route('post.show',$post->id) }}">Single Blog</a></li>
                                </ul> --}}
                            </li>
                            <li><a href="{{ route('contact') }}">CONTACT</a></li>
                        </ul>
                    </nav>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div class="mobile-menu order-4 d-block d-lg-none col"></div>

        </div>
    </div>
</div>
<!-- Header BOttom End -->
