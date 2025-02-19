@extends('admin.layouts.index')
@section('title')
    Chỉnh sửa sản phẩm
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- /.content-header -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Chỉnh sửa sản phẩm</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form method="post" enctype="multipart/form-data"
                            action="{{ route('admin.product.product_attribute.update', ['product' => $product, 'product_attribute' => $skus->id]) }}">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <input type="hidden" name="product_id"  value="{{$product}}">
                                    <div class="form-group">
                                        <label for="name">Tên sản phẩm</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Enter name" value="{{ $skus->name }}">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="quantity">quantity</label>
                                        <input type="number" class="form-control" id="name" name="quantity"
                                            placeholder="Enter quantity" value="{{ $skus->quantity }}">
                                        @error('quantity')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="barcode">barcode</label>
                                        <input type="number" class="form-control" id="name" name="barcode"
                                            placeholder="Enter barcode" value="{{ $skus->barcode }}">
                                        @error('barcode')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="price">Price</label>
                                        <input type="text" class="form-control" id="price" name="price"
                                            placeholder="Enter price" value="{{ $skus->price }}">
                                        @error('price')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="text-center">
                                        <a href="{{ route('admin.product.index') }}" class="btn btn-danger">Quay
                                            lại</a>
                                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
