@extends('admin.layouts.index')

@section('title')
    Quản lý yêu cầu hoàn tiền
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Quản lý yêu cầu hoàn tiền</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="">Home</a></li>
                            <li class="breadcrumb-item active"><a href="{{ route('admin.refunds.index') }}">Quản lý yêu cầu
                                    hoàn tiền</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                    <div class="row">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Tên tài khoản</th>
                                                    <th>Lý do</th>
                                                    <th>Số tiền hoàn</th>
                                                    <th>Số lượng hoàn</th>
                                                    <th>Trạng thái</th>
                                                    <th>Chứng từ</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($refunds as $refund)
                                                    <tr>
                                                        <td>
                                                            {{ $refund->user->name }}
                                                        </td>
                                                        <td>{{ $refund->reason }}</td>
                                                        <td>{{ number_format($refund->refund_amount, 0, ',', '.') }} VND
                                                        </td>
                                                        <td>{{ $refund->refund_quantity }}</td>
                                                        <td class="text-center">
                                                            @if ($refund->status == 'Đang chờ xử lý')
                                                                <span class="badge bg-warning">{{ $refund->status }}</span>
                                                            @elseif ($refund->status == 'Đã chấp nhận')
                                                                <span class="badge bg-success">{{ $refund->status }}</span>
                                                            @elseif ($refund->status == 'Đã từ chối')
                                                                <span class="badge bg-danger">{{ $refund->status }}</span>
                                                            @endif
                                                        </td>
                                                        
                                                        <td>
                                                            @if ($refund->image_path)
                                                                <span>Có ảnh minh họa</span>
                                                            @elseif ($refund->video_path)
                                                                <span>Có video minh họa/span>
                                                                @else
                                                                    <span>Không có ảnh minh họa</span>
                                                            @endif
                                                        </td>

                                                        <td>
                                                            <a class="btn btn-primary"
                                                                href="{{ route('admin.refunds.show', $refund->id) }}"><i
                                                                    class="bi bi-eye"></i></a>
                                                        </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            {{ $refunds->links() }}
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
