@extends('admin.layouts.index')

@section('title')
    Chi tiết người dùng
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Chi tiết người dùng</h3>
                            </div>

                            <div class="card-body">
                                <div class="form-group">
                                    <label>Tên</label>
                                    <p>{{ $product->name }}</p>
                                </div>
                                <div class="form-group">
                                    <label>hãng</label>
                                    <p>{{ $product->id_brand }}</p>
                                </div>
                                <div class="form-group">
                                    <label>danh mục</label>
                                    <p>{{ $product->id_category }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Mô tả</label>
                                    <p>
                                        {{ $product->description }}
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label>giá</label>
                                    <p>{{ $product->price }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Image:</label>
                                    <div>
                                        @if ($product->image)
                                            <img src="{{ Storage::url($product->image) }}" alt="" width="100px">
                                        @else
                                            <p>Chưa có ảnh đại diện</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-center">
                                    <a href="{{ route('admin.product.index') }}" class="btn btn-danger">Quay lại</a>
                                    <a href="{{ route('admin.product.product_attribute.index',$product->id) }}" class="btn btn-success">
                                        danh sách biến thể sản phẩm</a>
                                    <a href="{{ route('admin.product.product_attribute.create',$product->id) }}" class="btn btn-primary">
                                        Thêm biến thể sản phẩm</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
