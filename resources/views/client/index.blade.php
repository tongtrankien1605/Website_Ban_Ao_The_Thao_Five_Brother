@extends('client.layouts.master')
@section('content')
    <!-- Hero Section Start -->
    <div class="hero-section section">

        <!-- Hero Slider Start -->
        <div class="hero-slider hero-slider-one fix">

            <!-- Hero Section Start -->
            <div class="hero-item" style="background-image: url(/client/assets/images/hero/hero-1.jpg)">
                <div class="hero-overlay">
                    <div class="hero-content">
                        <h1>Khám phá bộ sưu tập áo thể thao 2025 <br>– Sale cực mạnh –</h1>
                        <a class="cta-button" href="{{ route('product.index') }}">MUA NGAY</a>
                    </div>
                </div>
            </div>

            <div class="hero-item" style="background-image: url(/client/assets/images/hero/hero-2.jpg)">
                <div class="hero-overlay">
                    <div class="hero-content">
                        <h1>Giảm giá 35% <br>Áo đấu mùa giải mới</h1>
                        <a class="cta-button" href="{{ route('product.index') }}">MUA NGAY</a>
                    </div>
                </div>
            </div>
            <!-- Hero Section End -->

        </div>
        <!-- Hero Slider End -->

    </div>
    <!-- Hero Section End -->

    <!-- Banner Section Start -->
    <div class="banner-section section mt-40">
        <div class="container-fluid">
            <div class="row row-10 mbn-20">

                <div class="col-lg-4 col-md-6 col-12 mb-20">
                    <div class="banner banner-1 content-left content-middle">

                        <a href="" class="image"><img src="/client/assets/images/banner/banner-1.jpg"
                                alt="Banner Image"></a>

                        <div class="content">
                            <h1 style="color: white; background: rgba(0, 0, 0, 0.297);">Hàng mới <br>Áo ĐTQG <br>Giảm giá
                                30%</h1>
                            <a style="color: black" href="{{ route('product.index') }}" data-hover="SHOP NOW">SHOP NOW</a>
                        </div>

                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12 mb-20">
                    <a href="" class="banner banner-2">

                        <img src="/client/assets/images/banner/banner-2.jpg" alt="Banner Image">

                        {{-- <div class="content bg-theme-one">
                            <h1>New Toy’s for your Baby</h1>
                        </div> --}}

                        <div class="content ">
                            <h1
                                style="
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%);
                            background: rgba(0, 0, 0, 0.5);
                            color: white;
                            padding: 10px 20px;
                            font-size: 18px;
                            border-radius: 5px;
                            font-weight: bold;
                          ">
                                Áo Đấu Tự Thiết Kế</h1>


                        </div>

                        <span class="banner-offer">Giảm 25%</span>

                    </a>
                </div>

                <div class="col-lg-4 col-md-6 col-12 mb-20">
                    <div class="banner banner-1 content-left content-top">

                        <a href="" class="image"><img src="/client/assets/images/banner/banner-3.jpg"
                                alt="Banner Image"></a>

                        <div class="content">
                            <h1 style="color: white; background: rgba(0, 0, 0, 0.297)">Áo đấu <br>câu lạc bộ</h1>
                            <a href="{{ route('product.index') }}" data-hover="SHOP NOW">SHOP NOW</a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Banner Section End -->

    <!-- Product Section Start -->
    <div class="product-section section section-padding">
        <div class="container">

            <div class="row">
                <div class="section-title text-center col mb-30">
                    <h1>Danh Sách Sản Phẩm</h1>
                    {{-- <p>All popular product find here</p> --}}
                </div>
            </div>

            <div class="row mbn-40">
                @foreach ($products as $product)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-40">

                        <div class="product-item">
                            <div class="product-inner">

                                <div class="image">
                                    <div class="bg-light border rounded d-flex justify-content-center align-items-center">
                                        <img src="{{ Storage::url($product->image) }}" alt=""
                                            style="height: 300px;width: 300px; overflow: hidden;">
                                    </div>

                                    <div class="image-overlay">
                                        <div class="action-buttons">
                                            <button><a href="{{ route('product.show', $product->id) }}">Add to
                                                    cart</a></button>
                                            <button class="add_to_wishlist"
                                                data-url="{{ route('add_wishlist', ['id' => $product->id]) }}">Add to
                                                wishlist</button>
                                        </div>
                                    </div>

                                </div>

                                <div class="content">

                                    <div class="content-left">

                                        <h4 class="title"><a
                                                href="{{ route('product.show', $product) }}">{{ $product->name }}</a>
                                        </h4>

                                        <div class="ratting">
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        </div>

                                        {{-- <h5 class="size">Size: <span>S</span><span>M</span><span>L</span><span>XL</span>
                                        </h5>
                                        <h5 class="color">Color: <span style="background-color: #ffb2b0"></span><span
                                                style="background-color: #0271bc"></span><span
                                                style="background-color: #efc87c"></span><span
                                                style="background-color: #00c183"></span></h5> --}}

                                    </div>

                                    <div class="content-right">
                                        <span class="price">{{ $product->price }}</span>
                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>
                @endforeach

            </div>

        </div>
    </div>
    <!-- Product Section End -->

    <!-- Banner Section Start -->
    <div id="featuredProducts" class="banner-section section section-padding pt-0 fix">
        <div class="row row-5 mbn-10">

            <div class="col-lg-4 col-md-6 col-12 mb-10">
                <div class="banner banner-3">

                    <a href="#" class="image"><img src="/client/assets/images/banner/banner-4.jpg"
                            alt="Banner Image"></a>

                    <div class="content" style="background-image: url(/client/assets/images/banner/banner-3a-shape.png)">
                        <h1>Áo Mới Về</h1>
                        <h2>Giảm đến 35%</h2>
                        <h4>Phù hợp từ tập luyện đến thi đấu</h4>
                    </div>

                    {{-- <a href="{{ route('product.index') }}" class="shop-link" data-hover="SHOP NOW">SHOP NOW</a> --}}

                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12 mb-10">
                <div class="banner banner-4">

                    <a href="#" class="image"><img src="/client/assets/images/banner/banner-5.jpg"
                            alt="Banner Image"></a>

                    <div class="content">
                        <div class="content-inner">
                            <h1>Mua Online Siêu Tiện Lợi</h1>
                            <h2>Ưu đãi đến 25%<br>Xu hướng thể thao mới nhất 2025</h2>
                            {{-- <a href="{{ route('product.index') }}" data-hover="SHOP NOW">SHOP NOW</a> --}}
                        </div>
                    </div>


                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12 mb-10">
                <div class="banner banner-5">

                    <a href="#" class="image"><img src="/client/assets/images/banner/banner-6.jpg"
                            alt="Banner Image"></a>

                    <div class="content" style="background-image: url(/client/assets/images/banner/banner-5a-shape.png)">
                        <h1>Bộ Sưu Tập Nam/Nữ<br>Đa dạng mẫu mã, thấm hút tốt</h1>
                        <h2>Ưu đãi đến 25%</h2>
                    </div>

                    {{-- <a href="{{ route('product.index') }}" class="shop-link" data-hover="SHOP NOW">SHOP NOW</a> --}}

                </div>
            </div>

        </div>
    </div>
    <!-- Banner Section End -->

    <!-- Product Section Start -->
    <div class="product-section section section-padding pt-0">
        <div class="container">

            <div class="row" >
                <div class="section-title text-center col mb-30">
                    <h1>Sản Phẩm Nổi Bật</h1>
                    {{-- <p>All popular product find here</p> --}}
                </div>
            </div>

            <div class="row mbn-40">
                @foreach ($productFeatured as $product)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-40">

                        <div class="product-item">
                            <div class="product-inner">

                                <div class="image">
                                    <div class="bg-light border rounded d-flex justify-content-center align-items-center">
                                        <img src="{{ Storage::url($product->image) }}" alt=""
                                            style="height: 300px;width: 300px; overflow: hidden;">
                                    </div>

                                    <div class="image-overlay">
                                        <div class="action-buttons">
                                            <button><a href="{{ route('product.show', $product->id) }}">Add to
                                                    cart</a></button>
                                            <button class="add_to_wishlist"
                                                data-url="{{ route('add_wishlist', ['id' => $product->id]) }}">Add to
                                                wishlist</button>
                                        </div>
                                    </div>

                                </div>

                                <div class="content">

                                    <div class="content-left">

                                        <h4 class="title"><a
                                                href="{{ route('product.show', $product) }}">{{ $product->name }}</a>
                                        </h4>

                                        <div class="ratting">
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        </div>

                                        {{-- <h5 class="size">Size: <span>S</span><span>M</span><span>L</span><span>XL</span>
                                        </h5>
                                        <h5 class="color">Color: <span style="background-color: #ffb2b0"></span><span
                                                style="background-color: #0271bc"></span><span
                                                style="background-color: #efc87c"></span><span
                                                style="background-color: #00c183"></span></h5> --}}

                                    </div>

                                    <div class="content-right">
                                        <span class="price">{{ $product->price }}</span>
                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>
                @endforeach

            </div>

        </div>
    </div>
    <!-- Product Section End -->

    <!-- Feature Section Start -->
    <div class="feature-section bg-theme-two section section-padding fix"
        style="background-image: url(/client/assets/images/pattern/pattern-dot.png);">
        <div class="container">
            <div class="feature-wrap row justify-content-between mbn-30">

                <div class="col-md-4 col-12 mb-30">
                    <div class="feature-item text-center">

                        <div class="icon"><img src="/client/assets/images/feature/feature-1.png" alt="Image"></div>
                        <div class="content">
                            <h3>Đa dạng mẫu mã</h3>
                            <p>Mặc không vừa? Đổi ngay mẫu khác!</p>
                        </div>

                    </div>
                </div>

                <div class="col-md-4 col-12 mb-30">
                    <div class="feature-item text-center">

                        <div class="icon"><img src="/client/assets/images/feature/feature-2.png" alt="Image"></div>
                        <div class="content">
                            <h3>Giao hàng toàn quốc </h3>
                            <p>Chi phí ưu đãi nhất</p>
                        </div>

                    </div>
                </div>

                <div class="col-md-4 col-12 mb-30">
                    <div class="feature-item text-center">

                        <div class="icon"><img src="/client/assets/images/feature/feature-3.png" alt="Image"></div>
                        <div class="content">
                            <h3>Cam kết chính hãng</h3>
                            <p>Hàng chất lượng, chuẩn thương hiệu</p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Feature Section End -->

    <!-- Blog Section Start -->
    <div class="blog-section section section-padding">
        <div class="container">
            <div class="row mbn-40">

                <div class="col-xl-6 col-lg-5 col-12 mb-40">

                    <div class="row">
                        <div class="section-title text-start col mb-30">
                            <h1>Khách Hàng</h1>
                            <p>& một số nhận xét, đánh giá</p>
                        </div>
                    </div>

                    <div class="row mbn-40">

                        <div class="col-12 mb-40">
                            <div class="testimonial-item">
                                <p>"Áo thể thao không chỉ để tập gym, chơi bóng đá hay chạy bộ mà còn phải 'chất' để mặc
                                    thường ngày.
                                    Trang web này của <b>Five Brother</b> làm tôi phải ghé lại nhiều lần!"</p>
                                <div class="testimonial-author">
                                    <img src="/client/assets/images/testimonial/testimonial-1.png" alt="Image">
                                    <div class="content">
                                        <h4>Sơn Tùng - MTP </h4>
                                        <p>CEO, M-TP Entertainment</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-40">
                            <div class="testimonial-item">
                                <p>"Tôi luôn ưu tiên sự thoải mái và chất lượng trong từng chuyển động. Những chiếc áo thể
                                    thao ở
                                    <b>Five Brother</b> khiến tôi cảm thấy nhẹ nhàng và linh hoạt, giống như khi tôi đang
                                    thi đấu trên sân cỏ.
                                    Một trải nghiệm mua sắm tuyệt vời!"
                                </p>
                                <div class="testimonial-author">
                                    <img src="/client/assets/images/testimonial/testimonial-2.png" alt="Image">
                                    <div class="content">
                                        <h4>Lionel Messi</h4>
                                        <p>Argentina professional football player</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="col-xl-6 col-lg-7 col-12 mb-40">

                    <div class="row">
                        <div class="section-title text-start col mb-30">
                            <h1>FROM THE BLOG</h1>
                            <p>tin tức mới nhất được cập nhật tại đây</p>
                        </div>
                    </div>

                    <div class="row mbn-40">
                        @foreach ($posts as $post)
                            <div class="col-12 mb-40">
                                <div class="blog-item">
                                    <div class="image-wrap">
                                        <h4 class="date">
                                            {{ $post->published_month }}<span>{{ $post->published_day }}</span></h4>
                                        <a class="image" href="{{ route('post.show', $post) }}"><img
                                                src="/client/assets/images/blog/blog-1.jpg" alt="Image"></a>
                                    </div>
                                    <div class="content">
                                        <h4 class="title"><a
                                                href="{{ route('post.show', $post) }}">{{ $post->title }}</a>
                                        </h4>
                                        <div class="desc">
                                            <p>{{ $post->short_description }}</p>
                                        </div>
                                        <ul class="meta">
                                            <li>
                                                <a href="#"><img src="/client/assets/images/blog/blog-author-1.jpg"
                                                        alt="Blog Author">{{ $post->author }}</a>
                                            </li>
                                            <li><a href="#">25 Likes</a></li>
                                            <li><a href="#">05 Views</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach


                    </div>

                </div>

            </div>
        </div>
    </div>
    <!-- Blog Section End -->

    <style>
        /* START hero 1 + 2 */

        .hero-item {
            position: relative;
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-overlay {
            background: rgba(0, 0, 0, 0.4);
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-content {
            text-align: center;
            color: #fff;
            padding: 20px;
            backdrop-filter: blur(4px);
        }

        .hero-content h1 {
            font-size: 42px;
            font-weight: 700;
            line-height: 1.4;
            margin-bottom: 20px;
            text-shadow: 1px 2px 4px rgba(0, 0, 0, 0.6);
        }

        .cta-button {
            background-color: #007BFF;
            color: #fff;
            padding: 14px 32px;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .cta-button:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        /* END hero 1 + 2 *

                    /* START Banner 4+5+6  */

        /* Banner Styles Enhancement */

        .banner {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            z-index: 1;
        }

        .banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.45));
            z-index: 1;
        }

        .banner .image {
            position: relative;
            display: block;
        }

        .banner .image img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 8px;
        }

        .banner .content,
        .banner .content-inner {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 20px;
            color: #fff;
            z-index: 2;
        }

        .banner .content h1,
        .banner .content h2,
        .banner .content h4,
        .banner .content-inner h1,
        .banner .content-inner h2,
        .banner .content-inner h4 {
            color: #ffffff;
            text-shadow: 1px 2px 4px rgba(0, 0, 0, 0.6);
            margin: 5px 0;
            font-weight: 700;
        }

        /* Responsive font sizes */
        .banner .content h1,
        .banner .content-inner h1 {
            font-size: 26px;
        }

        .banner .content h2,
        .banner .content-inner h2 {
            font-size: 20px;
        }

        .banner .content h4,
        .banner .content-inner h4 {
            font-size: 16px;
        }

        @media (min-width: 768px) {

            .banner .content h1,
            .banner .content-inner h1 {
                font-size: 32px;
            }

            .banner .content h2,
            .banner .content-inner h2 {
                font-size: 24px;
            }

            .banner .content h4,
            .banner .content-inner h4 {
                font-size: 18px;
            }
        }

        /* END banner 4+5+6 */

        html {
            scroll-behavior: smooth;
        }
    </style>
@endsection
