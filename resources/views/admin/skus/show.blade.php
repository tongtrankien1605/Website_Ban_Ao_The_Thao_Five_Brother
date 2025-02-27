@extends('admin.layouts.index')

@section('title')
    Chi tiết biến thể
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Chi tiết biến thể</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                            <li class="breadcrumb-item active"><a
                                    href="{{ route('admin.product.skus.show', ['product' => $product, 'sku' => $skus->id]) }}">Chi
                                    tiết biến thể</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Tên biến thể</label>
                                    <p>{{ $skus->name }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Giá</label>
                                    <p>{{ $skus->price }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Giá giảm</label>
                                    <p>{{ $skus->sale_price }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Barcode</label>
                                    <p>
                                        {{ $skus->barcode }}
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label>Ảnh biến thể:</label>
                                    <div>
                                        @if ($skus->image)
                                            <img src="{{ Storage::url($skus->image) }}" width="200px"
                                                style="margin-right: 10px" alt="">
                                        @else
                                            <p>Chưa có ảnh</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Trạng thái</label>
                                    <div>
                                        @if ($skus->status)
                                            <p>Active</p>
                                        @else
                                            <p>Deactive</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-center">
                                    <a href="{{ route('admin.product.show', $product) }}" class="btn btn-danger">Quay lại</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
