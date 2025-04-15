@extends('admin.layouts.index')
@section('title')
    {{ isset($user) ? 'Cập nhật người dùng' : 'Thêm mới người dùng' }}
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="container-fluid p-4">
            <!-- Form Card -->
            <div class="card">
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data" 
                          action="{{ isset($user) ? route('admin.user.update', $user->id) : route('admin.user.store') }}">
                        @csrf
                        @if(isset($user))
                            @method('PUT')
                        @endif

                        <!-- User Image -->
                        <div class="mb-4">
                            <label class="form-label">Ảnh đại điện</label>
                            <div class="border rounded-3 p-4 text-center position-relative" 
                                 style="border-style: dashed !important;">
                                <input type="file" class="position-absolute top-0 start-0 bottom-0 end-0 opacity-0" 
                                       id="avatar" name="avatar" accept=".jpg,.webp,.png">
                                <div class="py-4">
                                    <div id="preview-container">
                                        @if(isset($user) && $user->avatar)
                                            <img src="{{ Storage::url($user->avatar) }}" alt="Current avatar" 
                                                class="mb-3" style="max-height: 100px;" id="preview-image">
                                        @else
                                            <i class="fas fa-cloud-upload-alt text-success mb-3" style="font-size: 2rem;" id="upload-icon"></i>
                                            <img id="preview-image" style="max-height: 100px; display: none;" class="mb-3">
                                        @endif
                                    </div>
                                    <div class="text-center">
                                        <h6 class="mb-2">Chọn ảnh của bạn</h6>
                                        <p class="text-muted small mb-0">(Chỉ *.jpg, *.webp and *.png images will be accepted)</p>
                                    </div>
                                </div>
                            </div>
                            @error('avatar')
                                <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label">Tên</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   placeholder="Tên người dùng" value="{{ $user->name ?? old('name') }}">
                            @error('name')
                                <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="Email" value="{{ $user->email ?? old('email') }}">
                            @error('email')
                                <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Mật khẩu" {{ !isset($user) ? 'required' : '' }}>
                            @error('password')
                                <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" 
                                   placeholder="Mật khẩu" {{ !isset($user) ? 'required' : '' }}>
                            @error('password_confirmation')
                                <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contact Number -->
                        <div class="mb-4">
                            <label for="phone_number" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" 
                                   placeholder="Số điện thoại" value="{{ $user->phone_number ?? old('phone_number') }}">
                            @error('phone_number')
                                <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div class="mb-4">
                            <label for="gender" class="form-label">Giới tính</label>
                            <select class="form-control" id="gender" name="gender">
                                <option value="">Giới tính</option>
                                <option value="male" {{ (isset($user) && $user->gender == 'male') ? 'selected' : (old('gender') == 'male' ? 'selected' : '') }}>Male</option>
                                <option value="female" {{ (isset($user) && $user->gender == 'female') ? 'selected' : (old('gender') == 'female' ? 'selected' : '') }}>Female</option>
                                <option value="other" {{ (isset($user) && $user->gender == 'other') ? 'selected' : (old('gender') == 'other' ? 'selected' : '') }}>Other</option>
                            </select>
                            @error('gender')
                                <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Birthday -->
                        <div class="mb-4">
                            <label for="birthday" class="form-label">Ngày sinh</label>
                            <input type="date" class="form-control" id="birthday" name="birthday"
                                max="{{ date('Y-m-d') }}"
                                value="{{ isset($user) && $user->birthday ? date('Y-m-d', strtotime($user->birthday)) : old('birthday') }}">
                            @error('birthday')
                                <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="mb-4">
                            <label for="role" class="form-label">Vai trò</label>
                            <select class="form-control" id="role" name="role">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" 
                                        {{ (isset($user) && $user->role == $role->id) ? 'selected' : '' }}>
                                        {{ $role->user_role }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.user.index') }}" class="btn btn-light flex-grow-1">Quay lại</a>
                            <button type="submit" class="btn btn-success flex-grow-1">
                                {{ isset($user) ? 'Cập nhật người dùng' : 'Thêm mới người dùng' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .content-wrapper {
        background-color: #f8fafc;
    }
    .form-control {
        border-color: #e5e7eb;
        padding: 0.75rem 1rem;
    }
    .form-control:focus {
        border-color: #10B981;
        box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25);
    }
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    .btn-success {
        background-color: #10B981;
        border-color: #10B981;
    }
    .btn-success:hover {
        background-color: #059669;
        border-color: #059669;
    }
    .btn-light {
        background-color: #f8f9fa;
        border-color: #e9ecef;
        color: #dc3545;
    }
    .btn-light:hover {
        background-color: #e9ecef;
        border-color: #dde0e3;
        color: #dc3545;
    }
</style>
@endpush

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const avatarInput = document.getElementById('avatar');
        const previewImage = document.getElementById('preview-image');
        const uploadIcon = document.getElementById('upload-icon');
        const previewContainer = document.getElementById('preview-container');

        avatarInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'inline-block';
                    previewImage.style.maxHeight = '100px';
                    previewImage.style.objectFit = 'contain';
                    previewImage.style.borderRadius = '5px';
                    previewImage.classList.add('mb-3');
                    
                    if (uploadIcon) {
                        uploadIcon.style.display = 'none';
                    }
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
