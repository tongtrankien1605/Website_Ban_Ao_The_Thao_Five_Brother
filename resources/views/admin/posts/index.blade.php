@extends('admin.layouts.index')

@section('title')
List Posts
@endsection

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>List Posts</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('admin.posts.index') }}">List Posts</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="col-12">
        <div class="card" style="height: 700px; width:1250px">
            <div class="card-header">
                <h3 class="card-title"></h3>
                <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">Create New Post</a>
                <div class="card-tools">
                    <form action="{{ route('admin.posts.index') }}" method="GET" class="input-group input-group-sm"
                        style="width: 150px;">
                        <input type="text" name="search" class="form-control float-right" placeholder="Search"
                            value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="table table-head-fixed text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tiêu Đề</th>
                            <th>Tác Giả</th>
                            <th>Ngày Xuất Bản</th>
                            <th>Ảnh</th>
                            <th>Mô tả ngắn</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posts as $post)
                            <tr>
                                <td>{{ $post->id }}</td>
                                <td>{{ Str::limit($post->title, 10) }}</td>
                                <td>{{ $post->author }}</td>
                                <td>{{ $post->published_at }}</td>
                                <td>
                                    @if($post->image)
                                        <img src="{{ asset('storage/' . $post->image) }}" width="100" alt="image"
                                            height="130px">
                                    @endif
                                </td>
                                <td>{{ Str::limit($post->short_description, 20) }}</td>
                                <td>
                                    <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-warning">Sửa</a>
                                    <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Xóa</button>
                                    </form>
                                    <a href="{{ route('admin.posts.show', $post->id) }}" class="btn btn-info">Xem Chi Tiết</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            {{ $posts->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
</div>
@extends('admin.table.js')
@endsection