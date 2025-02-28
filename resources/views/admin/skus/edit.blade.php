@extends('admin.layouts.index')
@extends('admin.products.css')
@section('title')
    Chỉnh sửa biến thể
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Chỉnh sửa biến thể</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <form action="{{ route('admin.product.skus.update', ['product' => $product, 'sku' => $skus->id]) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" id="name"
                                value="{{ old('name', $skus->name) }}">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="barcode">Barcode</label>
                            <input type="text" name="barcode" class="form-control" id="barcode"
                                value="{{ old('barcode', $skus->barcode) }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="text" name="price" class="form-control" id="price"
                                value="{{ old('price', $skus->price) }}">
                            @error('price')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="sale_price">Sale price</label>
                            <input type="text" name="sale_price" class="form-control" id="sale_price"
                                value="{{ old('sale_price', $skus->sale_price) }}">
                            @error('sale_price')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="image">Image</label>
                            <div class="input-group">
                                <div class="input-group">
                                    @if ($skus->image)
                                        <img src="{{ Storage::url($skus->image) }}" alt="" width="100px">
                                    @endif
                                </div>
                                <input type="file" class="form-control" id="pwd" id="image" name="image">
                                @error('image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="text-center">
                        <a href="{{ route('admin.product.show', $product) }}" class="btn btn-danger">Quay lại</a>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
<style>
    .content-wrapper {
        min-height: fit-content !important;
    }

    .card-header::after {
        content: none !important;
    }
</style>
