@extends('admin.layouts.index')

@section('title')
    Danh sách sản phẩm
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Danh sách sản phẩm</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                            <li class="breadcrumb-item active"><a href="{{ route('admin.product.index') }}">Danh sách sản
                                    phẩm</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <div class="col-12">
            <div class="card" style="height: 700px; width:1250px">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                    <a href="{{ route('admin.product.create') }}" class="btn btn-primary">Thêm mới sản phẩm</a>
                    <div class="card-tools">
                        <form action="{{ route('admin.posts.index') }}" method="GET" class="input-group input-group-sm"
                            style="width: 150px;">
                            <input type="text" name="search" class="form-control float-right" placeholder="Search"
                                value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1" aria-sort="ascending"
                                    aria-label="Rendering engine: activate to sort column descending">
                                    Tên sản phẩm
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="Browser: activate to sort column ascending">Brand
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="Platform(s): activate to sort column ascending">
                                    Category</th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="Engine version: activate to sort column ascending">
                                    Image
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                    aria-label="CSS grade: activate to sort column ascending">Hành
                                    động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td class="dtr-control sorting_1" tabindex="0">
                                        {{ $product->name }}
                                    </td>
                                    <td>{{ $product->product_brand }}</td>
                                    <td>{{ $product->product_category }}</td>
                                    <td>
                                        <img src="{{ Storage::url($product->product_image) }}" width="100px"
                                            alt="">
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-warning">
                                            <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24"
                                                stroke-linecap="round" stroke-linejoin="round" height="1em" width="1em"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.product.destroy', $product->id) }}" method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"><i
                                                    class="bi bi-trash"></i></button>
                                        </form>
                                        <a href="{{ route('admin.product.show', $product->id) }}" class="btn btn-info"><i
                                                class="bi bi-eye"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                {{ $products->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
    @extends('admin.table.js')
@endsection
