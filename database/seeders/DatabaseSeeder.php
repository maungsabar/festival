<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan sementara foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('users')->truncate();
        DB::table('lombas')->truncate();
        DB::table('pendaftars')->truncate();

        // Aktifkan kembali foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('users')->insert([
            ['username'=>'superadmin','password'=>Hash::make('super123'),'role'=>'superadmin','created_at'=>now(),'updated_at'=>now()],
            ['username'=>'admin_putra','password'=>Hash::make('putra123'),'role'=>'admin_putra','created_at'=>now(),'updated_at'=>now()],
            ['username'=>'admin_putri','password'=>Hash::make('putri123'),'role'=>'admin_putri','created_at'=>now(),'updated_at'=>now()],
        ]);

        DB::table('lombas')->insert([
            ['nama_lomba'=>'Futsal','gender'=>'Putra','tipe'=>'team','min_anggota'=>5,'max_anggota'=>7,'kuota'=>8,'aktif'=>1,'pemenang'=>null,'tampil_pemenang'=>0,'created_at'=>now(),'updated_at'=>now()],
            ['nama_lomba'=>'Bulu Tangkis','gender'=>'Putra','tipe'=>'single','min_anggota'=>1,'max_anggota'=>1,'kuota'=>16,'aktif'=>1,'pemenang'=>null,'tampil_pemenang'=>0,'created_at'=>now(),'updated_at'=>now()],
            ['nama_lomba'=>'Pencak Silat','gender'=>'Putra','tipe'=>'single','min_anggota'=>1,'max_anggota'=>1,'kuota'=>null,'aktif'=>1,'pemenang'=>null,'tampil_pemenang'=>0,'created_at'=>now(),'updated_at'=>now()],
            ['nama_lomba'=>'Voli','gender'=>'Putri','tipe'=>'team','min_anggota'=>6,'max_anggota'=>8,'kuota'=>8,'aktif'=>1,'pemenang'=>null,'tampil_pemenang'=>0,'created_at'=>now(),'updated_at'=>now()],
            ['nama_lomba'=>'Tari Tradisional','gender'=>'Putri','tipe'=>'team','min_anggota'=>3,'max_anggota'=>5,'kuota'=>null,'aktif'=>1,'pemenang'=>null,'tampil_pemenang'=>0,'created_at'=>now(),'updated_at'=>now()],
            ['nama_lomba'=>'Renang','gender'=>'Putri','tipe'=>'single','min_anggota'=>1,'max_anggota'=>1,'kuota'=>null,'aktif'=>1,'pemenang'=>null,'tampil_pemenang'=>0,'created_at'=>now(),'updated_at'=>now()],
        ]);

        $futsal  = DB::table('lombas')->where('nama_lomba','Futsal')->value('id');
        $bulu    = DB::table('lombas')->where('nama_lomba','Bulu Tangkis')->value('id');
        $voli    = DB::table('lombas')->where('nama_lomba','Voli')->value('id');
        $tari    = DB::table('lombas')->where('nama_lomba','Tari Tradisional')->value('id');

        DB::table('pendaftars')->insert([
            ['nisn'=>'1234567890','nama'=>'Ahmad Fauzan','gender'=>'Putra','nama_sekolah'=>'SMA Negeri 1 Jakarta','alamat_sekolah'=>'Jl. Merdeka No.1, Jakarta','nama_pendamping'=>'Bapak Hadi','hp_pendamping'=>'081234567890','hp_peserta'=>'081234567891','link_twibbon'=>null,'nama_tim'=>'Garuda FC','id_lomba'=>$futsal,'file_kartu_siswa'=>'dummy_kartu_putra1.jpg','file_bukti_pembayaran'=>'dummy_bayar_putra1.jpg','status_verifikasi'=>'Terverifikasi','created_at'=>now(),'updated_at'=>now()],
            ['nisn'=>'1234567891','nama'=>'Budi Santoso','gender'=>'Putra','nama_sekolah'=>'SMA Negeri 2 Bandung','alamat_sekolah'=>'Jl. Diponegoro No.5, Bandung','nama_pendamping'=>'Ibu Sari','hp_pendamping'=>'081234567892','hp_peserta'=>'081234567893','link_twibbon'=>null,'nama_tim'=>null,'id_lomba'=>$bulu,'file_kartu_siswa'=>'dummy_kartu_putra2.jpg','file_bukti_pembayaran'=>'dummy_bayar_putra2.jpg','status_verifikasi'=>'Belum','created_at'=>now(),'updated_at'=>now()],
            ['nisn'=>'1234567892','nama'=>'Citra Dewi','gender'=>'Putri','nama_sekolah'=>'SMA Negeri 3 Surabaya','alamat_sekolah'=>'Jl. Ahmad Yani No.10, Surabaya','nama_pendamping'=>'Ibu Wati','hp_pendamping'=>'081234567894','hp_peserta'=>'081234567895','link_twibbon'=>null,'nama_tim'=>'Putri Nusantara','id_lomba'=>$voli,'file_kartu_siswa'=>'dummy_kartu_putri1.jpg','file_bukti_pembayaran'=>'dummy_bayar_putri1.jpg','status_verifikasi'=>'Terverifikasi','created_at'=>now(),'updated_at'=>now()],
            ['nisn'=>'1234567893','nama'=>'Dina Puspita','gender'=>'Putri','nama_sekolah'=>'SMA Negeri 4 Yogyakarta','alamat_sekolah'=>'Jl. Malioboro No.3, Yogyakarta','nama_pendamping'=>'Bapak Joko','hp_pendamping'=>'081234567896','hp_peserta'=>'081234567897','link_twibbon'=>null,'nama_tim'=>'Mekar Sari','id_lomba'=>$tari,'file_kartu_siswa'=>'dummy_kartu_putri2.jpg','file_bukti_pembayaran'=>'dummy_bayar_putri2.jpg','status_verifikasi'=>'Belum','created_at'=>now(),'updated_at'=>now()],
        ]);

        DB::table('settings')->upsert([
            ['key'=>'festival_name','value'=>'Festival Sekolah','created_at'=>now(),'updated_at'=>now()],
            ['key'=>'festival_year','value'=>date('Y'),'created_at'=>now(),'updated_at'=>now()],
        ], ['key'], ['value','updated_at']);

        $this->command->info('✅ Seeder selesai.');
    }
}