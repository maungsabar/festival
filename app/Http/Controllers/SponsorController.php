<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sponsor;

class SponsorController extends Controller
{
    private function checkAccess(): void
    {
        if (session('admin_user.role') !== 'superadmin') {
            abort(403);
        }
    }

    public function index()
    {
        $this->checkAccess();
        $sponsors = Sponsor::orderBy('nama')->get();
        return view('admin.sponsor.index', compact('sponsors'));
    }

    public function create()
    {
        $this->checkAccess();
        return view('admin.sponsor.form', ['sponsor' => null]);
    }

    public function store(Request $request)
    {
        $this->checkAccess();
        $request->validate([
            'nama' => 'required|string|max:255',
            'logo' => 'required|image|mimes:png,jpg,jpeg,webp,svg|max:2048',
            'link' => 'nullable|url|max:500',
            'aktif' => 'nullable|boolean',
        ]);

        $logoName = null;
        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            $logoName = time() . '_sponsor_' . uniqid() . '.' . $logoFile->getClientOriginalExtension();
            $logoFile->move(storage_path('app/public/sponsors'), $logoName);
        }

        Sponsor::create([
            'nama' => $request->nama,
            'logo' => $logoName,
            'link' => $request->link,
            'aktif' => $request->boolean('aktif', true),
        ]);

        return redirect()->route('admin.sponsor.index')->with('success', 'Sponsor berhasil ditambahkan.');
    }

    public function edit(Sponsor $sponsor)
    {
        $this->checkAccess();
        return view('admin.sponsor.form', compact('sponsor'));
    }

    public function update(Request $request, Sponsor $sponsor)
    {
        $this->checkAccess();
        $request->validate([
            'nama' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp,svg|max:2048',
            'link' => 'nullable|url|max:500',
            'aktif' => 'nullable|boolean',
        ]);

        $logoName = $sponsor->logo;
        if ($request->hasFile('logo')) {
            if ($sponsor->logo && file_exists(storage_path('app/public/sponsors/' . $sponsor->logo))) {
                @unlink(storage_path('app/public/sponsors/' . $sponsor->logo));
            }
            $logoFile = $request->file('logo');
            $logoName = time() . '_sponsor_' . uniqid() . '.' . $logoFile->getClientOriginalExtension();
            $logoFile->move(storage_path('app/public/sponsors'), $logoName);
        }

        $sponsor->update([
            'nama' => $request->nama,
            'logo' => $logoName,
            'link' => $request->link,
            'aktif' => $request->boolean('aktif', false),
        ]);

        return redirect()->route('admin.sponsor.index')->with('success', 'Sponsor berhasil diperbarui.');
    }

    public function destroy(Sponsor $sponsor)
    {
        $this->checkAccess();
        if ($sponsor->logo && file_exists(storage_path('app/public/sponsors/' . $sponsor->logo))) {
            @unlink(storage_path('app/public/sponsors/' . $sponsor->logo));
        }
        $sponsor->delete();
        return redirect()->route('admin.sponsor.index')->with('success', 'Sponsor berhasil dihapus.');
    }
}
