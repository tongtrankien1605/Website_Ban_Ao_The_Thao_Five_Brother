@extends('admin.layouts.index')
@section('title')
    Thêm mới Thương hiệu
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
                                <h3 class="card-title">Thêm mới Thương hiệu</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form method="post" enctype="multipart/form-data" action="{{ route('admin.brands.store') }}">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Tên thương hiệu</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Nhập tên thương hiệu" value="{{ old('name') }}">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- <div class="form-group">
                                        <label for="description">Mô tả</label>
                                        <textarea class="form-control" id="description" name="description" placeholder="Nhập mô tả">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div> --}}

                                    {{-- <div class="form-group">
                                        <label for="logo">Logo</label>
                                        <div class="input-group">
                                            <input type="file" id="logo" name="logo" value="{{ old('logo') }}">
                                        </div>
                                        @error('logo')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div> --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">Trạng thái</label>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="status"
                                                    name="status" value="1" {{ old('status') ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="status">Hoạt động</label>
                                            </div>
                                            @error('status')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <a href="{{ route('admin.brands.index') }}" class="btn btn-danger">Quay lại</a>
                                        <button type="submit" class="btn btn-primary">Thêm mới</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </div>
@endsection
