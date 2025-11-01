<?php

namespace Database\Seeders\Wilayah;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class KelurahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! Schema::hasTable('kelurahans')) {
            $this->command->error('Tabel "kelurahans" tidak ditemukan. Jalankan migrasi dulu.');
            return;
        }

        // Mapping nama kecamatan ke ID (kode BPS)
        $kecamatanMap = [
            'SUKOLILO'      => 331801,
            'KAYEN'         => 331802,
            'TAMBAKROMO'    => 331803,
            'WINONG'        => 331804,
            'PUCAKWANGI'    => 331805,
            'JAKEN'         => 331806,
            'BATANGAN'      => 331807,
            'JUWANA'        => 331808,
            'JAKENAN'       => 331809,
            'PATI'          => 331810,
            'GABUS'         => 331811,
            'MARGOREJO'     => 331812,
            'GEMBONG'       => 331813,
            'TLOGOWUNGU'    => 331814,
            'WEDARIJAKSA'   => 331815,
            'MARGOYOSO'     => 331816,
            'GUNUNGWUNGKAL' => 331817,
            'CLUWAK'        => 331818,
            'TAYU'          => 331819,
            'DUKUHSETI'     => 331820,
            'TRANGKIL'      => 331821,
        ];

        // Data lengkap (406 desa/kelurahan) — sesuai input kamu
        $data = [
            'BATANGAN' => ['Batursari','Bulumulyo','Bumimulyo','Gajahkumpul','Gunungsari','Jembangan','Kedalon','Ketitangwetan','Klayusiwalan','Kuniran','Lengkong','Mangunlegi','Ngening','Pecangaan','Raci','Sukoagung','Tlogomojo','Tompomulyo'],
            'CLUWAK' => ['Bleber','Gerit','Gesengan','Karangsari','Medani','Mojo','Ngablak','Ngawen','Payak','Plaosan','Sentul','Sirahan','Sumur'],
            'DUKUHSETI' => ['Alasdowo','Bakalan','Banyutowo','Dukuhseti','Dumpil','Grogolan','Kembang','Kenanti','Ngagel','Puncel','Tegalombo','Wedusan'],
            'GABUS' => ['Babalan','Banjarsari','Bogotanjung','Gabus','Gebang','Gempolsari','Karaban','Kosekan','Koripandriyo','Koryokalangan','Mintobasuki','Mojolawaran','Pantirejo','Penanggungan','Plumbungan','Sambirejo','Soko','Sugihrejo','Sunggingwarno','Tambahmulyo','Tanjang','Tanjunganom','Tlogoayu','Wuwur'],
            'GEMBONG' => ['Bageng','Bermi','Gembong','Kedungbulus','Ketanggan','Klakah Kasian','Plukaran','Pohgading','Semirejo','Sitiluhur','Wonosekar'],
            'GUNUNGWUNGKAL' => ['Bancak','Gadu','Gajihan','Giling','Gulangpungge','Gunungwungkal','Jembulwunut','Jepalo','Jrahi','Ngetuk','Perdopo','Pesagen','Sampok','Sidomulyo','Sumberejo'],
            'JAKEN' => ['Aromanis','Boto','Kebonturi','Lundo','Manjang','Mantingan','Mojolampir','Mojoluhur','Ronggo','Sidoluhur','Sidomukti','Srikaton','Sriwedari','Sukorukun','Sumberagung','Sumberan','Sumberarum','Sumberrejo','Tamansari','Tegalarum','Trikoyo'],
            'JAKENAN' => ['Bungasrejo','Dukuhmulyo','Glonggong','Jakenan','Jatisari','Kalimulyo','Karangrejo Lor','Karangrowo','Kedungmulyo','Mantingan Tengah','Ngastorejo','Plosojenar','Puluhan Tengah','Sembaturagung','Sendangsoko','Sidoarum','Sidomulyo','Sonorejo','Tambahmulyo','Tanjungsari','Tlogorejo','Tondokerto','Tondomulyo'],
            'JUWANA' => ['Agungmulyo','Bajomulyo','Bakarankulon','Bakaranwetan','Bendar','Bringin','Bumirejo','Doropayung','Dukutalit','Gadingrejo','Genengmulyo','Growongkidul','Growonglor','Jepuro','Karang','Karangrejo','Kauman','Kebonsawahan','Kedungpancing','Ketip','Kudukeras','Langenharjo','Margomulyo','Mintomulyo','Pajeksan','Pekuwon','Sejomulyo','Tluwah','Trimulyo'],
            'KAYEN' => ['Beketel','Boloagung','Brati','Durensawit','Jatiroto','Jimbaran','Kayen','Pasuruhan','Pesagi','Purwokerto','Rogomulyo','Slungkep','Srikaton','Sumbersari','Sundoluhur','Talun','Trimulyo'],
            'MARGOREJO' => ['Badegan','Banyuurip','Bumirejo','Dadirejo','Jambean Kidul','Jimbaran','Langenharjo','Langse','Margorejo','Metaraman','Muktiharjo','Ngawen','Pegandan','Penambuhan','Sokokulon','Sukobubuk','Sukoharjo','Wangunrejo'],
            'MARGOYOSO' => ['Bulumanis Kidul','Bulumanis Lor','Cebolek Kidul','Kajen','Kertomulyo','Langgenharjo','Margotuhu Kidul','Margoyoso','Ngemplak Kidul','Ngemplak Lor','Pangkalan','Pohijo','Purwodadi','Purworejo','Sekarjalak','Semerak','Sidomukti','Soneyan','Tanjungrejo','Tegalarum','Tunjungrejo','Waturoyo'],
            'PATI' => ['Blaru','Dengkek','Gajahmati','Geritan','Kutoharjo','Mulyoharjo','Mustokoharjo','Ngarus','Ngepungrejo','Panjunan','Payang','Plangitan','Puri','Purworejo','Sarirejo','Semampir','Sidoharjo','Sidokerto','Sinoman','Sugiharjo','Tambaharjo','Tambahsari','Widorokandang','Winong','Pati Wetan','Pati Kidul','Pati Lor','Parenggan','Kalidoro'],
            'PUCAKWANGI' => ['Bodeh','Grogolsari','Jetak','Karangrejo','Karangwotan','Kepohkencono','Kletek','Lumbungmas','Mencon','Mojoagung','Pelemgede','Plosorejo','Pucakwangi','Sitimulyo','Sokopuluhan','Tanjungsekar','Tegalwero','Terteg','Triguno','Wateshaji'],
            'SUKOLILO' => ['Baleadi','Baturejo','Cengkalsewu','Gadudero','Kasiyan','Kedumulyo','Kedungwinong','Kuwawur','Pakem','Porang Paring','Prawoto','Sukolilo','Sumbersoko','Tompegunung','Wegil','Wotan'],
            'TAMBAKROMO' => ['Angkatan Kidul','Angkatan Lor','Karangawen','Karangmulyo','Karangwono','Keben','Kedalingan','Larangan','Maitan','Mangunrekso','Mojomulyo','Pakis','Sinomwidodo','Sitirejo','Tambahagung','Tambaharjo','Tambakromo','Wukirsari'],
            'TAYU' => ['Bendokaton Kidul','Bulungan','Dororejo','Jepat Kidul','Jepat Lor','Kalikalong','Keboromo','Kedungbang','Kedungsari','Luwang','Margomulyo','Pakis','Pondowan','Pundenrejo','Purwokerto','Sambiroto','Sendangrejo','Tayukulon','Tayuwetan','Tendas','Tunggulsari'],
            'TLOGOWUNGU' => ['Cabak','Gunungsari','Guwo','Klumpit','Lahar','Purwosari','Regaloh','Sambirejo','Sumbermulyo','Suwatu','Tajungsari','Tamansari','Tlogorejo','Tlogosari','Wonorejo'],
            'TRANGKIL' => ['Asempapan','Guyangan','Kadilangu','Kajar','Karanglegi','Karangwage','Kertomulyo','Ketanen','Krandan','Mojoagung','Pasucen','Rejoagung','Sambilawang','Tegalharjo','Tlutup','Trangkil'],
            'WEDARIJAKSA' => ['Bangsalrejo','Bumiayu','Jatimulyo','Jetak','Jontro','Kepoh','Margorejo','Ngurenrejo','Ngurensiti','Pagerharjo','Panggungroyom','Sidoharjo','Sukoharjo','Suwaduk','Tawangharjo','Tlogoharum','Tluwuk','Wedarijaksa'],
            'WINONG' => ['Blingijati','Bringinwareng','Bumiharjo','Danyangmulyo','Degan','Godo','Gunungpanti','Guyangan','Karangkonang','Karangsumber','Kebolampang','Kebowan','Klecoregonang','Kropak','Kudur','Mintorahayu','Padangan','Pagendisan','Pekalongan','Pohgading','Pulorejo','Sarimulyo','Serutsadang','Sugihan','Sumbermulyo','Tanggel','Tawangrejo','Tlogorejo','Winong','Wirun'],
        ];

        // Build records
        $records = [];
        $now = Carbon::now()->toDateTimeString();
        foreach ($data as $kec => $desaList) {
            $idKec = $kecamatanMap[$kec] ?? null;
            if (!$idKec) {
                $this->command->warn("Kecamatan map tidak ditemukan untuk: {$kec}. Lewati.");
                continue;
            }
            foreach ($desaList as $nama) {
                $records[] = [
                    'id_kecamatan' => $idKec,
                    'nama_kelurahan' => trim($nama),
                    'status' => 'Aktif',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        // HAPUS isi tabel & reset auto increment — lakukan di luar transaction karena ALTER TABLE/DDL
        DB::table('kelurahans')->delete();
        DB::statement('ALTER TABLE kelurahans AUTO_INCREMENT = 1');

        // Insert dalam transaction terpisah (hanya insert)
        DB::transaction(function () use ($records) {
            $chunks = array_chunk($records, 500);
            foreach ($chunks as $chunk) {
                DB::table('kelurahans')->insert($chunk);
            }
        });

        $this->command->info('✅ Seeder selesai! Total data: ' . count($records) . ' kelurahan/desa di Kabupaten Pati.');
    }
}
