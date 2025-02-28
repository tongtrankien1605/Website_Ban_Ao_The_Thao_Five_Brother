@extends('admin.layouts.index')

@section('title')
    Chi tiết sản phẩm
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Chi tiết sản phẩm</h3>
                            </div>

                            <div class="card-body">
                                <div class="form-group">
                                    <label>Tên sản phẩm</label>
                                    <p>{{ $product->name }}</p>
                                </div>
                                <div class="form-group">
                                    <label>brand</label>
                                    <p>{{ $brand->name }}</p>
                                </div>
                                <div class="form-group">
                                    <label>danh mục</label>
                                    <p>{{ $category->name }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Mô tả</label>
                                    <p>
                                        {{ $product->description }}
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label>Ảnh sản phẩm:</label>
                                    <div>
                                        @if ($productImages)
                                            @foreach ($productImages as $productImage)
                                                <img src="{{ Storage::url($productImage->image_url) }}" width="200px" style="margin-right: 10px"
                                                    alt="">
                                            @endforeach
                                        @else
                                            <p>Chưa có ảnh đại diện</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-center">
                                    <a href="{{ route('admin.product.index') }}" class="btn btn-danger">Quay lại</a>
                                    <a href="{{ route('admin.product.product_attribute.index', $product->id) }}"
                                        class="btn btn-success">
                                        Danh sách biến thể sản phẩm</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
