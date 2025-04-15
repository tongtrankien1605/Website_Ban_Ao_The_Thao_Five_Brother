@extends('admin.layouts.index')

@section('title')
    Tất cả người dùng
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid p-4">
            <!-- Header -->
            <div class="mb-4">
                <h1 class="h3 mb-4">Tất cả người dùng</h1>

                <!-- Search and Filter -->
                <div class="d-flex gap-3 mb-4">
                    <div class="flex-grow-1">
                        <input type="text" class="form-control" placeholder="Tìm kiếm" id="searchInput"
                            name="search" value="{{ request('search') }}">
                    </div>
                    <select class="form-control" style="width: 200px;" id="roleFilter">
                        <option value="">Tất cả</option>
                        <option value="admin">Quản trị viên</option>
                        <option value="staff">Nhân viên</option>
                        <option value="user">Người dùng</option>
                    </select>
                    <a href="{{ route('admin.user.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i> Thêm người dùng
                    </a>
                </div>
            </div>

            <!-- Table -->
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>TÊN</th>
                                <th>EMAIL</th>
                                <th>ĐIỆN THOẠI</th>
                                <th>NGÀY SINH</th>
                                <th>VAI TRÒ</th>
                                <th>TRẠNG THÁI</th>
                                {{-- <th>PUBLISHED</th> --}}
                                <th class="text-end">HÀNH ĐỘNG</th>
                            </tr>
                        </thead>
                        <tbody id="accountTable">
                            @foreach ($users as $user)
                                <tr class="user-row" data-role="{{ strtolower($user->user_role) }}">
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar">
                                                @if ($user->avatar)
                                                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}"
                                                        class="rounded-circle" width="40" height="40">
                                                @else
                                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                                        style="width: 40px; height: 40px;">
                                                        <i class="fas fa-user text-secondary"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>{{ $user->name }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone_number }}</td>
                                    <td>{{ $user->birthday ? $user->birthday->format('j M, Y') : '' }}</td>
                                    <td>{{ $user->user_role }}</td>
                                    <td>
                                        @if ($user->status == 1)
                                            <span class="badge bg-success-light text-success">Hoạt động</span>
                                        @else
                                            <span class="badge bg-success-light text-danger">Không hoạt động</span>
                                        @endif
                                    </td>
                                    {{-- <td>
                                        <div class="form-check form-switch">
                                            <input type="checkbox" class="form-check-input" checked>
                                        </div>
                                    </td> --}}
                                    <td>
                                        <div class="d-flex gap-1 justify-content-end">
                                            <a href="{{ route('admin.user.show', $user->id) }}" class="btn btn-sm btn-light"
                                                title="Xem chi tiết">
                                                <i class="fas fa-search"></i>
                                            </a>
                                            <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-sm btn-light"
                                                title="Sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this member?')"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light" title="Xóa">
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

                {{-- @if ($users->hasPages())
                <div class="card-footer border-top bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="pagination">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
                @endif --}}
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .content-wrapper {
            background-color: #f8fafc;
            min-height: calc(100vh - 57px - 57px);
            /* Trừ đi chiều cao của navbar và footer */
            padding-bottom: 30px;
            overflow: auto;
        }

        .table> :not(caption)>*>* {
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

        .avatar img,
        .avatar div {
            object-fit: cover;
        }
    </style>
@endpush
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // console.log('Script loaded'); // Kiểm tra script đã được tải

    // Sử dụng JavaScript thuần vì jQuery không có sẵn
    document.addEventListener('DOMContentLoaded', function () {
        // console.log('DOM fully loaded');
        var searchInput = document.getElementById('searchInput');
        var roleFilter = document.getElementById('roleFilter');

        if (searchInput) {
            // console.log('Search input found');
            searchInput.addEventListener('keyup', filterItems);
        } else {
            // console.log('Search input not found');
        }

        if (roleFilter) {
            // console.log('Role filter found');
            roleFilter.addEventListener('change', filterItems);
        } else {
            // console.log('Role filter not found');
        }

        // Lọc ngay khi trang tải xong để áp dụng giá trị mặc định
        filterItems();

        function filterItems() {
            var searchText = searchInput ? searchInput.value.toLowerCase() : '';
            var selectedRole = roleFilter ? roleFilter.value.toLowerCase() : '';
            // console.log('Filtering with search:', searchText, 'and role:', selectedRole);

            var rows = document.querySelectorAll('#accountTable tr');
            // console.log('Found', rows.length, 'rows');

            rows.forEach(function (row) {
                // Lấy text từ các cột
                var nameCell = row.querySelector('td:nth-child(1)');
                var emailCell = row.querySelector('td:nth-child(2)');
                var phoneCell = row.querySelector('td:nth-child(3)');
                var roleCell = row.querySelector('td:nth-child(5)');

                if (!nameCell || !emailCell || !phoneCell || !roleCell) {
                    // console.log('Row missing cells, skipping');
                    return;
                }

                var name = nameCell.textContent.toLowerCase();
                var email = emailCell.textContent.toLowerCase();
                var phone = phoneCell.textContent.toLowerCase();
                var role = roleCell.textContent.toLowerCase();

                // Kiểm tra điều kiện lọc
                var matchSearch = searchText === '' ||
                    name.includes(searchText) ||
                    email.includes(searchText) ||
                    phone.includes(searchText);

                var matchRole = selectedRole === '' || role.includes(selectedRole);

                // console.log('Row:', name, 'Search match:', matchSearch, 'Role match:', matchRole);

                // Hiển thị hoặc ẩn dựa trên kết quả
                row.style.display = (matchSearch && matchRole) ? '' : 'none';
            });
        }
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: "{{ session('success') }}",
                confirmButtonText: 'Đóng'
            });
        @elseif(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: "{{ session('error') }}",
                confirmButtonText: 'Đóng'
            });
        @endif
    });
</script>