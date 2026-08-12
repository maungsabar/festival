<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lomba;
use App\Models\Pendaftar;
use App\Models\AnggotaTim;
use App\Models\Sponsor;

class PublicController extends Controller
{
    public function index()
    {
        $lombas   = Lomba::withCount('pendaftars')->where('aktif',1)->orderBy('gender')->orderBy('nama_lomba')->get();
        $pemenang = Lomba::where('tampil_pemenang',true)->whereNotNull('pemenang')->get();
        $sponsors = Sponsor::where('aktif',1)->orderBy('nama')->get();
        return view('public.index', compact('lombas','pemenang','sponsors'));
    }

    public function showForm()
    {
        $status = \App\Models\Setting::get('pendaftaran_status', 'dibuka');
        if ($status !== 'dibuka') {
            $msg = $status === 'ditutup'
                ? \App\Models\Setting::get('pendaftaran_ditutup_teks', 'Pendaftaran Resmi Ditutup')
                : \App\Models\Setting::get('pendaftaran_belum_teks', 'Pendaftaran Belum Dibuka');
            return redirect()->route('home')->with('warning', $msg);
        }
        return view('public.daftar');
    }

    public function getLomba(Request $request)
    {
        $gender = $request->query('gender');
        if (!in_array($gender,['Putra','Putri'])) return response()->json([]);
        return response()->json(
            Lomba::withCount('pendaftars') // PERF: eager load count to avoid N+1 queries
            ->where('gender',$gender)->where('aktif',1)->orderBy('nama_lomba')->get()
            ->map(fn($l) => [
                'id'          => $l->id,
                'nama_lomba'  => $l->nama_lomba,
                'tipe'        => $l->tipe,
                'min_anggota' => $l->min_anggota,
                'max_anggota' => $l->max_anggota,
                'kuota'       => $l->kuota,
                'sisa_kuota'  => $l->sisaKuota(),
                'is_full'     => $l->isFull(),
                'jenjang'     => $l->jenjang,
            ])
        );
    }

    public function store(Request $request)
    {
        $status = \App\Models\Setting::get('pendaftaran_status', 'dibuka');
        if ($status !== 'dibuka') {
            return back()->withErrors(['id_lomba' => 'Pendaftaran saat ini sedang tidak dibuka.'])->withInput();
        }

        $lomba  = $request->id_lomba ? Lomba::find($request->id_lomba) : null;
        $isTeam = $lomba && $lomba->tipe === 'team';
        $maxA   = $lomba ? $lomba->max_anggota : 1;

        $rules = [
            'nisn'             => ['required','digits:10','unique:pendaftars,nisn'],
            'nama'             => ['required','string','max:255'],
            'gender'           => ['required','in:Putra,Putri'],
            'nama_sekolah'     => ['required','string','max:255'],
            'alamat_sekolah'   => ['required','string'],
            'id_lomba'         => ['required','exists:lombas,id'],
            'hp_peserta'       => ['required','string','max:20'],
            'nama_pendamping'  => ['required','string','max:255'],
            'hp_pendamping'    => ['required','string','max:20'],
            'link_twibbon'     => ['nullable','url','max:500'],
            'kartu_siswa'      => ['required','file','mimes:jpg,jpeg,png,gif,pdf','max:2048'],
            'bukti_pembayaran' => ['required','file','mimes:jpg,jpeg,png,gif,pdf','max:2048'],
        ];
        if ($isTeam) {
            $rules['nama_tim'] = ['required','string','max:255'];
            $minA = $lomba ? ($lomba->min_anggota ?? 1) : 1;
            for ($i = 2; $i <= $maxA; $i++) {
                $isReq = ($i <= $minA) ? 'required' : 'nullable';
                $rules["anggota.{$i}.nisn"] = ['nullable','digits:10'];
                $rules["anggota.{$i}.nama"] = [$isReq,'string','max:255'];
                $rules["anggota.{$i}.kelas"]= ['nullable','string','max:50'];
            }
        }

        $request->validate($rules,[
            'nisn.required'=>'NISN wajib diisi.','nisn.digits'=>'NISN harus 10 digit.','nisn.unique'=>'NISN sudah terdaftar.',
            'nama.required'=>'Nama wajib diisi.','gender.required'=>'Jenis kelamin wajib dipilih.',
            'nama_sekolah.required'=>'Nama sekolah wajib.','alamat_sekolah.required'=>'Alamat sekolah wajib.',
            'id_lomba.required'=>'Pilih perlombaan.','hp_peserta.required'=>'HP peserta wajib.',
            'nama_pendamping.required'=>'Nama pendamping wajib.','hp_pendamping.required'=>'HP pendamping wajib.',
            'link_twibbon.url'=>'Link twibbon harus berupa URL.',
            'kartu_siswa.required'=>'Kartu siswa wajib.','bukti_pembayaran.required'=>'Bukti pembayaran wajib.',
            'nama_tim.required'=>'Nama tim wajib untuk lomba beregu.',
        ]);

        if ($lomba && $lomba->isFull()) {
            return back()->withErrors(['id_lomba'=>'Kuota lomba '.$lomba->nama_lomba.' sudah penuh.'])->withInput();
        }

        $ts       = now()->format('YmdHis');
        $kartu    = $request->file('kartu_siswa');
        $kExt     = $kartu->extension() ?: $kartu->getClientOriginalExtension();
        $kName    = $ts.'_'.$request->nisn.'_kartu.'.$kExt;
        $kartu->move(storage_path('app/public/kartu_siswa'),$kName);

        $bayar    = $request->file('bukti_pembayaran');
        $bExt     = $bayar->extension() ?: $bayar->getClientOriginalExtension();
        $bName    = $ts.'_'.$request->nisn.'_bukti.'.$bExt;
        $bayar->move(storage_path('app/public/bukti_pembayaran'),$bName);

        $pendaftar = Pendaftar::create([
            'nisn'=>$request->nisn,'nama'=>$request->nama,'gender'=>$request->gender,
            'nama_sekolah'=>$request->nama_sekolah,'alamat_sekolah'=>$request->alamat_sekolah,
            'nama_pendamping'=>$request->nama_pendamping,'hp_pendamping'=>$request->hp_pendamping,
            'hp_peserta'=>$request->hp_peserta,'link_twibbon'=>$request->link_twibbon,
            'nama_tim'=>$isTeam?$request->nama_tim:null,'id_lomba'=>$request->id_lomba,
            'file_kartu_siswa'=>$kName,'file_bukti_pembayaran'=>$bName,'status_verifikasi'=>'Belum',
        ]);

        if ($isTeam && $request->has('anggota')) {
            foreach ($request->anggota as $urutan => $data) {
                if (empty($data['nama'])) continue;
                AnggotaTim::create(['pendaftar_id'=>$pendaftar->id,'urutan'=>$urutan,'nisn'=>$data['nisn']??'','nama'=>$data['nama'],'kelas'=>$data['kelas']??null]);
            }
        }

        if ($lomba && $lomba->isFull()) $lomba->update(['aktif'=>false]);

        return redirect()->route('daftar.sukses',['nisn'=>$request->nisn]);
    }

    public function sukses(Request $request)
    {
        $nisn = $request->query('nisn');
        return view('public.sukses', compact('nisn'));
    }
}
