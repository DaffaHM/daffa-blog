@extends('back.layout')
@section('title', 'tambah blog/Artikel')

@section('content')
    <div class="row g-0">

        <h1 class="text-center mb-3">Tambah Blog/Artikel</h1>
        @if ($errors->any())
            <div class="alert alert-danger mx-2">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="col-md-12 mb-3">
            <form action="{{ route('blog.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Judul</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <input type="text" name="description" id="description" class="form-control"
                        value="{{ old('description') }}">
                </div>

                <div class="mb-3">
                    <label for="thumbnail">Thumbnail</label>
                    <input type="file" name="thumbnail" id="thumbnail" class="form-control"
                        value="{{ old('thumbnail') }}">
                </div>

                <div class="mb-3">
                    <label for="content" class="content">Content</label>
                    <textarea name="content" id="content" class="form-control">{{ old('content') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="publish" {{ old('status') == 'publish' ? 'selected' : '' }}>publish</option>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>draft</option>

                    </select>
                </div>
                <div class="mb-3 text-center">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('blog.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>

@endsection
