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
                            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Trang chủ</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.refunds.show', $refund->id) }}">Sản phẩm</a></li>
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
                            <div class="card-header">
                                <h3 class="card-title">Thông tin sản phẩm</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Thông tin tài khoản</th>
                                                <td>
                                                    <p><strong>Số tài khoản:</strong> {{ $refund->bank_account }}<br></p>
                                                    <p><strong>Tên ngân hàng:</strong> {{ $refund->bank_name }}<br></p>
                                                    <p><strong>Tên chủ tài khoản:</strong> {{ $refund->account_holder_name }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Lý do</th>
                                                <td>{{ $refund->reason }}</td>
                                            </tr>
                                            <tr>
                                                <th>Giá</th>
                                                <td>{{ number_format($refund->refund_amount, 0, ',', '.') }} VND</td>
                                            </tr>
                                            <tr>
                                                <th>Số lượng</th>
                                                <td>{{ $refund->refund_quantity }}</td>
                                            </tr>
                                            <tr>
                                                <th>Trạng thái</th>
                                                <td class="text-center">
                                                    @if ($refund->status == 'Đang chờ xử lý')
                                                        <span class="badge bg-warning">{{ $refund->status }}</span>
                                                    @elseif ($refund->status == 'Đã chấp nhận')
                                                        <span class="badge bg-success">{{ $refund->status }}</span>
                                                    @elseif ($refund->status == 'Đã từ chối')
                                                        <span class="badge bg-danger">{{ $refund->status }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <h5>Ảnh chứng từ</h5>
                                                @if ($refund->image_path)
                                                    <img src="{{ asset('storage/' . $refund->image_path) }}" class="img-fluid" alt="Image">
                                                @else
                                                    <span>Không có ảnh</span>
                                                @endif
                                            </div>
                                            <div class="col-12">
                                                <h5>Video chứng từ</h5>
                                                @if ($refund->video_path)
                                                    <video class="w-100" controls>
                                                        <source src="{{ asset('storage/' . $refund->video_path) }}" type="video/mp4">
                                                        Trình duyệt của bạn không hỗ trợ video.
                                                    </video>
                                                @else
                                                    <span>Không có video</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('admin.refunds.index') }}" class="btn btn-secondary">Quay lại</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
