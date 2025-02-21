@extends('admin.layouts.index')
@section('title')
    Danh sách biển thể sản phẩm
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Danh sách biến thể sản phẩm {{ $product->name }}</h3>
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
                                                            Ảnh biến thể
                                                        </th>
                                                        <th class="sorting sorting_asc" tabindex="0"
                                                            aria-controls="example1" rowspan="1" colspan="1"
                                                            aria-sort="ascending"
                                                            aria-label="Rendering engine: activate to sort column descending">
                                                            Tên biến thể
                                                        </th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1"
                                                            rowspan="1" colspan="1"
                                                            aria-label="Engine version: activate to sort column ascending">
                                                            Price
                                                        </th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1"
                                                            rowspan="1" colspan="1"
                                                            aria-label="Engine version: activate to sort column ascending">
                                                            Sale price
                                                        </th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1"
                                                            rowspan="1" colspan="1"
                                                            aria-label="Engine version: activate to sort column ascending">
                                                            Status
                                                        </th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1"
                                                            rowspan="1" colspan="1"
                                                            aria-label="CSS grade: activate to sort column ascending">Hành
                                                            động</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($skuses as $skus)
                                                        <tr>
                                                            <td> <img src="{{ Storage::url($skus->image) }}" width="100px"
                                                                    alt=""></td>
                                                            <td class="dtr-control sorting_1" tabindex="0">
                                                                {{ $skus->name }}</td>
                                                            <td>{{ $skus->price }}</td>
                                                            <td>{{ $skus->sale_price }}</td>
                                                            <td>
                                                                @if ($skus->status)
                                                                    <span class="badge bg-success">active</span>
                                                                @else
                                                                    <span class="badge bg-danger">dieactive</span>
                                                                @endif
                                                            </td>
                                                            <td
                                                                class=" d-flex justify-content-around align-items-center text-nowrap">
                                                                <a class="btn btn-success btn-sm"
                                                                    href="{{ route('admin.product.product_attribute.show', ['product' => $product->id, 'product_attribute' => $skus->id]) }}">Xem</a>
                                                                <a class="btn btn-primary btn-sm"
                                                                    href="{{ route('admin.product.product_attribute.edit', ['product' => $product->id, 'product_attribute' => $skus->id]) }}"
                                                                    role="button">Sửa</a>
                                                                <form
                                                                    action="{{ route('admin.product.product_attribute.changeActive', ['product' => $product->id, 'product_attribute' => $skus->id]) }}"
                                                                    method="post">
                                                                    @method('put')
                                                                    @csrf
                                                                    <button type="submit"
                                                                        onclick="return confirm('Bạn sẽ xóa biến thể sản phẩm')"
                                                                        class="btn btn-danger btn-sm">change status
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th rowspan="1" colspan="1">Tên sản phẩm</th>
                                                        <th rowspan="1" colspan="1">Tên biến thể</th>
                                                        <th rowspan="1" colspan="1">giá</th>
                                                        <th rowspan="1" colspan="1">số lượng</th>
                                                        <th rowspan="1" colspan="1">barcode</th>
                                                        <th rowspan="1" colspan="1">Hành động</th>
                                                    </tr>
                                                </tfoot>
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
        </section>
    </div>
@endsection
