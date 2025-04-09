@extends('admin.layouts.index')
@section('title')
    {{ isset($user) ? 'Edit Staff' : 'Add Staff' }}
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

                        <!-- Staff Image -->
                        <div class="mb-4">
                            <label class="form-label">Staff Image</label>
                            <div class="border rounded-3 p-4 text-center position-relative" 
                                 style="border-style: dashed !important;">
                                <input type="file" class="position-absolute top-0 start-0 bottom-0 end-0 opacity-0" 
                                       id="avatar" name="avatar" accept=".jpg,.webp,.png">
                                <div class="py-4">
                                    @if(isset($user) && $user->avatar)
                                        <img src="{{ Storage::url($user->avatar) }}" alt="Current avatar" 
                                             class="mb-3" style="max-height: 100px;">
                                    @else
                                        <i class="fas fa-cloud-upload-alt text-success mb-3" style="font-size: 2rem;"></i>
                                    @endif
                                    <div class="text-center">
                                        <h6 class="mb-2">Drag your images here</h6>
                                        <p class="text-muted small mb-0">(Only *.jpg, *.webp and *.png images will be accepted)</p>
                                    </div>
                                </div>
                            </div>
                            @error('avatar')
                                <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   placeholder="Staff name" value="{{ $user->name ?? old('name') }}">
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
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Password" {{ !isset($user) ? 'required' : '' }}>
                            @error('password')
                                <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contact Number -->
                        <div class="mb-4">
                            <label for="phone_number" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" 
                                   placeholder="Phone number" value="{{ $user->phone_number ?? old('phone_number') }}">
                            @error('phone_number')
                                <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Joining Date -->
                        <div class="mb-4">
                            <label for="joining_date" class="form-label">Joining Date</label>
                            <input type="date" class="form-control" id="joining_date" name="joining_date" 
                                   value="{{ isset($user) ? $user->created_at->format('Y-m-d') : date('Y-m-d') }}">
                            @error('joining_date')
                                <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="mb-4">
                            <label for="role" class="form-label">Role</label>
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
                            <a href="{{ route('admin.user.index') }}" class="btn btn-light flex-grow-1">Cancel</a>
                            <button type="submit" class="btn btn-success flex-grow-1">
                                {{ isset($user) ? 'Update Staff' : 'Add Staff' }}
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
