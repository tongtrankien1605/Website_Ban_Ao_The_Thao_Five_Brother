@extends('admin.layouts.index')

@section('title')
    All Staff
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid p-4">
            <!-- Header -->
            <div class="mb-4">
                <h1 class="h3 mb-4">All Staff</h1>

                <!-- Search and Filter -->
                <div class="d-flex gap-3 mb-4">
                    <div class="flex-grow-1">
                        <input type="text" class="form-control" placeholder="Search by name/email/phone" name="search" value="{{ request('search') }}">
                    </div>
                    <select class="form-control" style="width: 200px;">
                        <option value="">Staff Role</option>
                        <option value="super_admin">Super Admin</option>
                        <option value="admin">Admin</option>
                        <option value="manager">Manager</option>
                        <option value="staff">Staff</option>
                    </select>
                    <a href="{{ route('admin.user.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i> Add Staff
                    </a>
                    <button class="btn btn-success px-4">Filter</button>
                    <button class="btn btn-light px-4">Reset</button>
                </div>
            </div>

            <!-- Table -->
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>NAME</th>
                                <th>EMAIL</th>
                                <th>CONTACT</th>
                                <th>JOINING DATE</th>
                                <th>ROLE</th>
                                <th>STATUS</th>
                                <th>PUBLISHED</th>
                                <th class="text-end">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar">
                                                @if($user->avatar)
                                                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="rounded-circle" width="40" height="40">
                                                @else
                                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-user text-secondary"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>{{ $user->name }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone_number }}</td>
                                    <td>{{ $user->created_at->format('j M, Y') }}</td>
                                    <td>{{ $user->user_role }}</td>
                                    <td>
                                        <span class="badge bg-success-light text-success">Active</span>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input type="checkbox" class="form-check-input" checked>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1 justify-content-end">
                                            <a href="{{ route('admin.user.show', $user->id) }}" class="btn btn-sm btn-light" title="View Details">
                                                <i class="fas fa-search"></i>
                                            </a>
                                            <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-sm btn-light" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this staff member?')"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($users->hasPages())
                    <div class="card-footer border-top bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>SHOWING 1-7 OF 7</div>
                            <div class="pagination">
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .content-wrapper {
        background-color: #f8fafc;
    }
    .table > :not(caption) > * > * {
        padding: 1rem;
    }
    .btn-light {
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }
    .btn-light:hover {
        background-color: #e9ecef;
        border-color: #dde0e3;
    }
    .bg-success-light {
        background-color: rgba(16, 185, 129, 0.1);
    }
    .form-switch .form-check-input {
        width: 2.5em;
    }
    .form-check-input:checked {
        background-color: #10B981;
        border-color: #10B981;
    }
    .pagination {
        margin-bottom: 0;
    }
    .avatar img, .avatar div {
        object-fit: cover;
    }
</style>
@endpush
