@extends('admin.layouts.index')
@section('title')
    Thêm mới người dùng
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- /.content-header -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Thêm mới người dùng</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form method="post" enctype="multipart/form-data" action="{{ route('admin.user.store') }}">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Enter name" value="{{ old('name') }}">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="phone_number">Số điện thoại</label>
                                        <input type="number" class="form-control" id="phone_number" name="phone_number"
                                            placeholder="Enter Phone number" value="{{ old('phone_number') }}">
                                        @error('phone_number')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="Enter email" value="{{ old('email') }}">
                                        @error('email')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="gender">Giới tính</label>
                                        <select class="form-control" id="gender" name="gender">
                                            <option value="male">Other</option>
                                            <option value="male">Nam</option>
                                            <option value="female">Nữ</option>
                                        </select>
                                        @error('gender')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="birthday">Ngày sinh</label>
                                        <input type="date" class="form-control" id="birthday" name="birthday"
                                            value="{{ old('birthday') }}">
                                        @error('birthday')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="role">Vai trò</label>
                                        <select class="form-control" id="role" name="role">
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->user_role }}</option>
                                            @endforeach
                                        </select>
                                        @error('role')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="Password">
                                        @error('password')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="password_confirmation">Confirm Password</label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" placeholder="Confirm Password">
                                        @error('password_confirmation')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="avatar">Avatar</label>
                                        <div class="input-group">
                                            <input type="file" class="" id="avatar" name="avatar"
                                                value="{{ old('avatar') }}">
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <a href="{{ route('admin.user.index') }}" class="btn btn-danger">Quay
                                            lại</a>
                                        <button type="submit" class="btn btn-primary">Thêm mới</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
    </div>
@endsection
