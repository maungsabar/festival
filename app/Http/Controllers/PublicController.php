<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lomba;
use App\Models\Pendaftar;
use App\Models\AnggotaTim;
use App\Models\Sponsor;
use App\Models\Setting;
use App\Models\Rekening;
use App\Models\Merchandise;
use App\Models\Penjualan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    public function index()
    {
        $lombas   = Lomba::withCount('pendaftars')->where('aktif',1)->orderBy('gender')->orderBy('nama_lomba')->get();
        $pemenang = Lomba::where('tampil_pemenang',true)->whereNotNull('pemenang')->get();
        $sponsors = Sponsor::where('aktif',1)->orderBy('nama')->get();
        return view('public.index', compact('lombas','pemenang','sponsors'));
    }

    public function kategori(string $gender)
    {
        $genderLabel = $gender === 'putra' ? 'Putra' : 'Putri';

        $lombas = Lomba::withCount('pendaftars')
            ->where('gender', $genderLabel)
            ->where('aktif', 1)
            ->orderBy('nama_lomba')
            ->get();

        $pemenang = Lomba::where('gender', $genderLabel)
            ->where('tampil_pemenang', true)
            ->whereNotNull('pemenang')
            ->get();

        // PENTING soal penamaan variabel: AppServiceProvider::View::composer('*', ...)
        // sudah membagikan $heroBgImage / $heroBgColor / $heroBgOverlayOpacity /
        // $pendaftaranStatus ke SEMUA view (termasuk halaman ini) dari setting scope
        // Global — dan composer itu jalan SETELAH data controller di-set, jadi kalau
        // nama variabel di sini sama persis, nilai gender-scoped di bawah ini akan
        // DIAM-DIAM TERTIMPA balik oleh nilai Global (inilah penyebab bug gambar hero
        // Putri sebelumnya selalu menampilkan gambar landing page, bukan punya Putri).
        // Solusinya: semua variabel gender-scoped WAJIB pakai nama berbeda + akhiran
        // "Gender", sama seperti pola $logoTahunan/$taglineTahunan/$countdownGender.
        $heroBgImageGender          = Setting::get('hero_bg_image', null, $genderLabel);
        $heroBgColorGender          = Setting::get('hero_bg_color', $genderLabel === 'Putra' ? '#1d4ed8' : '#db2777', $genderLabel);
        $heroBgOverlayOpacityGender = Setting::get('hero_bg_overlay_opacity', '70', $genderLabel);
        $pendaftaranStatusGender    = Setting::get('status_pendaftaran', 'dibuka', $genderLabel);

        // Teks pendukung status pendaftaran (diatur admin_putra/putri di halaman
        // Pengaturan Kategori) — dipakai sebagai label tombol saat status bukan 'dibuka'.
        $teksStatusGender = match ($pendaftaranStatusGender) {
            'belum'   => Setting::get('teks_belum', 'Pendaftaran Belum Dibuka', $genderLabel),
            'ditutup' => Setting::get('teks_ditutup', 'Pendaftaran Resmi Ditutup', $genderLabel),
            default   => Setting::get('teks_dibuka', 'Pendaftaran Resmi Dibuka', $genderLabel),
        };

        $logoTahunan     = Setting::get('logo_tahunan', null, $genderLabel);
        $judulHero       = Setting::get('judul_hero', null, $genderLabel);
        $taglineTahunan  = Setting::get('tagline_tahunan', null, $genderLabel);
        $countdownGender = Setting::get('countdown', null, $genderLabel);

        // Sponsor bersifat festival-wide (bukan gender-scoped, lihat SponsorController),
        // jadi cukup query yang sama seperti index().
        $sponsors = Sponsor::where('aktif', 1)->orderBy('nama')->get();

        // Kontak WhatsApp KHUSUS kategori ini (diatur admin_putra/admin_putri di
        // halaman Pengaturan Kategori) — berbeda dari $contactPhone/$contactWhatsapp/
        // $contactEmail yang global lewat View::composer.
        $contactWaGender1     = Setting::get('contact_whatsapp_1', null, $genderLabel);
        $contactWaGender1Nama = Setting::get('contact_whatsapp_1_nama', 'HUMAS', $genderLabel);
        $contactWaGender2     = Setting::get('contact_whatsapp_2', null, $genderLabel);
        $contactWaGender2Nama = Setting::get('contact_whatsapp_2_nama', 'BENDAHARA', $genderLabel);

        // Media sosial KHUSUS kategori ini (diatur admin_putra/admin_putri) — beda
        // dari $socialInstagram/$socialTiktok/dst yang global lewat View::composer,
        // ditampilkan di footer halaman kategori (lihat public/kategori.blade.php).
        $socialInstagramGender = Setting::get('social_instagram', null, $genderLabel);
        $socialTiktokGender    = Setting::get('social_tiktok', null, $genderLabel);
        $socialYoutubeGender   = Setting::get('social_youtube', null, $genderLabel);
        $socialFacebookGender  = Setting::get('social_facebook', null, $genderLabel);

        // Menu "Merchandise" di navbar cuma muncul kalau kategori ini punya
        // merchandise aktif — cukup exists(), tidak perlu ambil datanya di sini.
        $hasMerchandiseGender = Merchandise::where('gender', $genderLabel)->where('aktif', true)->exists();

        return view('public.kategori', compact(
            'lombas', 'pemenang', 'genderLabel', 'sponsors',
            'heroBgImageGender', 'heroBgColorGender', 'heroBgOverlayOpacityGender', 'pendaftaranStatusGender', 'teksStatusGender',
            'socialInstagramGender', 'socialTiktokGender', 'socialYoutubeGender', 'socialFacebookGender',
            'logoTahunan', 'judulHero', 'taglineTahunan', 'countdownGender',
            'contactWaGender1', 'contactWaGender1Nama', 'contactWaGender2', 'contactWaGender2Nama',
            'hasMerchandiseGender'
        ));
    }

    public function merchandise(string $gender)
    {
        $genderLabel = $gender === 'putra' ? 'Putra' : 'Putri';

        $merchandises = Merchandise::where('gender', $genderLabel)
            ->where('aktif', true)
            ->orderBy('nama')
            ->get();

        // Kontak WhatsApp + media sosial KHUSUS kategori ini — dipakai untuk tombol
        // "Pesan via WhatsApp" dan footer (footer-nya reuse markup yang sama persis
        // dengan public/kategori.blade.php, jadi butuh variabel yang sama juga).
        // Nomor yang dipakai tombol pemesanan mengikuti pilihan admin di menu
        // Kelola Merchandise > Setup (merchandise_whatsapp_pilihan = '1' atau '2'),
        // dengan fallback ke slot lain kalau slot pilihan ternyata kosong.
        $contactWaGender1      = $this->resolveMerchandiseWhatsApp($genderLabel);
        $socialInstagramGender = Setting::get('social_instagram', null, $genderLabel);
        $socialTiktokGender    = Setting::get('social_tiktok', null, $genderLabel);
        $socialYoutubeGender   = Setting::get('social_youtube', null, $genderLabel);
        $socialFacebookGender  = Setting::get('social_facebook', null, $genderLabel);

        $merchHeroImageGender = Setting::get('merchandise_hero_image', null, $genderLabel);
        $merchJudulGender     = Setting::get('merchandise_judul', null, $genderLabel);
        $merchTaglineGender   = Setting::get('merchandise_tagline', null, $genderLabel);
        $merchRekeningsGender = Rekening::where('gender', $genderLabel)
            ->where('untuk_merchandise', true)
            ->where('aktif', true)
            ->orderBy('nama_bank')
            ->get();

        return view('public.merchandise', compact(
            'merchandises', 'genderLabel', 'contactWaGender1',
            'socialInstagramGender', 'socialTiktokGender', 'socialYoutubeGender', 'socialFacebookGender',
            'merchHeroImageGender', 'merchJudulGender', 'merchTaglineGender', 'merchRekeningsGender'
        ));
    }

    /**
     * Order online merchandise dari halaman publik katalog — buat record Penjualan
     * dan kurangi stok produk sekaligus dalam satu transaction terkunci (lockForUpdate)
     * supaya dua pembeli yang order barang terakhir bersamaan tidak membuat stok minus.
     */
    public function orderMerchandise(Request $request, Merchandise $merchandise)
    {
        $request->validate([
            'nama_pembeli'   => ['required', 'string', 'max:255'],
            'hp_pembeli'     => ['required', 'string', 'max:20'],
            'jumlah'         => ['required', 'integer', 'min:1', 'max:9999'],
            'bukti_transfer' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,pdf', 'max:2048'],
        ]);

        if (!$merchandise->aktif) {
            return back()->withErrors(['jumlah' => 'Produk ini sudah tidak tersedia.'])->withInput();
        }

        $jumlah = (int) $request->jumlah;

        try {
            $penjualan = DB::transaction(function () use ($request, $merchandise, $jumlah) {
                $locked = Merchandise::lockForUpdate()->find($merchandise->id);

                if (!$locked || !$locked->aktif) {
                    throw new \RuntimeException('Produk ini sudah tidak tersedia.');
                }
                if ($locked->stok !== null && $locked->stok < $jumlah) {
                    throw new \RuntimeException('Stok tidak mencukupi. Sisa stok: ' . $locked->stok . '.');
                }

                $file = $request->file('bukti_transfer');
                $ext  = $file->extension() ?: $file->getClientOriginalExtension();
                $name = time() . '_bukti_' . uniqid() . '.' . $ext;
                $file->move(storage_path('app/public/bukti_transfer_merchandise'), $name);

                $created = Penjualan::create([
                    'gender'         => $locked->gender,
                    'merchandise_id' => $locked->id,
                    'nama_pembeli'   => $request->nama_pembeli,
                    'hp_pembeli'     => $request->hp_pembeli,
                    'jumlah'         => $jumlah,
                    'harga_satuan'   => $locked->harga,
                    'total_harga'    => $locked->harga * $jumlah,
                    'bukti_transfer' => $name,
                    'status'         => 'Menunggu Verifikasi',
                    'struk_token'    => Str::random(40),
                ]);

                if ($locked->stok !== null) {
                    $locked->decrement('stok', $jumlah);
                }

                return $created;
            });
        } catch (\RuntimeException $e) {
            return back()->withErrors(['jumlah' => $e->getMessage()])->withInput();
        }

        return redirect()->route('merchandise.order.struk', $penjualan->struk_token)
            ->with('success', 'Pesanan berhasil dikirim! Admin akan segera memverifikasi pembayaran Anda.');
    }

    /**
     * Halaman struk pembelian merchandise — dicari lewat token acak (bukan id
     * berurutan) supaya struk pembeli lain tidak bisa ditebak lewat URL.
     */
    public function strukPenjualan(string $token)
    {
        $penjualan = Penjualan::where('struk_token', $token)->with('merchandise')->firstOrFail();
        $waNomor   = $this->resolveMerchandiseWhatsApp($penjualan->gender);
        return view('public.struk', compact('penjualan', 'waNomor'));
    }

    public function unduhStruk(string $token)
    {
        $penjualan = Penjualan::where('struk_token', $token)->with('merchandise')->firstOrFail();
        $fest      = \App\Models\Setting::get('festival_name', 'Festival Sekolah');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('public.struk-pdf', compact('penjualan', 'fest'))
            ->setPaper('a5', 'portrait');

        $fn = 'struk_' . $penjualan->id . '_' . $penjualan->created_at->format('Ymd_His') . '.pdf';
        return $pdf->download($fn);
    }

    /**
     * Nomor WA merchandise terpilih untuk gender ini — dipakai tombol "Pesan via
     * WhatsApp" di katalog DAN tombol "Konfirmasi Pembayaran" di halaman struk,
     * supaya keduanya selalu konsisten mengarah ke nomor yang sama. Slot dipilih
     * admin di menu Kelola Merchandise > Setup (merchandise_whatsapp_pilihan =
     * '1' atau '2'), dengan fallback ke slot lain kalau slot pilihan kosong.
     */
    private function resolveMerchandiseWhatsApp(string $genderLabel): ?string
    {
        $waPilihan = Setting::get('merchandise_whatsapp_pilihan', '1', $genderLabel);
        $slot1     = Setting::get('contact_whatsapp_1', null, $genderLabel);
        $slot2     = Setting::get('contact_whatsapp_2', null, $genderLabel);
        $chosen    = $waPilihan === '2' ? $slot2 : $slot1;

        return $chosen ?: ($waPilihan === '2' ? $slot1 : $slot2);
    }

    public function showForm(Request $request)
    {
        // Form pendaftaran WAJIB diakses lewat halaman kategori (?gender=putra|putri) —
        // gender lalu dikunci di form, tidak lagi dipilih manual oleh pendaftar, karena
        // tanggal buka/tutup Putra & Putri bisa berbeda (diatur admin_putra/admin_putri).
        $gender = strtolower((string) $request->query('gender'));
        if (!in_array($gender, ['putra', 'putri'], true)) {
            return redirect()->route('home')->with('warning', 'Silakan pilih kategori Putra atau Putri terlebih dahulu.');
        }
        $genderLabel = ucfirst($gender);

        $status = \App\Models\Setting::get('status_pendaftaran', 'dibuka', $genderLabel);
        if ($status !== 'dibuka') {
            $msg = $status === 'ditutup'
                ? \App\Models\Setting::get('teks_ditutup', 'Pendaftaran Resmi Ditutup', $genderLabel)
                : \App\Models\Setting::get('teks_belum', 'Pendaftaran Belum Dibuka', $genderLabel);
            return redirect()->route('lomba.kategori', $gender)
                ->with('warning', 'Pendaftaran kategori ' . $genderLabel . ': ' . $msg);
        }

        // Rekening pembayaran Putra & Putri dikirim sekaligus (dataset kecil) dan
        // ditoggle sisi klien begitu pendaftar memilih gender — lihat public/daftar.blade.php.
        // Filtering per-kategori aslinya terjadi di admin (PembayaranController), di sini
        // publik hanya menerima rekening yang aktif. groupBy (bukan keyBy) karena satu
        // kategori bisa punya lebih dari satu rekening (mis. 2 pilihan bank).
        $rekenings = Rekening::where('aktif', true)->where('untuk_pendaftaran', true)->orderBy('nama_bank')->get()->groupBy('gender');

        // Kalau datang dari tombol "Daftar" di card lomba (?lomba=ID), kunci nama lomba +
        // jenjangnya di form — pendaftar tidak perlu memilih lagi dari daftar. Divalidasi
        // ulang di sini (gender cocok, aktif, belum penuh) meski kartu sumbernya sudah
        // memfilter, supaya URL yang diutak-atik manual tidak bisa mengunci lomba yang
        // sebetulnya tidak valid untuk kategori ini.
        $lockedLomba = null;
        if ($request->filled('lomba')) {
            $candidate = Lomba::withCount('pendaftars')
                ->where('id', $request->query('lomba'))
                ->where('gender', $genderLabel)
                ->where('aktif', 1)
                ->first();
            if ($candidate && !$candidate->isFull()) {
                $lockedLomba = $candidate;
            }
        }

        return view('public.daftar', compact('rekenings', 'genderLabel', 'lockedLomba'));
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
        // SECURITY: validasi status berdasarkan gender yang BENAR-BENAR disubmit (bukan status
        // global, dan bukan cuma percaya field "gender" terkunci di UI — field hidden tetap
        // bisa dimanipulasi lewat request langsung), supaya kategori yang belum/sudah ditutup
        // tidak bisa ditembus meski form berhasil dibuka sebelumnya.
        $submittedGender = in_array($request->input('gender'), ['Putra', 'Putri'], true) ? $request->input('gender') : null;
        $status = $submittedGender
            ? \App\Models\Setting::get('status_pendaftaran', 'dibuka', $submittedGender)
            : 'ditutup';
        if ($status !== 'dibuka') {
            return back()->withErrors(['id_lomba' => 'Pendaftaran kategori ' . ($submittedGender ?? '') . ' saat ini sedang tidak dibuka.'])->withInput();
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
