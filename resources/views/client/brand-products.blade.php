@extends('client.layouts.master')
@section('content')
    <!-- Page Banner Section Start -->
    <div class="page-banner-section section" style="background-image: url(/client/assets/images/hero/hero-1.jpg)">
        <div class="container">
            <div class="row">
                <div class="page-banner-content col">

                    <h1>Shop</h1>
                    <ul class="page-breadcrumb">
                        <li><a href="{{ route('index') }}">Home</a></li>
                        <li><a href="{{ route('product.index') }}">Shop</a></li>
                    </ul>

                </div>
            </div>
        </div>
    </div><!-- Page Banner Section End -->

    <!-- Page Section Start -->
    <div class="page-section section section-padding">
        <div class="container">

            <div class="row">

                <div class="col-12">
                    <div class="product-show">
                        <h4>Show:</h4>
                        <select class="nice-select">
                            <option>8</option>
                            <option>12</option>
                            <option>16</option>
                            <option>20</option>
                        </select>
                    </div>
                    <div class="product-short">
                        <h4>Short by:</h4>
                        <select class="nice-select">
                            <option>Name Ascending</option>
                            <option>Name Descending</option>
                            <option>Date Ascending</option>
                            <option>Date Descending</option>
                            <option>Price Ascending</option>
                            <option>Price Descending</option>
                        </select>
                    </div>
                </div>
                @foreach ($products as $product)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-40">

                        <div class="product-item">
                            <div class="product-inner">
                                <div class="image">
                                    <div class="bg-light border rounded d-flex justify-content-center align-items-center">
                                        <img id="main-image" src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                            style="height: 300px; width: 300px;">
                                    </div>
                                    <div class="image-overlay">
                                        <div class="action-buttons">
                                            <button
                                                ><a href="{{route('product.show',$product->id)}}">Add to cart</a></button>
                                            <button>add to wishlist</button>
                                        </div>
                                    </div>

                                </div>

                                <div class="content">

                                    <div class="content-left">

                                        <h4 class="title"><a
                                                href="{{ route('product.show', $product) }}">{{ $product->name }}</a></h4>

                                        <div class="ratting">
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        </div>

                                        <h5 class="size">Size: <span>S</span><span>M</span><span>L</span><span>XL</span>
                                        </h5>
                                        <h5 class="color">Color: <span style="background-color: #ffb2b0"></span><span
                                                style="background-color: #0271bc"></span><span
                                                style="background-color: #efc87c"></span><span
                                                style="background-color: #00c183"></span></h5>

                                    </div>

                                    <div class="content-right">
                                        <span class="price">{{ $product->price }}</span>
                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>
                @endforeach
                <div class="col-12">
                    <ul class="page-pagination">
                        {{ $products->links() }}
                        {{-- <li><a href="#"><i class="fa fa-angle-left"></i></a></li>
                        <li class="active"><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#"><i class="fa fa-angle-right"></i></a></li> --}}
                    </ul>
                </div>

            </div>

        </div>
    </div><!-- Page Section End -->
@endsection
