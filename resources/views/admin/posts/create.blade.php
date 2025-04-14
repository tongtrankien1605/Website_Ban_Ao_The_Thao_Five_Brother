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
                    <h1>Add New Post</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Posts</a></li>
                        <li class="breadcrumb-item active">Add New</li>
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
                            <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="title">Post Title</label>
                                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                                                value="{{ old('title') }}" placeholder="Enter post title">
                                            @error('title')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="short_description">Short Description</label>
                                            <textarea name="short_description" id="short_description" rows="3" 
                                                class="form-control @error('short_description') is-invalid @enderror" 
                                                placeholder="Enter a brief description">{{ old('short_description') }}</textarea>
                                            @error('short_description')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="content">Content</label>
                                            <textarea name="content" id="summernote" class="form-control @error('content') is-invalid @enderror">
                                                {{ old('content') }}
                                            </textarea>
                                            @error('content')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Post Settings</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="author">Author</label>
                                                    <input type="text" name="author" id="author" 
                                                        class="form-control @error('author') is-invalid @enderror"
                                                        value="{{ old('author') }}" placeholder="Enter author name">
                                                    @error('author')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="published_at">Publish Date</label>
                                                    <input type="datetime-local" name="published_at" id="published_at" 
                                                        class="form-control @error('published_at') is-invalid @enderror"
                                                        value="{{ old('published_at') }}">
                                                    @error('published_at')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="image">Featured Image</label>
                                                    <div class="custom-file">
                                                        <input type="file" name="image" class="custom-file-input @error('image') is-invalid @enderror" 
                                                            id="image" accept="image/*">
                                                        <label class="custom-file-label" for="image">Choose file</label>
                                                    </div>
                                                    @error('image')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                    <div id="image-preview" class="mt-2"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Cancel</a>
                                        <button type="submit" class="btn btn-success float-right">Publish Post</button>
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bs-custom-file-input/1.3.4/bs-custom-file-input.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Summernote
        $('#summernote').summernote({
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
@endpush
@endsection