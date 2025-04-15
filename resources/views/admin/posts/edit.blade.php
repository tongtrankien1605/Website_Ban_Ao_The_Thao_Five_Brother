@extends('admin.layouts.index')

@section('title')
Chỉnh sửa bài viết
@endsection

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Chỉnh sửa bài viết</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Bài viết</a></li>
                        <li class="breadcrumb-item active">Chỉnh sửa bài viết</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="title">Tiêu đề</label>
                                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                                                value="{{ old('title', $post->title) }}" placeholder="Enter post title">
                                            @error('title')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="short_description">Mô tả ngắn</label>
                                            <textarea name="short_description" id="summernote_short" rows="3" 
                                                class="form-control @error('short_description') is-invalid @enderror" 
                                                placeholder="Enter a brief description">{{ old('short_description', $post->short_description) }}</textarea>
                                            @error('short_description')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="content">Nội dung</label>
                                            <textarea name="content" id="summernote_content" class="form-control @error('content') is-invalid @enderror">
                                                {{ old('content', $post->content) }}
                                            </textarea>
                                            @error('content')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Cài đặt bài viết</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="author">Tác giả</label>
                                                    <input type="text" name="author" id="author" 
                                                        class="form-control @error('author') is-invalid @enderror"
                                                        value="{{ old('author', $post->author) }}" placeholder="Enter author name">
                                                    @error('author')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="published_at">Ngày đăng</label>
                                                    <input type="datetime-local" name="published_at" id="published_at" 
                                                        class="form-control @error('published_at') is-invalid @enderror"
                                                        value="{{ old('published_at', date('Y-m-d\TH:i', strtotime($post->published_at))) }}">
                                                    @error('published_at')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="image">Ảnh bài viết</label>
                                                    <div class="custom-file">
                                                        <input type="file" name="image" class="custom-file-input @error('image') is-invalid @enderror" 
                                                            id="image" accept="image/*">
                                                        <label class="custom-file-label" for="image">Chọn ảnh</label>
                                                    </div>
                                                    @error('image')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                    <div id="image-preview" class="mt-2">
                                                        @if($post->image)
                                                            <img src="{{ Storage::url($post->image) }}" class="img-fluid" style="max-height: 200px;">
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Quay lại</a>
                                        <button type="submit" class="btn btn-success float-right">Cập nhật</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('styles')
{{-- Không cần tải lại Summernote CSS vì đã có sẵn trong hệ thống --}}
@endpush

{{-- Không cần tải lại Summernote JS vì đã có sẵn trong hệ thống --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bs-custom-file-input/1.3.4/bs-custom-file-input.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Summernote for content
        $('#summernote_content').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        
        // Initialize Summernote for short description
        $('#summernote_short').summernote({
            height: 150,
            toolbar: [
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['view', ['fullscreen']]
            ],
            placeholder: 'Enter a brief description'
        });

        // Initialize custom file input
        bsCustomFileInput.init();

        // Image preview
        $('#image').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#image-preview').html('<img src="' + e.target.result + '" class="img-fluid" style="max-height: 200px;">');
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endsection