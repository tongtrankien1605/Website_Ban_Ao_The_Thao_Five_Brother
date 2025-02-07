@extends('admin.layouts.index')

@section('title')
Add Posts
@endsection

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add Posts</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('admin.posts.create') }}">Add Posts</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <div class="container">
        <!-- general form elements -->
        <div class="card card-primary">
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="title" class="form-label">Tiêu Đề</label>
                        <input type="text" name="title" id="title" class="form-control"
                            placeholder="Nhập tiêu đề bài viết" required>
                    </div>

                    <div class="form-group">
                        <label for="author" class="form-label">Tác Giả</label>
                        <input type="text" name="author" id="author" class="form-control" placeholder="Nhập tên tác giả"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="published_at" class="form-label">Ngày Xuất Bản</label>
                        <input type="date" name="published_at" id="published_at" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="short_description" class="form-label">Mô tả ngắn</label>
                        <input type="text" name="short_description" id="short_description" class="form-control" placeholder="Nhập mô tả"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="image">Ảnh</label>
                        <input type="file" name="image" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="content" class="form-label">Nội Dung</label>
                        <textarea name="content" id="summernote" class="form-control" rows="5"
                            placeholder="Nhập nội dung bài viết" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Thêm Bài Viết</button>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Quay Lại</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </div>
</div>

<!-- Include Summernote CSS and JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.js"></script>
<script>
    $(document).ready(function () {
        $('#summernote').summernote({
            height: 300,   // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: true     // set focus to editable area after initializing summernote
        });
    });
</script>
@endsection