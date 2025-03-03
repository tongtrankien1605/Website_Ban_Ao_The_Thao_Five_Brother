@extends('admin.layouts.index')

@section('title')
    Chi tiết người dùng
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Chi tiết người dùng</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                            <li class="breadcrumb-item active"><a href="{{ route('admin.user.show', $user) }}">Chi tiết người
                                    dùng</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
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
                                    <p>
                                        @switch($user->role)
                                            @case($user->role == 1)
                                                <span>User</span>
                                            @break

                                            @case($user->role == 2)
                                                <span>Staff</span>
                                            @break

                                            @case($user->role == 3)
                                                <span>Admin</span>
                                            @break
                                        @endswitch
                                    </p>
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
                                <div class="form-group">
                                    <label>Địa chỉ:</label>
                                    <div>
                                        @foreach ($addresses as $address)
                                            <ul>
                                                <li>
                                                    <p>
                                                        @if ($address->is_default == 1)
                                                            <span><strong>Mặc định: </strong></span>
                                                        @endif
                                                        {{ $address->address }}
                                                    </p>
                                                </li>
                                            </ul>
                                        @endforeach
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
        </section>
    </div>
@endsection
