@extends('back.layout')
@section('title', 'hapus berita')
@section('content')
    <div class="row g-0">
        <h1 class="text-center">Hapus berita</h1>
        <div class="alert alert-danger" role="alert">
            <h1 class="text-center mb-3"> <i class='bx bx-error'></i> Apakah anda yakin ingin menghapus data siswa
                <br>
                Data yang dihapus tidak dapat dikembalikan!
            </h1>
        </div>
        <table class="table">
            <tr>
                <td>Judul</td>
                <td>:</td>
                <td>
                    {{ $data->title }}
                    <div class="small">Penulis : {{ $data->user->name }}</div>
                </td>
            </tr>
            <tr>
                <td>Deskripsi</td>
                <td>:</td>
                <td>{{ $data->description }}</td>
            </tr>
            <tr>
                <td>Thumbnail</td>
                <td>:</td>
                <td>
                    <img src="{{ asset('thumbnail/' . $data->thumbnail) }}" alt="" class="img-thumbnail">
                </td>
            </tr>
            <tr>
                <td>Konten</td>
                <td>:</td>
                <td>{!! $data->content !!}</td>
            </tr>
            <tr>
                <td>status</td>
                <td>:</td>
                <td>{{ $data->status }}</td>
            </tr>
        </table>
        <div class="my-3">
            <form action="{{ route('blog.destroy', ['blog' => $data->id]) }}" method="post">
                @csrf
                @method('delete')
                <center>
                    <a href="{{ route('blog.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </center>
            </form>
        </div>
    </div>
@endsection
