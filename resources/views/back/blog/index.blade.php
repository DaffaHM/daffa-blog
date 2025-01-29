@extends('back.layout')
@section('title', 'blog')
@section('content')
    <div class="row g-0">
        <h1 class="text-center mb-3">Daftar Blog</h1>
        <div class=" d-flex justify-content-between">
            <div class="col-4 col-md-4 mb-3">
                <a href="{{ route('blog.create') }}" class="btn btn-primary">Tambah berita</a>
            </div>
            <div class="col-4 col-md-4 mb-3">
                <form action="{{ route('blog.index') }}" method="post">
                    @csrf
                    <div class="input-group">
                        <input type="text" value="{{ request('cari') }}" name="cari" id=""
                            class="form-control">
                        <button type="submit" class="btn btn-primary">cari</button>
                        <a href="{{ route('blog.index') }}" class="btn btn-danger">batal</a>
                    </div>
                </form>
            </div>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered">
                <thead>
                    <tr class="text-center">
                        <th>No</th>
                        <th>Judul</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $value)
                        <tr class="text-center">
                            <td>{{ $data->firstItem() + $key }}</td>
                            <td>{{ $value->title }}
                                <div class="small">
                                    penulis : {{ $value->user->name }}
                                </div>
                            </td>
                            <td>{{ $value->created_at->isoFormat('dddd, D MMMM Y') }}</td>
                            <td>
                                @if ($value->status == 'publish')
                                    <span class="badge bg-success">publish</span>
                                @else
                                    <span class="badge bg-danger">draft</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ url('/berita/' . $value->slug) }}" class="btn btn-info">
                                    Lihat Berita
                                </a>
                                <a href="{{ route('blog.edit', ['blog' => $value->id]) }}" class="btn btn-warning">edit</a>
                                <a href="{{ route('blog.delete', ['blog' => $value->id]) }}"
                                    class="btn btn-danger">delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-5">
            {{ $data->links() }}
        </div>
    </div>
@endsection
