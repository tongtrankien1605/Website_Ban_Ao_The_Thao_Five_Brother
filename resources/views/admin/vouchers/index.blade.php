@extends('admin.layouts.index')
@section('title')
    Danh sách Người dùng
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Danh sách Voucher</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <div class="dt-buttons btn-group flex-wrap">
                                                <button class="btn btn-secondary buttons-copy buttons-html5" tabindex="0"
                                                    aria-controls="example1" type="button"><span>Copy</span></button>
                                                <button class="btn btn-secondary buttons-csv buttons-html5" tabindex="0"
                                                    aria-controls="example1" type="button"><span>CSV</span></button>
                                                <button class="btn btn-secondary buttons-excel buttons-html5" tabindex="0"
                                                    aria-controls="example1" type="button"><span>Excel</span></button>
                                                <button class="btn btn-secondary buttons-pdf buttons-html5" tabindex="0"
                                                    aria-controls="example1" type="button"><span>PDF</span></button>
                                                <button class="btn btn-secondary buttons-print" tabindex="0"
                                                    aria-controls="example1" type="button"><span>Print</span></button>
                                                <div class="btn-group">
                                                    <button
                                                        class="btn btn-secondary buttons-collection dropdown-toggle buttons-colvis"
                                                        tabindex="0" aria-controls="example1" type="button"
                                                        aria-haspopup="true"><span>Column
                                                            visibility</span><span class="dt-down-arrow"></span></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div id="example1_filter" class="dataTables_filter"><label>Search:<input
                                                        type="search" class="form-control form-control-sm" placeholder=""
                                                        aria-controls="example1"></label>
                                            </div>
                                        </div>
                                    </div>
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
                                                            Mã Voucher
                                                        </th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1"
                                                            rowspan="1" colspan="1"
                                                            aria-label="Browser: activate to sort column ascending">Loại
                                                        </th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1"
                                                            rowspan="1" colspan="1"
                                                            aria-label="Platform(s): activate to sort column ascending">
                                                            Giá trị</th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1"
                                                            rowspan="1" colspan="1"
                                                            aria-label="Engine version: activate to sort column ascending">
                                                            Số lần sử dụng
                                                        </th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1"
                                                            rowspan="1" colspan="1"
                                                            aria-label="CSS grade: activate to sort column ascending">Ngày
                                                            bắt đầu</th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1"
                                                            rowspan="1" colspan="1"
                                                            aria-label="CSS grade: activate to sort column ascending">Ngày
                                                            kết thúc</th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1"
                                                            rowspan="1" colspan="1"
                                                            aria-label="CSS grade: activate to sort column ascending">Trạng thái</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($vouchers as $voucher)
                                                        <tr>
                                                            <td class="dtr-control sorting_1" tabindex="0">
                                                                {{ $voucher->code }}</td>
                                                                <td>{{ $voucher->discount_type }}</td>
                                                                <td>{{ $voucher->discount_value }}</td>
                                                                <td>{{ $voucher->total_usage }}</td>
                                                                <td>{{ $voucher->start_date }}</td>
                                                                <td>{{ $voucher->end_date }}</td>
                                                                <td>{{ $voucher->status ? 'Còn' : 'Hết' }}</td>
                                                            <td
                                                                class=" d-flex justify-content-around align-items-center text-nowrap">
                                                                {{-- <a class="btn btn-success btn-sm"
                                                                    href="{{ route('admin.vouchers.show', $voucher->id) }}">Xem</a> --}}
                                                                <a class="btn btn-primary btn-sm"
                                                                    href="{{ route('admin.vouchers.edit', $voucher->id) }}"
                                                                    role="button">Sửa</a>
                                                                <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}"
                                                                    method="post">
                                                                    @method('DELETE')
                                                                    @csrf
                                                                    <button type="submit"
                                                                        onclick="return confirm('Bạn sẽ xóa khách hàng')"
                                                                        class="btn btn-danger btn-sm">Xóa
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th rowspan="1" colspan="1">Mã Voucher</th>
                                                        <th rowspan="1" colspan="1">Loại</th>
                                                        <th rowspan="1" colspan="1">Giá trị</th>
                                                        <th rowspan="1" colspan="1">Số lần sử dụng</th>
                                                        <th rowspan="1" colspan="1">Ngày bắt đầu</th>
                                                        <th rowspan="1" colspan="1">Ngày kết thúc</th>
                                                        <th rowspan="1" colspan="1">Trạng thái</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            {{-- {{ $vouchers->links() }} --}}
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

