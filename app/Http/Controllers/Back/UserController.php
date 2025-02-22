<?php

namespace App\Http\Controllers\Back;

use DOMDocument;
use Carbon\Carbon;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use function PHPUnit\Framework\returnSelf;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->can('admin-user')) {
            $data = User::orderBy('id', 'desc')->get();
        } else {
            $data = User::where('id', $user->id)->get();
        }


        return view('back.user.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::get();

        return view('back.user.create', ['permissions' => $permissions]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|min:6|same:password_confirmation|required_with:password_confirmation',
            'password_confirmation' => 'required_with:password',
        ], [
            'name.required' => 'nama wajib diisi',
            'email.required' => 'email wajib diisi',
            'email.email' => 'email harus berformat email',
            'email.unique' => 'email sudah terdaftar, silakan gunakan email lain',
            'password.required_with' => 'konfirmasi password wajib diisi',
            'password.same' => 'password harus sama dengan konfirmasi password',
            'password.min' => 'password minimal :min karakter!',
            'password_confirmation.required_with' => 'password wajib diisi',
        ]);

        $email_verified_at = $request->email_verified_at ? Carbon::now() : null;

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'email_verified_at' => $email_verified_at
        ];

        $newuser = User::create($data);
        $newuser->permissions()->sync($request->permissions);
        sweetalert()->success('user berhasil dibuat');
        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        Gate::authorize('edit', $user);
        $permissions = Permission::get();
        $userPermissions = $user->getPermissionNames()->toArray();
        $data = $user;

        return view('back.user.edit', [
            'data' => $data,
            'permissions' => $permissions,
            'userPermissions' => $userPermissions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'new_password' => 'nullable|min:6|same:new_password_confirmation|required_with:new_password_confirmation',
            'new_password_confirmation' => 'required_with:new_password',
        ], [
            'name.required' => 'nama wajib diisi',
            'email.required' => 'email wajib diisi',
            'email.email' => 'email harus berformat email',
            'email.unique' => 'email sudah terdaftar, silakan gunakan email lain',
            'new_password.required_with' => 'konfirmasi password wajib diisi',
            'new_password.same' => 'password harus sama dengan konfirmasi password',
            'new_password.min' => 'password minimal :min karakter!',
            'new_password_confirmation.required_with' => 'password wajib diisi',
        ]);

        $email_verified_at = $user->email_verified_at ? $user->email_verified_at : Carbon::now();

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'email_verified_at' => $email_verified_at,
            'password' => $request->new_password ? bcrypt($request->new_password) : $user->password,
        ];

        User::findOrFail($user->id)->update($data);

        $user->syncPermissions($request->permission);
        sweetalert()->success('data user berhasil diupdate');
        return redirect()->route('user.index');
    }

    function delete(user $user)
    {
        $data = user::findOrFail($user->id);

        return view('back.user.delete', ['data' => $data]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $data = Blog::where('user_id', $user->id)->get();

        foreach ($data as $blog) {
            // Ambil konten dari kolom content
            $content = $blog->content;

            // Gunakan DOMDocument untuk memproses konten HTML
            $dom = new DOMDocument();
            libxml_use_internal_errors(true); // Untuk menghindari error parsing
            $dom->loadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            libxml_clear_errors();

            // Cari semua elemen <img> dan <a>
            $filePaths = [];

            // Ekstrak dari <img src="...">
            foreach ($dom->getElementsByTagName('img') as $img) {
                $src = $img->getAttribute('src');
                if (strpos($src, '/') === 0) {
                    $filePaths[] = $src;
                }
            }

            // Ekstrak dari <a href="...">
            foreach ($dom->getElementsByTagName('a') as $a) {
                $href = $a->getAttribute('href');
                if (strpos($href, '/') === 0) {
                    $filePaths[] = $href;
                }
            }

            // Hapus semua file yang ditemukan
            foreach ($filePaths as $filePath) {
                $fullPath = public_path($filePath);
                if (File::exists($fullPath)) {
                    File::delete($fullPath);
                }
            }
        }

        foreach ($data as $datum) {
            if (file_exists(public_path('thumbnail/' . $datum->thumbnail)) && isset($datum->thumbnail)) {
                unlink(public_path('thumbnail/' . $datum->thumbnail));
            }
        }

        User::findOrFail($user->id)->delete();
        sweetalert()->success('Data User berhasil dihapus!');
        return redirect()->route('users.index');
    }
    public function toggleBlock(User $user)
    {
        if ($user->blocked_at == null) {
            $data = [
                'blocked_at' => now(),
            ];
            $pesan = "user" . $user->name . " telah di blokir";
        } else {
            $data = [
                'blocked_at' => null,
            ];

            $pesan = 'user' . $user->name . " telah di unblokir";
        }
        User::findOrFail($user->id)->update($data);
        sweetalert()->success($pesan);
        return redirect()->back();
    }
}
