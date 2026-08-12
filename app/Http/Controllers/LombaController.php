<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lomba;

class LombaController extends Controller
{
    private function allowed(): array {
        return match(session('admin_user.role')){'admin_putra'=>['Putra'],'admin_putri'=>['Putri'],default=>['Putra','Putri']};
    }
    private function check(Lomba $l): void { if(!in_array($l->gender,$this->allowed())) abort(403); }

    public function index() {
        $allowed=$this->allowed();
        $lombas=Lomba::withCount('pendaftars')->whereIn('gender',$allowed)->orderBy('gender')->orderBy('nama_lomba')->get();
        return view('admin.lomba.index',compact('lombas','allowed'));
    }
    public function create() { return view('admin.lomba.form',['lomba'=>null,'allowed'=>$this->allowed()]); }

    public function store(Request $request) {
        $allowed=$this->allowed();
        $request->validate([
            'nama_lomba'=>'required|string|max:255',
            'gender'=>['required','in:'.implode(',',$allowed)],
            'jenjang'=>'required|in:SMP,SMA,UMUM',
            'tipe'=>'required|in:single,team',

            'min_anggota'=>'required_if:tipe,team|nullable|integer|min:1|max:50',
            'max_anggota'=>'required_if:tipe,team|nullable|integer|min:1|max:50|gte:min_anggota',

            'kuota'=>'nullable|integer|min:1',
            'aktif'=>'nullable|boolean',
            'pemenang'=>'nullable|string|max:500',
            'tampil_pemenang'=>'nullable|boolean',
            'gambar'=>'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'file_guidebook'=>'nullable|file|mimes:pdf|max:10240',
        ]);

        $gambarName = null;
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $ext = $gambar->extension() ?: $gambar->getClientOriginalExtension();
            $gambarName = time() . '_lomba_' . uniqid() . '.' . $ext;
            $gambar->move(storage_path('app/public/lomba_images'), $gambarName);
        }

        $guidebookName = null;
        if ($request->hasFile('file_guidebook')) {
            $guidebook = $request->file('file_guidebook');
            $ext = $guidebook->extension() ?: $guidebook->getClientOriginalExtension();
            $guidebookName = time() . '_guidebook_' . uniqid() . '.' . $ext;
            $guidebook->move(storage_path('app/public/guidebooks'), $guidebookName);
        }

        Lomba::create([
            'nama_lomba'=>$request->nama_lomba,
            'gender'=>$request->gender,
            'jenjang'=>$request->jenjang,
            'tipe'=>$request->tipe,
            'min_anggota'=>$request->tipe==='single'?1:$request->min_anggota,
            'max_anggota'=>$request->tipe==='single'?1:$request->max_anggota,
            'kuota'=>$request->kuota?:null,
            'aktif'=>$request->boolean('aktif',true),
            'pemenang'=>$request->pemenang,
            'tampil_pemenang'=>$request->boolean('tampil_pemenang',false),
            'gambar'=>$gambarName,
            'file_guidebook'=>$guidebookName,
        ]);

        return redirect()->route('admin.lomba.index')->with('success','Lomba ditambahkan.');
    }

    public function edit(Lomba $lomba) { $this->check($lomba); return view('admin.lomba.form',['lomba'=>$lomba,'allowed'=>$this->allowed()]); }

    public function update(Request $request, Lomba $lomba) {
        $this->check($lomba); $allowed=$this->allowed();
        $request->validate([
            'nama_lomba'=>'required|string|max:255',
            'gender'=>['required','in:'.implode(',',$allowed)],
            'jenjang'=>'required|in:SMP,SMA,UMUM',
            'tipe'=>'required|in:single,team',

            'min_anggota'=>'required_if:tipe,team|nullable|integer|min:1|max:50',
            'max_anggota'=>'required_if:tipe,team|nullable|integer|min:1|max:50|gte:min_anggota',

            'kuota'=>'nullable|integer|min:1',
            'aktif'=>'nullable|boolean',
            'pemenang'=>'nullable|string|max:500',
            'tampil_pemenang'=>'nullable|boolean',
            'gambar'=>'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'file_guidebook'=>'nullable|file|mimes:pdf|max:10240',
        ]);

        $gambarName = $lomba->gambar;
        if ($request->hasFile('gambar')) {
            if ($lomba->gambar && file_exists(storage_path('app/public/lomba_images/' . $lomba->gambar))) {
                @unlink(storage_path('app/public/lomba_images/' . $lomba->gambar));
            }
            $gambar = $request->file('gambar');
            $ext = $gambar->extension() ?: $gambar->getClientOriginalExtension();
            $gambarName = time() . '_lomba_' . uniqid() . '.' . $ext;
            $gambar->move(storage_path('app/public/lomba_images'), $gambarName);
        }

        $guidebookName = $lomba->file_guidebook;
        if ($request->hasFile('file_guidebook')) {
            if ($lomba->file_guidebook && file_exists(storage_path('app/public/guidebooks/' . $lomba->file_guidebook))) {
                @unlink(storage_path('app/public/guidebooks/' . $lomba->file_guidebook));
            }
            $guidebook = $request->file('file_guidebook');
            $ext = $guidebook->extension() ?: $guidebook->getClientOriginalExtension();
            $guidebookName = time() . '_guidebook_' . uniqid() . '.' . $ext;
            $guidebook->move(storage_path('app/public/guidebooks'), $guidebookName);
        }

        $lomba->update([
            'nama_lomba'=>$request->nama_lomba,
            'gender'=>$request->gender,
            'jenjang'=>$request->jenjang,
            'tipe'=>$request->tipe,
            'min_anggota'=>$request->tipe==='single'?1:$request->min_anggota,
            'max_anggota'=>$request->tipe==='single'?1:$request->max_anggota,
            'kuota'=>$request->kuota?:null,
            'aktif'=>$request->boolean('aktif',false),
            'pemenang'=>$request->pemenang,
            'tampil_pemenang'=>$request->boolean('tampil_pemenang',false),
            'gambar'=>$gambarName,
            'file_guidebook'=>$guidebookName,
        ]);

        return redirect()->route('admin.lomba.index')->with('success','Lomba diperbarui.');
    }

    public function toggle(Lomba $lomba) { $this->check($lomba); $lomba->update(['aktif'=>!$lomba->aktif]); return back()->with('success','Status lomba diubah.'); }

    public function deleteGambar(Lomba $lomba) {
        $this->check($lomba);
        if (!$lomba->gambar) {
            return back()->with('info','Gambar lomba sudah tidak ada.');
        }

        $path = storage_path('app/public/lomba_images/' . $lomba->gambar);
        if (file_exists($path)) {
            @unlink($path);
        }

        $lomba->update(['gambar' => null]);
        return back()->with('success','Gambar lomba berhasil dihapus.');
    }

    public function deleteGuidebook(Lomba $lomba) {
        $this->check($lomba);
        if (!$lomba->file_guidebook) {
            return back()->with('info','Guidebook lomba sudah tidak ada.');
        }

        $path = storage_path('app/public/guidebooks/' . $lomba->file_guidebook);
        if (file_exists($path)) {
            @unlink($path);
        }

        $lomba->update(['file_guidebook' => null]);
        return back()->with('success','Guidebook lomba berhasil dihapus.');
    }

    public function destroy(Lomba $lomba) {
        $this->check($lomba);
        if($lomba->pendaftars()->count()>0) return back()->with('error','Tidak bisa menghapus lomba karena sudah ada pendaftar.');
        $lomba->delete();
        return redirect()->route('admin.lomba.index')->with('success','Lomba dihapus.');
    }
}
