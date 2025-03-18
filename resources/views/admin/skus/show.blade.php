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
                                    <label>Tên biến thể: </label>
                                    <span>{{ $skus->name }}</span>
                                </div>
                                <div class="form-group">
                                    <label>Giá: </label>
                                    <span>{{ number_format($skus->price, 0, '', ',') }} VND</span>
                                </div>
                                <div class="form-group">
                                    <label>Giá giảm: </label>
                                    <span>{{ number_format($skus->sale_price, 0, '', ',') }} VND</span>
                                </div>
                                <div class="form-group">
                                    <label>Barcode: </label>
                                    <span>{{ $skus->barcode }}</span>
                                </div>
                                <div class="form-group">
                                    <label>Các giá trị: </label>
                                    <ul>
                                        @foreach ($skus->variants as $variant)
                                            <li>{{$variant->product_atribute_values->value}}</li>
                                        @endforeach
                                    </ul>
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
                                    <label>Trạng thái: </label>
                                    @if ($skus->status)
                                        <span class=" badge bg-success">Active</span>
                                    @else
                                        <span class=" badge bg-danger">Deactive</span>
                                    @endif
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
