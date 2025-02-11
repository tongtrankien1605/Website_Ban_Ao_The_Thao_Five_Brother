@extends('admin.layouts.index')

@section('title')
    Chi tiết người dùng
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Chi tiết người dùng</h3>
                            </div>

                            <div class="card-body">
                                <div class="form-group">
                                    <label>Họ và tên:</label>
                                    <p>{{ $user->name }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Số điện thoại:</label>
                                    <p>{{ $user->phone_number }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Email:</label>
                                    <p>{{ $user->email }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Giới tính:</label>
                                    <p>
                                        {{ $user->gender }}
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label>Ngày sinh:</label>
                                    <p>{{ $user->birthday }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Vai trò:</label>
                                    <p>{{ $user->role }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Avatar:</label>
                                    <div>
                                        @if ($user->avatar)
                                            <img src="{{ Storage::url($user->avatar) }}" alt="" width="100px">
                                        @else
                                            <p>Chưa có ảnh đại diện</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-center">
                                    <a href="{{ route('admin.user.index') }}" class="btn btn-danger">Quay lại</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
    </div>
@endsection
