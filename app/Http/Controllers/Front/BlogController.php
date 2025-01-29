<?php

namespace App\Http\Controllers\Front;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    function index()
    {
        $dataTerakhir = $this->dataTerakhir();

        $data = Blog::where('status', 'publish')
            ->where('id', '!=', $dataTerakhir->id)
            ->orderBy('id', 'desc')
            ->paginate(5);

        return view('front.blog.berita', [
            'data' => $data,
            'dataTerakhir' => $dataTerakhir,
        ]);
    }

    function show($slug)
    {
        $data = Blog::where('status', 'publish')
            ->where('slug', $slug)
            ->firstOrFail();
        $pagination = $this->pagination($data->id);

        return view('front.blog.detail', [
            'data' => $data,
            'pagination' => $pagination,
        ]);
    }

    private function pagination($id)
    {
        $dataSebelum = Blog::where('status', 'publish')
            ->where('id', '<', $id)
            ->orderBy('id', 'desc')
            ->first();

        $dataSesudah = Blog::where('status', 'publish')
            ->where('id', '>', $id)
            ->orderBy('id', 'desc')
            ->first();

        $data = [
            'sebelum' => $dataSebelum,
            'sesudah' => $dataSesudah
        ];

        return $data;
    }

    function dataTerakhir()
    {
        $data = Blog::where('status', 'publish')
            ->orderBy('id', 'desc')
            ->latest()
            ->first();

        return $data;
    }
}
