@extends('back.layout')
@section('title', 'hapus user')
@section('content')
    <div class="row g-0">
        <h1 class="text-center">Hapus User</h1>
        <div class="col-md-12 text-center">
            <div class="alert alert-danger" role="alert">
                <h1> <i class='bx bxs-error' style='color:#e22121'></i> Data yang dihapus tidak dapat dikembalikan</h1>
            </div>
            <h2>data user</h2>

            <table class="table">
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{ $data->name }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>:</td>
                    <td>{{ $data->email }}</td>
                </tr>
            </table>
            <form action="{{ route('user.destroy', ['user' => $data->id]) }}" method="post">
                @csrf
                @method('DELETE')
                <a href="{{ route('user.index') }}" class="btn btn-secondary">Kembali</a>
                <button class="btn btn-danger" type="submit">Hapus</button>
            </form>
        </div>
    </div>

@endsection
