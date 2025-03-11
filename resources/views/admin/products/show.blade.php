@extends('admin.layouts.index')

@section('title')
    Chi tiết sản phẩm
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Chi tiết sản phẩm</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                            <li class="breadcrumb-item active"><a href="{{ route('admin.product.show', $product) }}">Chi
                                    tiết sản phẩm</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
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
                                <div>
                                    <label for="">Ảnh đại diện</label>
                                    <div>
                                        <img src="{{ Storage::url($product->image) }}" width="200px" alt="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Ảnh sản phẩm:</label>
                                    <div>
                                        @if ($productImages)
                                            @foreach ($productImages as $productImage)
                                                <img src="{{ Storage::url($productImage->image_url) }}" width="70px"
                                                    style="margin-right: 10px" alt="">
                                            @endforeach
                                        @else
                                            <p>Chưa có ảnh</p>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <label for="">Trạng thái</label>
                                    <div>
                                        @if ($product->status)
                                            <p>Active</p>
                                        @else
                                            <p>Deactive</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-center">
                                    <a href="{{ route('admin.product.index') }}" class="btn btn-danger">Quay lại</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Biến thể của sản phẩm</h1>
                    </div>
                </div>
            </div>
        </section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="example1"
                                            class="table table-bordered table-striped dataTable dtr-inline"
                                            aria-describedby="example1_info">
                                            <thead>
                                                <tr>
                                                    <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                                        rowspan="1" colspan="1" aria-sort="ascending"
                                                        aria-label="Rendering engine: activate to sort column descending">
                                                        Tên biến thể
                                                    </th>
                                                    <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                                        rowspan="1" colspan="1" aria-sort="ascending"
                                                        aria-label="Rendering engine: activate to sort column descending">
                                                        Ảnh biến thể
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="example1"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Engine version: activate to sort column ascending">
                                                        Trạng thái
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="example1"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Engine version: activate to sort column ascending">
                                                        Ngày tạo
                                                    </th>
                                                    <th class="sorting text-nowrap" tabindex="0" aria-controls="example1"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Engine version: activate to sort column ascending">
                                                        Hành động
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($skuses as $skus)
                                                    <tr>
                                                        <td class="dtr-control sorting_1" tabindex="0"
                                                            style="width:300px">
                                                            {{ $skus->name }}
                                                        </td>
                                                        <td style="width:100px" class=" text-center">
                                                            <img src="{{ Storage::url($skus->image) }}" width="150px"
                                                                style="margin-right: 10px" alt="">
                                                        </td>
                                                        <td class=" text-center" style="width:100px">
                                                            @if ($skus->status)
                                                                <span class="badge bg-success">active</span>
                                                            @else
                                                                <span class="badge bg-danger">deactive</span>
                                                            @endif
                                                        </td>
                                                        <td style="width:150px">{{ $skus->created_at }}</td>
                                                        <td class="text-center text-nowrap" style="width: 1px">
                                                            <a href="{{ route('admin.product.skus.edit', ['product' => $product->id, 'sku' => $skus->id]) }}"
                                                                class="btn btn-warning">
                                                                <svg stroke="currentColor" fill="none"
                                                                    stroke-width="2" viewBox="0 0 24 24"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    height="1em" width="1em"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                                                    </path>
                                                                    <path
                                                                        d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                                                    </path>
                                                                </svg>
                                                            </a>
                                                            <a href="{{ route('admin.product.skus.show', ['product' => $product->id, 'sku' => $skus->id]) }}"
                                                                class="btn btn-info"><i class="bi bi-eye"></i></a>
                                                            <form
                                                                action="{{ route('admin.skus.change_status', ['product' => $product->id, 'sku' => $skus->id]) }}"
                                                                method="post"
                                                                onsubmit="return confirm('Bạn có chắc muốn disable sản phẩm này?')"
                                                                style="display:inline;">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit" class="btn btn-primary"><i
                                                                        class="fa-solid fa-repeat"></i></button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-12">
                                        {{ $skuses->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
