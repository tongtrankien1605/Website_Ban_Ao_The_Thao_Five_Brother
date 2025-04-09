@extends('client.layouts.master')
@section('content')
    <!-- Page Banner Section Start -->
    <div class="page-banner-section section" style="background-image: url(/client//client/assets/images/hero/hero-1.jpg)">
        <div class="container">
            <div class="row">
                <div class="page-banner-content col">

                    <h1>Single Product</h1>
                    <ul class="page-breadcrumb">
                        <li><a href="{{ route('index') }}">Home</a></li>
                        <li><a href="{{ route('product.index') }}">Shop</a></li>
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

                            <div class="pro-large-img mb-10 fix easyzoom easyzoom--overlay easyzoom--with-thumbnails">
                                <a href="{{ Storage::url($product->image) }}">
                                    <img src="{{ Storage::url($product->image) }}" alt="" />
                                </a>
                            </div>
                            <!-- Single Product Thumbnail Slider -->
                            <ul id="pro-thumb-img" class="pro-thumb-img">
                                @foreach ($productImages as $productImage)
                                    <li>
                                        <a href="{{ Storage::url($productImage->image_url) }}"
                                            data-standard="{{ Storage::url($productImage->image_url) }}">
                                            <img src="{{ Storage::url($productImage->image_url) }}" alt="" />
                                        </a>
                                    </li>
                                @endforeach
                                {{-- <li><a href="/client/assets/images/product/product-zoom-1.jpg" data-standard="/client/assets/images/product/product-big-1.jpg"><img src="/client/assets/images/product/product-1.jpg" alt="" /></a></li>
                                <li><a href="/client/assets/images/product/product-zoom-2.jpg" data-standard="/client/assets/images/product/product-big-2.jpg"><img src="/client/assets/images/product/product-2.jpg" alt="" /></a></li>
                                <li><a href="/client/assets/images/product/product-zoom-3.jpg" data-standard="/client/assets/images/product/product-big-3.jpg"><img src="/client/assets/images/product/product-3.jpg" alt="" /></a></li>
                                <li><a href="/client/assets/images/product/product-zoom-4.jpg" data-standard="/client/assets/images/product/product-big-4.jpg"><img src="/client/assets/images/product/product-4.jpg" alt="" /></a></li>
                                <li><a href="/client/assets/images/product/product-zoom-5.jpg" data-standard="/client/assets/images/product/product-big-5.jpg"><img src="/client/assets/images/product/product-5.jpg" alt="" /></a></li> --}}
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
                                    {{-- <div class="head-right">
                                        <span class="price">${{ number_format($product->price, 2) }}</span>
                                    </div> --}}
                                </div>

                                <div class="description">
                                    <p>{!! $product->description !!}</p>
                                </div>

                                <span class="availability">Availability: <span id="availabilityQty">--</span></span>


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
                                            <label for="">Số lượng:</label>
                                            <input type="number" id="quantity" class="form-control w-50" value="1"
                                                min="1">
                                        </div>
                                        @foreach ($product->attributeValues->groupBy('attribute.name') as $attributeName => $values)
                                        @php
                                            $uniqueValues = $values->unique('value');
                                        @endphp
                                        <div class="mb-3">
                                            <h5>{{ $attributeName }}:</h5>
                                                <div class="btn-group variant-selection" role="group"
                                                    data-attribute="{{ $attributeName }}">
                                                @foreach ($uniqueValues as $value)
                                                    @php
                                                            // dd($value->toArray());
                                                            $sku = $skus
                                                                ->where('product_attribute_value_id', $value->id)
                                                                ->first();
                                                        $variantImage = $sku ? Storage::url($sku->image) : null;
                                                    @endphp
                                    
                                                    <input type="radio" class="btn-check variant-option"
                                                        id="variant-{{ $value->id }}"
                                                        name="variant[{{ Str::slug($attributeName) }}]"
                                                        value="{{ $value->id }}" data-image="{{ $variantImage }}">
                                    
                                                    @if ($attributeName == 'Màu sắc')
                                                        @php $colorCode = $colorMap[$value->value] ?? '#cccccc'; @endphp
                                                        <label class="btn color-btn border border-secondary"
                                                            for="variant-{{ $value->id }}"
                                                            style="background-color: {{ $colorCode }}; width: 30px; height: 30px; border-radius: 50%; display: inline-block;">
                                                        </label>
                                                    @else
                                                            <label class="btn btn-outline-dark"
                                                                for="variant-{{ $value->id }}">
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
                                    <button id="addToCartBtn" class="add_to_cart"
                                        data-url="{{ route('add.cart', ['id' => $product->id]) }}">
                                        <i class="ti-shopping-cart"></i><span>ADD TO CART</span>
                                    </button>
                                    <button class="box" data-tooltip="Compare"><i
                                    class="ti-control-shuffle"></i></button>
                                    @isset($wishlist)
                                        <button class="box pro-remove"><a
                                                href="{{ route('delete_wishlist', $product->id) }}"><i
                                                    class="ti-heart"></i></button>
                                        @else
                                        <button class="box add_to_wishlist"
                                            data-url="{{ route('add_wishlist', ['id' => $product->id]) }} "
                                            data-tooltip="Add to Wishlist"><i class="ti-heart"></i></button>
                                    @endisset
                                        
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-50">
                        <!-- Nav tabs -->
                        <div class="col-12">
                            <ul class="pro-info-tab-list section nav">
                                <li><a class="active" href="#more-info" data-bs-toggle="tab">More info</a></li>
                                {{-- <li><a href="#data-sheet" data-bs-toggle="tab">Data sheet</a></li> --}}
                                <li><a href="#reviews" data-bs-toggle="tab">Reviews</a></li>
                            </ul>
                        </div>
                        <!-- Tab panes -->
                        <div class="tab-content col-12">
                            <div class="pro-info-tab tab-pane active" id="more-info">
                                <p>{!! $product->description !!}</p>
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


                        @foreach ($relatedProducts as $related)
                        <div class="slick-slide">

                            <div class="product-item">
                                <div class="product-inner">

                                    <div class="image">
                                            <div
                                                class="bg-light border rounded d-flex justify-content-center align-items-center">
                                                <img src="{{ Storage::url($related->image) }}" alt=""
                                                    style="height: 300px;width: 300px; overflow: hidden;">
                                            </div>

                                        <div class="image-overlay">
                                            <div class="action-buttons">
                                                    <button><a href="{{ route('product.show', $related->id) }}">Add to
                                                            cart</a></button>
                                                    <button class="add_to_wishlist"
                                                        data-url="{{ route('add_wishlist', ['id' => $related->id]) }}">Add
                                                        to
                                                        wishlist</button>
                                                </div>
                                            </div>

                                    </div>

                                    <div class="content">

                                        <div class="content-left">

                                                <h4 class="title"><a
                                                        href="{{ route('product.show', $product) }}">{{ $related->name }}</a>
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
                        {{-- <div class="slick-slide">

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

                        </div> --}}

                    </div>

                </div>

                <div class="col-xl-3 col-lg-4 col-12 order-2 order-lg-1 mb-40">

                    <div class="sidebar">
                        <h4 class="sidebar-title">Brand</h4>
                        <ul class="sidebar-list">
                            @foreach ($brands as $brand)
                                <li><a href="{{ route('brands.show', $brand->id) }}">{{ $brand->name }} <span
                                            class="num">{{ $brand->products_count }}</span></a></li>
                            @endforeach
                        </ul>

                    </div>

                    {{-- <div class="sidebar">
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
                    </div> --}}

                    <div class="sidebar">
                        <h4 class="sidebar-title">Popular Product</h4>
                        <div class="sidebar-product-wrap">
                            @foreach ($popularProducts as $item)
                            <div class="sidebar-product">
                                    <a href="{{ route('product.show', $item->id) }}" class="image"><img
                                            src="{{ Storage::url($item->image) }}" alt=""></a>
                                <div class="content">
                                        <a href="{{ route('product.show', $item->id) }}"
                                            class="title">{{ $item->name }}</a>
                                    <div class="ratting">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-half-o"></i>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            {{-- <div class="sidebar-product">
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
                            </div> --}}
                        </div>
                    </div>

                    {{-- <div class="sidebar">
                        <h3 class="sidebar-title">Price</h3>

                        <div class="sidebar-price">
                            <div id="price-range"></div>
                            <input type="text" id="price-amount" readonly>
                        </div>
                    </div> --}}

                    {{-- <div class="sidebar">
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
                    </div> --}}

                </div>

            </div>
        </div>
    </div><!-- Page Section End -->
    <script>
        window.inventoryData = @json($inventoryData);
        window.variantMap = @json($variantMap);

        console.log(window.inventoryData);
        console.log(window.variantMap);

        document.querySelectorAll('.variant-option').forEach(input => {
            input.addEventListener('change', function() {
                const selectedVariants = [];

                // Lấy ID của các variant đang được chọn
                document.querySelectorAll('.variant-option:checked').forEach(checked => {
                    // console.log(checked.value);
                    selectedVariants.push(checked.value);
                });

                const sortedKey = selectedVariants.map(Number).sort((a, b) => a - b).join(',');

                const skuId = window.variantMap[sortedKey];
                const inventoryObject = {};
                window.inventoryData.forEach(item => {
                    inventoryObject[item.id] = item.quantity;
                });

                const qty = skuId ? (inventoryObject[skuId] || 0) : 0;

                const availabilitySpan = document.getElementById('availabilityQty');
                if (availabilitySpan) {
                    availabilitySpan.textContent = skuId ?
                        (qty > 0 ? `${qty} sản phẩm còn hàng` : 'Hết hàng') :
                        'Chưa chọn đủ biến thể';
                    availabilitySpan.style.color = qty > 0 ? 'green' : 'red';
                }

                const addToCartBtn = document.getElementById('addToCartBtn');

                if (addToCartBtn) {
                    if (qty > 0) {
                        addToCartBtn.disabled = false;
                        addToCartBtn.querySelector('span').textContent = 'ADD TO CART';
                        addToCartBtn.classList.remove('disabled');
                    } else {
                        addToCartBtn.disabled = true;
                        addToCartBtn.querySelector('span').textContent = 'Hết hàng';
                        addToCartBtn.classList.add('disabled');
                    }
                }
            });
        });
    </script>

    <style>
        .add_to_cart.disabled {
            opacity: 0.6;
            cursor: not-allowed;
            pointer-events: none;
        }
    </style>

@endsection
