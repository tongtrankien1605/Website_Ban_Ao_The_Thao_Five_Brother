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
                            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
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
                                                    <th>Thông tin tài khoản</th>
                                                    <th>Lý do</th>
                                                    <th>Số tiền hoàn</th>
                                                    <th>Số lượng hoàn</th>
                                                    <th>Trạng thái</th>
                                                    <th>Ảnh</th>
                                                    <th>Video</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($refunds as $refund)
                                                    <tr>
                                                        <td>
                                                            <p><strong>Số tài khoản:</strong>
                                                                {{ $refund->bank_account }}<br></p>
                                                            <p><strong>Tên ngân hàng:</strong> {{ $refund->bank_name }}<br>
                                                            </p>
                                                            <p><strong>Tên chủ tài khoản:</strong>
                                                                {{ $refund->account_holder_name }}</p>
                                                        </td>
                                                        <td>{{ $refund->reason }}</td>
                                                        <td>{{ number_format($refund->refund_amount, 0, ',', '.') }} VND
                                                        </td>
                                                        <td>{{ $refund->refund_quantity }}</td>
                                                        <td>{{ $refund->status }}</td>
                                                        <td>
                                                            @if ($refund->image_path)
                                                                <img src="{{ asset('storage/' . $refund->image_path) }}"
                                                                    width="60" alt="Image">
                                                            @else
                                                                <span>Không có ảnh</span>
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if ($refund->video_path)
                                                                <video width="100" controls>
                                                                    <source src="{{ asset('storage/' . $refund->video_path) }}" type="video/mp4">
                                                                    Trình duyệt của bạn không hỗ trợ video.
                                                                </video>
                                                            @else
                                                                <span>Không có video</span>
                                                            @endif
                                                        </td>
                                                        
                                                        <td></td>
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
