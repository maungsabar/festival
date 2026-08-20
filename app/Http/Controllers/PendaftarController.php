<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Pendaftar;
use App\Http\Controllers\Concerns\ScopesByAdminGender;

class PendaftarController extends Controller
{
    use ScopesByAdminGender;

    // Otorisasi per-record (IDOR) sudah ditangani middleware `gender.access`
    // pada route group admin (lihat routes/web.php). allowedGenders() di sini
    // dipakai untuk scope query listing & export.

    public function index(Request $request)
    {
        $allowed=$this->allowedGenders(); $role=session('admin_user.role');
        $q=Pendaftar::with('lomba')->whereIn('gender',$allowed);
        if($role==='superadmin'&&$request->filled('gender')&&in_array($request->gender,['Putra','Putri'])) $q->where('gender',$request->gender);
        if($request->filled('search')){ $s=$request->search; $q->where(fn($qq)=>$qq->where('nama','like',"%$s%")->orWhere('nisn','like',"%$s%")->orWhere('nama_sekolah','like',"%$s%")); }
        if($request->filled('status')) $q->where('status_verifikasi',$request->status);
        $pendaftars=$q->orderByDesc('created_at')->paginate(20)->withQueryString();
        return view('admin.pendaftar.index',compact('pendaftars','allowed','role'));
    }

    public function show(Pendaftar $pendaftar)
    {
        $pendaftar->load(['lomba','anggotaTim']);
        return view('admin.pendaftar.show',compact('pendaftar'));
    }

    public function verifikasi(Request $request, Pendaftar $pendaftar)
    {
        $request->validate(['status'=>'required|in:Belum,Terverifikasi,Ditolak']);
        $pendaftar->update(['status_verifikasi'=>$request->status]);
        return back()->with('success','Status diperbarui menjadi "'.$request->status.'".');
    }

    public function destroy(Pendaftar $pendaftar)
    {
        foreach(['kartu_siswa','bukti_pembayaran'] as $f){ $field='file_'.$f; $p=storage_path("app/public/{$f}/{$pendaftar->$field}"); if(file_exists($p)&&!str_starts_with($pendaftar->$field,'dummy_')) unlink($p); }
        $pendaftar->delete();
        return redirect()->route('admin.pendaftar.index')->with('success','Pendaftar dihapus.');
    }

    public function export(Request $request)
    {
        $allowed=$this->allowedGenders(); $role=session('admin_user.role'); $format=$request->get('format','csv');
        $q=Pendaftar::with(['lomba','anggotaTim'])->whereIn('gender',$allowed);
        if($role==='superadmin'&&$request->filled('gender')&&in_array($request->gender,['Putra','Putri'])) $q->where('gender',$request->gender);
        $rows=$q->orderBy('gender')->orderBy('created_at')->get();
        $suffix=$request->filled('gender')?'_'.strtolower($request->gender):'_semua';
        $ts=now()->format('Ymd_His');
        return match($format){ 'excel'=>$this->toExcel($rows,$suffix,$ts),'pdf'=>$this->toPdf($rows,$suffix,$ts),default=>$this->toCsv($rows,$suffix,$ts) };
    }

    private function toCsv($rows,$suffix,$ts){
        $fn="pendaftar{$suffix}_{$ts}.csv";
        return response()->stream(function() use($rows){
            $f=fopen('php://output','w'); fputs($f,"\xEF\xBB\xBF");
            fputcsv($f,['NISN','Nama','Gender','Nama Tim','Sekolah','Lomba','Tipe','HP Peserta','HP Pendamping','Nama Pendamping','Link Twibbon','Status','Tanggal']);
            foreach($rows as $p) fputcsv($f,[$p->nisn,$p->nama,$p->gender,$p->nama_tim??'-',$p->nama_sekolah,$p->lomba?->nama_lomba??'-',$p->lomba?->tipe??'-',$p->hp_peserta??'-',$p->hp_pendamping??'-',$p->nama_pendamping??'-',$p->link_twibbon??'-',$p->status_verifikasi,$p->created_at->format('Y-m-d H:i')]);
            fclose($f);
        },200,['Content-Type'=>'text/csv','Content-Disposition'=>"attachment; filename=\"{$fn}\""]);
    }

    private function toExcel($rows,$suffix,$ts){
        $fn="pendaftar{$suffix}_{$ts}.xls";
        $h='<html><head><meta charset="UTF-8"></head><body><table border="1"><tr><th>NISN</th><th>Nama</th><th>Gender</th><th>Nama Tim</th><th>Sekolah</th><th>Lomba</th><th>Tipe</th><th>HP Peserta</th><th>HP Pendamping</th><th>Pendamping</th><th>Twibbon</th><th>Status</th><th>Tanggal</th></tr>';
        foreach($rows as $p){ $h.='<tr>'; foreach([$p->nisn,$p->nama,$p->gender,$p->nama_tim??'-',$p->nama_sekolah,$p->lomba?->nama_lomba??'-',$p->lomba?->tipe??'-',$p->hp_peserta??'-',$p->hp_pendamping??'-',$p->nama_pendamping??'-',$p->link_twibbon??'-',$p->status_verifikasi,$p->created_at->format('Y-m-d H:i')] as $v) $h.='<td>'.htmlspecialchars((string)$v).'</td>'; $h.='</tr>'; }
        $h.='</table></body></html>';
        return Response::make($h,200,['Content-Type'=>'application/vnd.ms-excel','Content-Disposition'=>"attachment; filename=\"{$fn}\""]);
    }

    private function toPdf($rows,$suffix,$ts){
        $fn="pendaftar{$suffix}_{$ts}.html";
        $fest=\App\Models\Setting::get('festival_name','Festival Sekolah');
        $h='<!DOCTYPE html><html><head><meta charset="UTF-8"><link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap" rel="stylesheet"><style>body{font-family:"Montserrat",sans-serif;font-size:11px}h2{text-align:center}table{width:100%;border-collapse:collapse}th{background:#1d4ed8;color:#fff;padding:5px 7px;font-size:10px}td{padding:4px 7px;border-bottom:1px solid #e5e7eb;font-size:10px}tr:nth-child(even) td{background:#f8fafc}.v{color:#065f46;background:#d1fae5;padding:1px 5px;border-radius:8px}.b{color:#92400e;background:#fef3c7;padding:1px 5px;border-radius:8px}.t{color:#991b1b;background:#fee2e2;padding:1px 5px;border-radius:8px}</style></head><body>';
        $h.="<h2>Data Pendaftar — {$fest}</h2><p style='text-align:center;color:#666;font-size:10px'>Dicetak ".now()->format('d M Y, H:i').' &bull; '.$rows->count().' peserta</p>';
        $h.='<table><thead><tr><th>#</th><th>NISN</th><th>Nama</th><th>Gender</th><th>Sekolah</th><th>Lomba</th><th>HP Peserta</th><th>Status</th><th>Tanggal</th></tr></thead><tbody>';
        foreach($rows as $i=>$p){ $badge=match($p->status_verifikasi){'Terverifikasi'=>'<span class="v">Terverifikasi</span>','Ditolak'=>'<span class="t">Ditolak</span>',default=>'<span class="b">Belum</span>'}; $h.='<tr><td>'.($i+1).'</td><td>'.$p->nisn.'</td><td>'.htmlspecialchars($p->nama).'</td><td>'.$p->gender.'</td><td>'.htmlspecialchars($p->nama_sekolah).'</td><td>'.htmlspecialchars($p->lomba?->nama_lomba??'-').'</td><td>'.htmlspecialchars($p->hp_peserta??'-').'</td><td>'.$badge.'</td><td>'.$p->created_at->format('d/m/Y').'</td></tr>'; }
        $h.='</tbody></table></body></html>';
        return Response::make($h,200,['Content-Type'=>'text/html; charset=UTF-8','Content-Disposition'=>"attachment; filename=\"{$fn}\""]);
    }

    public function serveFile(string $folder, string $filename)
    {
        // SECURITY: Whitelist allowed folders only
        $allowedFolders = ['kartu_siswa', 'bukti_pembayaran'];
        if (!in_array($folder, $allowedFolders, true)) abort(403);

        // SECURITY: Strip any path traversal sequences (e.g. ../../)
        $filename = basename($filename);
        if (empty($filename) || $filename === '.' || $filename === '..') abort(403);

        // SECURITY: Whitelist allowed file extensions
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','gif','pdf','webp'], true)) abort(403);

        // SECURITY: Verify the resolved path stays within the expected directory
        $baseDir  = realpath(storage_path("app/public/{$folder}"));
        $fullPath = storage_path("app/public/{$folder}/{$filename}");
        $realPath = realpath($fullPath);

        if (!$realPath || !$baseDir || !str_starts_with($realPath, $baseDir . DIRECTORY_SEPARATOR)) {
            abort(403, 'Akses ditolak.');
        }

        if (!file_exists($realPath)) abort(404);
        return response()->file($realPath);
    }
}
