@extends('admin.layouts.index')
@section('title')
    Danh sách sản phẩm
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Danh sách sản phẩm</h3>
                            </div>
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
                                                        <th class="sorting sorting_asc" tabindex="0"
                                                            aria-controls="example1" rowspan="1" colspan="1"
                                                            aria-sort="ascending"
                                                            aria-label="Rendering engine: activate to sort column descending">
                                                            Tên sản phẩm
                                                        </th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1"
                                                            rowspan="1" colspan="1"
                                                            aria-label="Browser: activate to sort column ascending">Brand
                                                        </th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1"
                                                            rowspan="1" colspan="1"
                                                            aria-label="Platform(s): activate to sort column ascending">
                                                            Category</th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1"
                                                            rowspan="1" colspan="1"
                                                            aria-label="Engine version: activate to sort column ascending">
                                                            Image
                                                        </th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1"
                                                            rowspan="1" colspan="1"
                                                            aria-label="Engine version: activate to sort column ascending">
                                                            Price
                                                        </th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1"
                                                            rowspan="1" colspan="1"
                                                            aria-label="CSS grade: activate to sort column ascending">Hành
                                                            động</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($products as $product)
                                                        <tr>
                                                            <td class="dtr-control sorting_1" tabindex="0">
                                                                {{ $product->name }}</td>
                                                            <td>{{ $product->product_brand }}</td>
                                                            <td>{{ $product->product_category }}</td>
                                                            <td>
                                                                <img src="{{Storage::url($product->image) }}" width="100px" alt="">
                                                            </td>
                                                            <td>{{ $product->price }}</td>
                                                            <td
                                                                class=" d-flex justify-content-around align-items-center text-nowrap">
                                                                <a class="btn btn-success btn-sm"
                                                                    href="{{ route('admin.product.show', $product->id) }}">Xem</a>
                                                                <a class="btn btn-primary btn-sm"
                                                                    href="{{ route('admin.product.edit', $product->id) }}"
                                                                    role="button">Sửa</a>
                                                                <form
                                                                    action="{{ route('admin.product.destroy', $product->id) }}"
                                                                    method="post">
                                                                    @method('DELETE')
                                                                    @csrf
                                                                    <button type="submit"
                                                                        onclick="return confirm('Bạn sẽ xóa sản phẩm')"
                                                                        class="btn btn-danger btn-sm">Xóa
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th rowspan="1" colspan="1">Tên sản phẩm</th>
                                                        <th rowspan="1" colspan="1">Brand</th>
                                                        <th rowspan="1" colspan="1">Category</th>
                                                        <th rowspan="1" colspan="1">Image</th>
                                                        <th rowspan="1" colspan="1">Price</th>
                                                        <th rowspan="1" colspan="1">Hành động</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            {{ $products->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
