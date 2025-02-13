@extends('admin.layouts.index')
@section('title')
    Thêm mới sản phẩm
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
                                <h3 class="card-title">Thêm mới sản phẩm</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form method="post" enctype="multipart/form-data" action="{{ route('admin.product.store') }}">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Tên sản phẩm</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Enter name" value="{{ old('name') }}">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="brand">Brand</label>
                                        <select class="form-control" id="brand" name="brand">
                                            <option value="" selected>-- select --</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('brand')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="category">Category</label>
                                        <select class="form-control" id="category" name="category">
                                            <option value="" selected>-- select --</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="price">Price</label>
                                        <input type="text" class="form-control" id="price" name="price"
                                            placeholder="Enter price" value="{{ old('price') }}">
                                        @error('price')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="image">Image</label>
                                        <div class="input-group">
                                            <input type="file" class="" id="image" name="image"
                                                value="{{ old('image') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea name="description" id="summernote" class="form-control" rows="5" placeholder="Nhập mô tả sản phẩm"
                                            required></textarea>
                                    </div>
                                    <div class="text-center">
                                        <a href="{{ route('admin.product.index') }}" class="btn btn-danger">Quay
                                            lại</a>
                                        <button type="submit" class="btn btn-primary">Thêm mới</button>
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
