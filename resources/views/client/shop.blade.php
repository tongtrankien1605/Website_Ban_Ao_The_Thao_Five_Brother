@extends('client.layouts.master')
@section('content')
    <!-- Page Banner Section Start -->
    <div class="page-banner-section section" style="background-image: url(/client/assets/images/hero/hero-1.jpg)">
        <div class="container">
            <div class="row">
                <div class="page-banner-content col">

                    <h1>Sản phẩm</h1>
                    <ul class="page-breadcrumb">
                        <li><a href="{{ route('index') }}">Trang chủ</a></li>
                        <li><a href="{{ route('product.index') }}">Sản phẩm</a></li>
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
                        <div class="product-short">
                            <h4>Sắp xếp theo:</h4>
                            <select class="nice-select" onchange="window.location.href=this.value">
                                <option value="{{ route('product.index', ['sort' => 'name_asc'] + request()->except(['page', 'sort'])) }}"
                                    {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                                <option value="{{ route('product.index', ['sort' => 'name_desc'] + request()->except(['page', 'sort'])) }}"
                                    {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                                <option value="{{ route('product.index', ['sort' => 'price_asc'] + request()->except(['page', 'sort'])) }}"
                                    {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                                <option value="{{ route('product.index', ['sort' => 'price_desc'] + request()->except(['page', 'sort'])) }}"
                                    {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                                <option value="{{ route('product.index', ['sort' => 'date_desc'] + request()->except(['page', 'sort'])) }}"
                                    {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="{{ route('product.index', ['sort' => 'date_asc'] + request()->except(['page', 'sort'])) }}"
                                    {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Cũ nhất</option>
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
                                                {{-- ><a href="{{route('product.show',$product->id)}}">Add to cart</a></button> --}}
                                                ><a href="{{route('product.show',$product->id)}}">Thêm vào giỏ hàng</a></button>
                                            <button>Thêm vào yêu thích</button>
                                            {{-- <button>add to wishlist</button> --}}
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

                                        <h5><br></h5>
                                        <h5><br></h5>

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
                    </ul>
                </div>

            </div>

        </div>
    </div><!-- Page Section End -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: "{{ session('success') }}",
                confirmButtonText: 'Đóng'
            });
        @elseif (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: "{{ session('error') }}",
                confirmButtonText: 'Đóng'
            });
        @endif
    </script>
@endsection
