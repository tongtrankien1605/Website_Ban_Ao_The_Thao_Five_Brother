@extends('client.layouts.master')
@section('content')
    <!-- Page Banner Section Start -->
    <div class="page-banner-section section" style="background-image: url(/client//client/assets/images/hero/hero-1.jpg)">
        <div class="container">
            <div class="row">
                <div class="page-banner-content col">

                    <h1>Single Product</h1>
                    <ul class="page-breadcrumb">
                        <li><a href="index.html">Home</a></li>
                        <li><a href="single-product.html">Shop</a></li>
                    </ul>

                </div>
            </div>
        </div>
    </div><!-- Page Banner Section End -->

    <!-- Page Section Start -->
    <style>
        .color-btn {
            width: 32px;
            height: 32px;
            border: 2px solid transparent;
            transition: all 0.2s;
        }

        .btn-check:checked+.color-btn {
            border-color: #333;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        }
    </style>
    <div class="page-section section section-padding">
        <div class="container">
            <div class="row row-30 mbn-40">

                <div class="col-xl-9 col-lg-8 col-12 order-1 order-lg-2 mb-40">
                    <div class="row row-20">
                        <div class="col-lg-6 col-12 mb-40">
                            {{-- Ảnh chính --}}
                            <div class="pro-large-img mb-10 fix easyzoom easyzoom--overlay easyzoom--with-thumbnails">
                                <a href="{{ Storage::url($mainImage) }}" id="main-image-link">
                                    <img id="main-image" src="{{ Storage::url($mainImage) }}" alt="{{ $product->name }}">
                                </a>
                            </div>

                            {{-- Danh sách ảnh nhỏ --}}
                            <ul id="pro-thumb-img" class="pro-thumb-img d-flex">
                                @foreach ($productImages as $image)
                                    <li>
                                        <a href="{{ Storage::url($image->image_url) }}" class="thumb-link">
                                            <img src="{{ Storage::url($image->image_url) }}" alt="Product Image"
                                                class="thumb-img"
                                                style="height: 70px; width: 70px; margin-right: 5px; cursor: pointer;">
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="col-lg-6 col-12 mb-40">
                            <div class="single-product-content">
                                <div class="head">
                                    <div class="head-left">
                                        <h3 class="title">{{ $product->name }}</h3>
                                        <div class="ratting">
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        </div>
                                    </div>
                                    <div class="head-right">
                                        <span class="price">${{ number_format($product->price, 2) }}</span>
                                    </div>
                                </div>

                                <div class="description">
                                    <p>{{ $product->description }}</p>
                                </div>

                                <span class="availability">Availability: <span>In Stock</span></span>

                                @if ($product->attributeValues->isNotEmpty())
                                    @php
                                        $colorMap = [
                                            'Đỏ' => '#ff0000',
                                            'Xanh' => '#0000ff',
                                            'Đen' => '#000000',
                                            'Trắng' => '#ffffff',
                                            'Vàng' => '#ffff00',
                                            'Xanh lá' => '#008000',
                                            'Cam' => '#ffa500',
                                            'Hồng' => '#ffc0cb',
                                            'Tím' => '#800080',
                                        ];
                                    @endphp

                                    <div class="product-options mt-3">
                                        {{-- Số lượng --}}
                                        <div class="mb-3">
                                            <label for="quantity">Số lượng:</label>
                                            <input type="number" id="quantity" class="form-control w-50" value="1"
                                                min="1">
                                        </div>
                                        @foreach ($product->attributeValues->groupBy('attribute.name') as $attributeName => $values)
                                            @php
                                                $uniqueValues = $values->unique('value'); // Loại bỏ giá trị trùng lặp
                                            @endphp
                                            <div class="mb-3">
                                                <h5>{{ $attributeName }}:</h5>
                                                <div class="btn-group variant-selection" role="group"
                                                    data-attribute="{{ $attributeName }}">
                                                    @foreach ($uniqueValues as $value)
                                                        @php
                                                            $sku = $skus
                                                                ->where('product_attribute_value_id', $value->id)
                                                                ->first();
                                                            $variantImage = $sku ? Storage::url($sku->image) : null;
                                                        @endphp

                                                        <input type="radio" class="btn-check variant-option"
                                                            id="{{ Str::slug($attributeName) }}-{{ $value->id }}"
                                                            name="{{ Str::slug($attributeName) }}"
                                                            value="{{ $value->id }}" data-image="{{ $variantImage }}">

                                                        @if ($attributeName == 'Màu sắc')
                                                            @php $colorCode = $colorMap[$value->value] ?? '#cccccc'; @endphp
                                                            <label class="btn color-btn border border-secondary"
                                                                for="{{ Str::slug($attributeName) }}-{{ $value->id }}"
                                                                style="background-color: {{ $colorCode }}; width: 30px; height: 30px; border-radius: 50%;">
                                                            </label>
                                                        @else
                                                            <label class="btn btn-outline-dark"
                                                                for="{{ Str::slug($attributeName) }}-{{ $value->id }}">
                                                                {{ $value->value }}
                                                            </label>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                @endif

                                <div class="actions">
                                    <button><i class="ti-shopping-cart"></i><span>ADD TO CART</span></button>
                                    <button class="box" data-tooltip="Compare"><i
                                            class="ti-control-shuffle"></i></button>
                                    <button class="box" data-tooltip="Wishlist"><i class="ti-heart"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-50">
                        <!-- Nav tabs -->
                        <div class="col-12">
                            <ul class="pro-info-tab-list section nav">
                                <li><a class="active" href="#more-info" data-bs-toggle="tab">More info</a></li>
                                <li><a href="#data-sheet" data-bs-toggle="tab">Data sheet</a></li>
                                <li><a href="#reviews" data-bs-toggle="tab">Reviews</a></li>
                            </ul>
                        </div>
                        <!-- Tab panes -->
                        <div class="tab-content col-12">
                            <div class="pro-info-tab tab-pane active" id="more-info">
                                <p>{{ $product->description }}</p>
                            </div>
                            <div class="pro-info-tab tab-pane" id="data-sheet">
                                <table class="table-data-sheet">
                                    <tbody>
                                        <tr class="odd">
                                            <td>Compositions</td>
                                            <td>Cotton</td>
                                        </tr>
                                        <tr class="even">
                                            <td>Styles</td>
                                            <td>Casual</td>
                                        </tr>
                                        <tr class="odd">
                                            <td>Properties</td>
                                            <td>Short Sleeve</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="pro-info-tab tab-pane" id="reviews">
                                <a href="#">Be the first to write your review!</a>
                            </div>
                        </div>
                    </div>

                    <div class="section-title text-start mb-30">
                        <h1>Related Product</h1>
                    </div>

                    <div class="related-product-slider related-product-slider-2 slick-space p-0">

                        <div class="slick-slide">

                            <div class="product-item">
                                <div class="product-inner">

                                    <div class="image">
                                        <img src="/client/assets/images/product/product-1.jpg" alt="">

                                        <div class="image-overlay">
                                            <div class="action-buttons">
                                                <button>add to cart</button>
                                                <button>add to wishlist</button>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="content">

                                        <div class="content-left">

                                            <h4 class="title"><a href="single-product.html">Tmart Baby Dress</a></h4>

                                            <div class="ratting">
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star-half-o"></i>
                                                <i class="fa fa-star-o"></i>
                                            </div>

                                            <h5 class="size">Size:
                                                <span>S</span><span>M</span><span>L</span><span>XL</span>
                                            </h5>
                                            <h5 class="color">Color: <span style="background-color: #ffb2b0"></span><span
                                                    style="background-color: #0271bc"></span><span
                                                    style="background-color: #efc87c"></span><span
                                                    style="background-color: #00c183"></span></h5>

                                        </div>

                                        <div class="content-right">
                                            <span class="price">$25</span>
                                        </div>

                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="slick-slide">

                            <div class="product-item">
                                <div class="product-inner">

                                    <div class="image">
                                        <img src="/client/assets/images/product/product-2.jpg" alt="">

                                        <div class="image-overlay">
                                            <div class="action-buttons">
                                                <button>add to cart</button>
                                                <button>add to wishlist</button>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="content">

                                        <div class="content-left">

                                            <h4 class="title"><a href="single-product.html">Jumpsuit Outfits</a></h4>

                                            <div class="ratting">
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                            </div>

                                            <h5 class="size">Size:
                                                <span>S</span><span>M</span><span>L</span><span>XL</span>
                                            </h5>
                                            <h5 class="color">Color: <span style="background-color: #ffb2b0"></span><span
                                                    style="background-color: #0271bc"></span><span
                                                    style="background-color: #efc87c"></span><span
                                                    style="background-color: #00c183"></span></h5>

                                        </div>

                                        <div class="content-right">
                                            <span class="price">$09</span>
                                        </div>

                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="slick-slide">

                            <div class="product-item">
                                <div class="product-inner">

                                    <div class="image">
                                        <img src="/client/assets/images/product/product-3.jpg" alt="">

                                        <div class="image-overlay">
                                            <div class="action-buttons">
                                                <button>add to cart</button>
                                                <button>add to wishlist</button>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="content">

                                        <div class="content-left">

                                            <h4 class="title"><a href="single-product.html">Smart Shirt</a></h4>

                                            <div class="ratting">
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star-o"></i>
                                            </div>

                                            <h5 class="size">Size:
                                                <span>S</span><span>M</span><span>L</span><span>XL</span>
                                            </h5>
                                            <h5 class="color">Color: <span style="background-color: #ffb2b0"></span><span
                                                    style="background-color: #0271bc"></span><span
                                                    style="background-color: #efc87c"></span><span
                                                    style="background-color: #00c183"></span></h5>

                                        </div>

                                        <div class="content-right">
                                            <span class="price">$18</span>
                                        </div>

                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="slick-slide">

                            <div class="product-item">
                                <div class="product-inner">

                                    <div class="image">
                                        <img src="/client/assets/images/product/product-4.jpg" alt="">

                                        <div class="image-overlay">
                                            <div class="action-buttons">
                                                <button>add to cart</button>
                                                <button>add to wishlist</button>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="content">

                                        <div class="content-left">

                                            <h4 class="title"><a href="single-product.html">Kids Shoe</a></h4>

                                            <div class="ratting">
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star-half-o"></i>
                                                <i class="fa fa-star-o"></i>
                                            </div>

                                            <h5 class="size">Size:
                                                <span>S</span><span>M</span><span>L</span><span>XL</span>
                                            </h5>
                                            <h5 class="color">Color: <span style="background-color: #ffb2b0"></span><span
                                                    style="background-color: #0271bc"></span><span
                                                    style="background-color: #efc87c"></span><span
                                                    style="background-color: #00c183"></span></h5>

                                        </div>

                                        <div class="content-right">
                                            <span class="price">$15</span>
                                        </div>

                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="slick-slide">

                            <div class="product-item">
                                <div class="product-inner">

                                    <div class="image">
                                        <img src="/client/assets/images/product/product-5.jpg" alt="">

                                        <div class="image-overlay">
                                            <div class="action-buttons">
                                                <button>add to cart</button>
                                                <button>add to wishlist</button>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="content">

                                        <div class="content-left">

                                            <h4 class="title"><a href="single-product.html"> Bowknot Bodysuit</a></h4>

                                            <div class="ratting">
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star-half-o"></i>
                                            </div>

                                            <h5 class="size">Size:
                                                <span>S</span><span>M</span><span>L</span><span>XL</span>
                                            </h5>
                                            <h5 class="color">Color: <span style="background-color: #ffb2b0"></span><span
                                                    style="background-color: #0271bc"></span><span
                                                    style="background-color: #efc87c"></span><span
                                                    style="background-color: #00c183"></span></h5>

                                        </div>

                                        <div class="content-right">
                                            <span class="price">$12</span>
                                        </div>

                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-xl-3 col-lg-4 col-12 order-2 order-lg-1 mb-40">

                    <div class="sidebar">
                        <h4 class="sidebar-title">Category</h4>
                        <ul class="sidebar-list">
                            <li><a href="#">Shart <span class="num">18</span></a></li>
                            <li><a href="#">Pants <span class="num">09</span></a></li>
                            <li><a href="#">T-Shart <span class="num">05</span></a></li>
                            <li><a href="#">Tops <span class="num">03</span></a></li>
                            <li><a href="#">Kid's Clothes <span class="num">15</span></a></li>
                            <li><a href="#">Watch <span class="num">07</span></a></li>
                            <li><a href="#">Accessories <span class="num">02</span></a></li>
                        </ul>
                    </div>

                    <div class="sidebar">
                        <h4 class="sidebar-title">colors</h4>
                        <ul class="sidebar-list">
                            <li><a href="#"><span class="color" style="background-color: #000000"></span>
                                    Black</a></li>
                            <li><a href="#"><span class="color" style="background-color: #FF0000"></span> Red</a>
                            </li>
                            <li><a href="#"><span class="color" style="background-color: #0000FF"></span> Blue</a>
                            </li>
                            <li><a href="#"><span class="color" style="background-color: #28901D"></span>
                                    Green</a></li>
                            <li><a href="#"><span class="color" style="background-color: #FF6801"></span>
                                    Orange</a></li>
                        </ul>
                    </div>

                    <div class="sidebar">
                        <h4 class="sidebar-title">Popular Product</h4>
                        <div class="sidebar-product-wrap">
                            <div class="sidebar-product">
                                <a href="single-product.html" class="image"><img
                                        src="/client/assets/images/product/product-1.jpg" alt=""></a>
                                <div class="content">
                                    <a href="single-product.html" class="title">Tmart Baby Dress</a>
                                    <span class="price">$25 <span class="old">$38</span></span>
                                    <div class="ratting">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-half-o"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="sidebar-product">
                                <a href="single-product.html" class="image"><img
                                        src="/client/assets/images/product/product-2.jpg" alt=""></a>
                                <div class="content">
                                    <a href="single-product.html" class="title">Jumpsuit Outfits</a>
                                    <span class="price">$09 <span class="old">$21</span></span>
                                    <div class="ratting">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-half-o"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="sidebar-product">
                                <a href="single-product.html" class="image"><img
                                        src="/client/assets/images/product/product-3.jpg" alt=""></a>
                                <div class="content">
                                    <a href="single-product.html" class="title">Smart Shirt</a>
                                    <span class="price">$18 <span class="old">$29</span></span>
                                    <div class="ratting">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-half-o"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sidebar">
                        <h3 class="sidebar-title">Price</h3>

                        <div class="sidebar-price">
                            <div id="price-range"></div>
                            <input type="text" id="price-amount" readonly>
                        </div>
                    </div>

                    <div class="sidebar">
                        <h3 class="sidebar-title">Tags</h3>
                        <ul class="sidebar-tag">
                            <li><a href="#">New</a></li>
                            <li><a href="#">brand</a></li>
                            <li><a href="#">black</a></li>
                            <li><a href="#">white</a></li>
                            <li><a href="#">chire</a></li>
                            <li><a href="#">table</a></li>
                            <li><a href="#">Lorem</a></li>
                            <li><a href="#">ipsum</a></li>
                            <li><a href="#">dolor</a></li>
                            <li><a href="#">sit</a></li>
                            <li><a href="#">amet</a></li>
                        </ul>
                    </div>

                </div>

            </div>
        </div>
    </div><!-- Page Section End -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const mainImage = document.getElementById("main-image");
            const mainImageLink = document.getElementById("main-image-link");
            const variantOptions = document.querySelectorAll(".variant-option");
            const thumbnails = document.querySelectorAll(".thumb-link");

            let defaultMainImage = mainImage.src; // Lưu ảnh mặc định
            let selectedThumbnail = null; // Ảnh nhỏ được chọn gần nhất
            let selectedVariants = {}; // Lưu trạng thái chọn của từng attribute

            // ✅ Xử lý khi bấm vào ảnh nhỏ (thumbnail)
            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener("click", function(event) {
                    event.preventDefault();
                    const newImageSrc = this.href;
                    if (newImageSrc) {
                        mainImage.src = newImageSrc;
                        mainImageLink.href = newImageSrc;
                        selectedThumbnail = newImageSrc; // Lưu ảnh nhỏ đã chọn
                    }
                });
            });

            // ✅ Xử lý chọn/bỏ chọn biến thể
            variantOptions.forEach(option => {
                option.addEventListener("click", function(event) {
                    event.preventDefault();

                    let scrollY = window.scrollY; // Lưu vị trí cuộn trước khi thay đổi ảnh
                    const attributeGroup = option.name; // Lấy nhóm thuộc tính (size, color,...)
                    const isSelected = selectedVariants[attributeGroup] === option.value;

                    // Nếu đã chọn trước đó, thì bỏ chọn
                    if (isSelected) {
                        option.checked = false;
                        selectedVariants[attributeGroup] = null;

                        // Xóa viền chọn
                        option.nextElementSibling.classList.remove("border-dark", "btn-dark",
                            "text-white");
                        option.nextElementSibling.classList.add("btn-outline-dark");

                        // Nếu bỏ chọn màu nhưng có ảnh nhỏ đã chọn trước đó → Giữ ảnh nhỏ
                        if (attributeGroup === "color" && selectedThumbnail) {
                            mainImage.src = selectedThumbnail;
                            mainImageLink.href = selectedThumbnail;
                        } else {
                            mainImage.src = defaultMainImage;
                            mainImageLink.href = defaultMainImage;
                        }
                    } else {
                        // Nếu chưa chọn trước đó, thì chọn mới
                        selectedVariants[attributeGroup] = option.value;

                        // Xóa class active cho tất cả các lựa chọn trong cùng nhóm attribute
                        document.querySelectorAll(`[name="${attributeGroup}"]`).forEach(other => {
                            other.nextElementSibling.classList.remove("border-dark",
                                "btn-dark", "text-white");
                            other.nextElementSibling.classList.add("btn-outline-dark");
                        });

                        // Đánh dấu nút được chọn
                        if (option.nextElementSibling.classList.contains("color-btn")) {
                            option.nextElementSibling.classList.add("border-dark");
                        } else {
                            option.nextElementSibling.classList.add("btn-dark", "text-white");
                            option.nextElementSibling.classList.remove("btn-outline-dark");
                        }

                        // Kiểm tra ảnh biến thể
                        let selectedImage = option.dataset.image;
                        if (selectedImage && selectedImage !== "null") {
                            mainImage.src = selectedImage;
                            mainImageLink.href = selectedImage;
                            selectedThumbnail =
                            null; // Nếu chọn biến thể có ảnh, bỏ ảnh nhỏ đã chọn trước đó
                        } else {
                            // Nếu không có ảnh biến thể, giữ ảnh nhỏ nếu đã chọn
                            mainImage.src = selectedThumbnail ? selectedThumbnail :
                            defaultMainImage;
                            mainImageLink.href = selectedThumbnail ? selectedThumbnail :
                                defaultMainImage;
                        }
                    }

                    // Giữ nguyên vị trí cuộn
                    window.scrollTo(0, scrollY);
                });
            });
        });
    </script>

@endsection
