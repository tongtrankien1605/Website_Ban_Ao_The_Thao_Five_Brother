@extends('admin.layouts.index')
@section('content')
    <section class="content">
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if (session('message'))
                                <div class="alert alert-success">
                                    {{ session('message') }}
                                </div>
                            @endif

                            <h1>Thêm mới danh mục sản phẩm</h1>
                            {{-- <a href="{{ Route('admin.category.create') }}" class="btn btn-primary mb-3">Add</a> --}}
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                                <li class="breadcrumb-item active"><a href="{{ Route('admin.category.index') }}">Trang danh sách</a></li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content-header -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Thêm mới danh mục</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="POST" action="{{ Route('admin.category.store') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="exampleInputName">Tên</label>
                                            <input type="text" name="name" class="form-control"
                                                id="exampleInputName" placeholder="Nhập tên"
                                                value="{{ old('name') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputDescription">Mô tả</label>
                                            <input type="text" name="description" class="form-control"
                                                id="exampleInputDescription" placeholder="Mô tả"
                                                value="{{ old('description') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputFile">Ảnh</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="image" class="form-control"
                                                        id="exampleInputFile">
                                                    {{-- <label class="custom-file-label" for="exampleInputFile">Choose file</label> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    @endsection
