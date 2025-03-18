@extends('admin.layouts.index')
@section('title')
    Chỉnh sửa người dùng
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Chỉnh sửa người dùng</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                            <li class="breadcrumb-item active"><a href="{{ route('admin.user.edit',$user->id) }}">Chỉnh sửa người
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
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form method="post" enctype="multipart/form-data"
                                action="{{ route('admin.user.update', $user->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Enter name" value="{{ $user->name }}">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="phone_number">Số điện thoại</label>
                                        <input type="text" class="form-control" id="phone_number" name="phone_number"
                                            placeholder="Enter Phone number" value="{{ $user->phone_number }}">
                                        @error('phone_number')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="Enter email" value="{{ $user->email }}">
                                        @error('email')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="gender">Giới tính</label>
                                        <select class="form-control" id="gender" name="gender">
                                            <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Other
                                            </option>
                                            <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Nam
                                            </option>
                                            <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Nữ
                                            </option>
                                        </select>
                                        @error('gender')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="birthday">Ngày sinh</label>
                                        <input type="date" class="form-control" id="birthday" name="birthday"
                                            value="{{ $user->birthday }}">
                                        @error('birthday')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="role">Vai trò</label>
                                        <select class="form-control" id="role" name="role">
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}"
                                                    {{ $user->role == $role->id ? 'selected' : '' }}>
                                                    {{ $role->user_role }}
                                                </option>
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
                                            <div class="input-group">
                                                @if ($user->avatar)
                                                    <img src="{{ Storage::url($user->avatar) }}" alt=""
                                                        width="100px">
                                                @endif
                                                <input type="file" class="form-control" id="avatar" name="avatar">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <a href="{{ route('admin.user.index') }}" class="btn btn-danger text-center">Quay
                                            lại</a>
                                        <button type="submit" class="btn btn-primary text-center">Cập nhật</button>

                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
