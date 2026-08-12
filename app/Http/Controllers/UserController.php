<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    private function guard(): void
    {
        if (session('admin_user.role') !== 'superadmin') abort(403, 'Akses ditolak.');
    }

    public function index()
    {
        $this->guard();
        $users = User::orderBy('role')->orderBy('username')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $this->guard();
        return view('admin.users.form', ['user' => null]);
    }

    public function store(Request $request)
    {
        $this->guard();
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username|alpha_dash',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:superadmin,admin_putra,admin_putri',
        ], [
            'username.required'   => 'Username wajib diisi.',
            'username.unique'     => 'Username sudah digunakan.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, dan underscore.',
            'password.required'   => 'Password wajib diisi.',
            'password.min'        => 'Password minimal 6 karakter.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
            'role.required'       => 'Role wajib dipilih.',
        ]);
        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);
        return redirect()->route('admin.users.index')->with('success','User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $this->guard();
        return view('admin.users.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->guard();
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username,'.$user->id.'|alpha_dash',
            'password' => 'nullable|string|min:8|confirmed',
            'role'     => 'required|in:superadmin,admin_putra,admin_putri',
        ], [
            'username.unique'    => 'Username sudah digunakan.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);
        $data = ['username' => $request->username, 'role' => $request->role];
        if ($request->filled('password')) $data['password'] = Hash::make($request->password);
        $user->update($data);
        return redirect()->route('admin.users.index')->with('success','User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $this->guard();
        if ($user->id === session('admin_user.id')) {
            return back()->with('error','Tidak bisa menghapus akun Anda sendiri yang sedang aktif.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success','User berhasil dihapus.');
    }
}
