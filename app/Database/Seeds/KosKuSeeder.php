<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KosKuSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // 1. Seed Admin User
        $userCheck = $db->table('users')->where('email', 'admin@kosku.id')->get()->getRow();
        if (!$userCheck) {
            $db->table('users')->insert([
                'nama'          => 'Administrator kosKu',
                'email'         => 'admin@kosku.id',
                'password_hash' => password_hash('admin123', PASSWORD_BCRYPT),
                'role'          => 'admin',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);
        }

        // 2. Seed Master Fasilitas
        $fasilitasList = [
            ['nama' => 'WiFi High-Speed', 'ikon' => 'bi-wifi', 'kategori' => 'umum'],
            ['nama' => 'Air Conditioner (AC)', 'ikon' => 'bi-snow', 'kategori' => 'kamar'],
            ['nama' => 'Kamar Mandi Dalam', 'ikon' => 'bi-droplet-half', 'kategori' => 'kamar'],
            ['nama' => 'Kasur Springbed & Bantal', 'ikon' => 'bi-inboxes', 'kategori' => 'kamar'],
            ['nama' => 'Lemari Pakaian', 'ikon' => 'bi-door-closed', 'kategori' => 'kamar'],
            ['nama' => 'Meja & Kursi Belajar', 'ikon' => 'bi-lamp', 'kategori' => 'kamar'],
            ['nama' => 'Dapur Bersama & Kulkas', 'ikon' => 'bi-cup-hot', 'kategori' => 'umum'],
            ['nama' => 'Parkir Motor Aman & Beratap', 'ikon' => 'bi-bicycle', 'kategori' => 'umum'],
            ['nama' => 'Parkir Mobil Luas', 'ikon' => 'bi-car-front', 'kategori' => 'umum'],
            ['nama' => 'CCTV Keamanan 24 Jam', 'ikon' => 'bi-camera-video', 'kategori' => 'umum'],
            ['nama' => 'Akses Kunci 24 Jam (Gerbang Mandiri)', 'ikon' => 'bi-key', 'kategori' => 'umum'],
            ['nama' => 'Mesin Cuci Bersama & Area Jemur', 'ikon' => 'bi-water', 'kategori' => 'umum'],
        ];

        foreach ($fasilitasList as $fas) {
            $exist = $db->table('fasilitas')->where('nama', $fas['nama'])->get()->getRow();
            if (!$exist) {
                $db->table('fasilitas')->insert([
                    'nama'       => $fas['nama'],
                    'ikon'       => $fas['ikon'],
                    'kategori'   => $fas['kategori'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        // 3. Seed Wilayah with GeoJSON
        $geojsonBumiayu = json_encode([
            'type' => 'Feature',
            'geometry' => [
                'type' => 'Polygon',
                'coordinates' => [[
                    [108.9950, -7.2350],
                    [109.0200, -7.2350],
                    [109.0230, -7.2550],
                    [108.9920, -7.2550],
                    [108.9950, -7.2350]
                ]]
            ],
            'properties' => ['name' => 'Kecamatan Bumiayu']
        ]);

        $geojsonPurwokertoUtara = json_encode([
            'type' => 'Feature',
            'geometry' => [
                'type' => 'Polygon',
                'coordinates' => [[
                    [109.2350, -7.3920],
                    [109.2620, -7.3920],
                    [109.2650, -7.4180],
                    [109.2320, -7.4180],
                    [109.2350, -7.3920]
                ]]
            ],
            'properties' => ['name' => 'Purwokerto Utara']
        ]);

        $geojsonPurwokertoTimur = json_encode([
            'type' => 'Feature',
            'geometry' => [
                'type' => 'Polygon',
                'coordinates' => [[
                    [109.2400, -7.4180],
                    [109.2680, -7.4180],
                    [109.2700, -7.4380],
                    [109.2380, -7.4380],
                    [109.2400, -7.4180]
                ]]
            ],
            'properties' => ['name' => 'Purwokerto Timur']
        ]);

        $geojsonPaguyangan = json_encode([
            'type' => 'Feature',
            'geometry' => [
                'type' => 'Polygon',
                'coordinates' => [[
                    [109.0050, -7.2550],
                    [109.0300, -7.2550],
                    [109.0350, -7.2800],
                    [109.0020, -7.2800],
                    [109.0050, -7.2550]
                ]]
            ],
            'properties' => ['name' => 'Kecamatan Paguyangan']
        ]);

        $wilayahData = [
            [
                'nama'         => 'Bumiayu (Area Kampus Peradaban)',
                'tipe'         => 'Kecamatan',
                'geojson'      => $geojsonBumiayu,
                'center_lat'   => -7.243542,
                'center_lng'   => 109.006528,
                'zoom_default' => 15,
            ],
            [
                'nama'         => 'Purwokerto Utara (Area Kampus Unsoed)',
                'tipe'         => 'Kecamatan',
                'geojson'      => $geojsonPurwokertoUtara,
                'center_lat'   => -7.404212,
                'center_lng'   => 109.248612,
                'zoom_default' => 15,
            ],
            [
                'nama'         => 'Purwokerto Timur (Pusat Kota)',
                'tipe'         => 'Kecamatan',
                'geojson'      => $geojsonPurwokertoTimur,
                'center_lat'   => -7.425812,
                'center_lng'   => 109.252031,
                'zoom_default' => 14,
            ],
            [
                'nama'         => 'Paguyangan',
                'tipe'         => 'Kecamatan',
                'geojson'      => $geojsonPaguyangan,
                'center_lat'   => -7.265012,
                'center_lng'   => 109.018042,
                'zoom_default' => 14,
            ],
        ];

        foreach ($wilayahData as $w) {
            $exist = $db->table('wilayah')->where('nama', $w['nama'])->get()->getRow();
            if (!$exist) {
                $db->table('wilayah')->insert(array_merge($w, [
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]));
            }
        }

        // Get Wilayah IDs
        $wilayahBumiayu = $db->table('wilayah')->where('nama', 'Bumiayu (Area Kampus Peradaban)')->get()->getRow();
        $wilayahPwtUtr  = $db->table('wilayah')->where('nama', 'Purwokerto Utara (Area Kampus Unsoed)')->get()->getRow();
        $wilayahPwtTmr  = $db->table('wilayah')->where('nama', 'Purwokerto Timur (Pusat Kota)')->get()->getRow();
        $wilayahPgy     = $db->table('wilayah')->where('nama', 'Paguyangan')->get()->getRow();

        // 4. Seed Kos Data
        $kosList = [
            [
                'nama'                => 'Kos Graha Peradaban Asri',
                'slug'                => 'kos-graha-peradaban-asri',
                'deskripsi'           => 'Kos khusus putri dengan suasana tenang, aman, dan sangat dekat ke kampus Universitas Peradaban Bumiayu (hanya 3 menit jalan kaki). Dilengkapi kamar mandi dalam, kasur empuk, lemari besar, dan WiFi super kencang untuk menunjang tugas kuliah.',
                'alamat'              => 'Jl. Raya Pagojengan No. 12, Kalierang, Kec. Bumiayu, Kabupaten Brebes',
                'wilayah_id'          => $wilayahBumiayu->id,
                'latitude'            => -7.244120,
                'longitude'           => 109.007150,
                'harga_min'           => 650000,
                'harga_max'           => 850000,
                'tipe'                => 'putri',
                'jumlah_kamar'        => 12,
                'kamar_tersedia'      => 3,
                'status_ketersediaan' => 'tersedia',
                'status_publikasi'    => 'aktif',
                'kontak_nama'         => 'Ibu Hj. Siti Aminah',
                'kontak_wa'           => '6281234567890',
                'kontak_telepon'      => '081234567890',
                'peraturan'           => "- Jam malam tamu pukul 21.00 WIB\n- Tidak boleh membawa lawan jenis ke kamar\n- Dilarang merokok di area kamar\n- Menjaga ketenangan dan kebersihan bersama",
                'is_unggulan'         => true,
            ],
            [
                'nama'                => 'Kos Pondok Mahasiswa Peradaban (Putra)',
                'slug'                => 'kos-pondok-mahasiswa-peradaban-putra',
                'deskripsi'           => 'Kos putra modern dengan sistem kunci bebas 24 jam. Parkir motor luas berkanopi, dekat minimarket, warung makan, dan fotokopi. Kecepatan internet WiFi 50 Mbps lancar jaya.',
                'alamat'              => 'Jl. KH. Ahmad Dahlan Gg. Peradaban No. 5, Bumiayu',
                'wilayah_id'          => $wilayahBumiayu->id,
                'latitude'            => -7.242850,
                'longitude'           => 109.005820,
                'harga_min'           => 500000,
                'harga_max'           => 650000,
                'tipe'                => 'putra',
                'jumlah_kamar'        => 15,
                'kamar_tersedia'      => 4,
                'status_ketersediaan' => 'tersedia',
                'status_publikasi'    => 'aktif',
                'kontak_nama'         => 'Pak Rudi Hartono',
                'kontak_wa'           => '6281398765432',
                'kontak_telepon'      => '081398765432',
                'peraturan'           => "- Kunci gerbang masing-masing\n- Tamu dilarang menginap tanpa izin pengelola\n- Bayar sewa tepat waktu maksimal tanggal 5 tiap bulan",
                'is_unggulan'         => true,
            ],
            [
                'nama'                => 'Kos Paviliun Mawar Cantik',
                'slug'                => 'kos-paviliun-mawar-cantik',
                'deskripsi'           => 'Hunian kos putri eksklusif ber-AC dan kamar mandi dalam shower air hangat. Lingkungan elit dan asri, terjamin kebersihannya.',
                'alamat'              => 'Jl. Pangeran Diponegoro No. 88, Dukuhturi, Bumiayu',
                'wilayah_id'          => $wilayahBumiayu->id,
                'latitude'            => -7.245200,
                'longitude'           => 109.009400,
                'harga_min'           => 900000,
                'harga_max'           => 1200000,
                'tipe'                => 'putri',
                'jumlah_kamar'        => 8,
                'kamar_tersedia'      => 2,
                'status_ketersediaan' => 'tersedia',
                'status_publikasi'    => 'aktif',
                'kontak_nama'         => 'Ibu Endang',
                'kontak_wa'           => '6285223344556',
                'kontak_telepon'      => '085223344556',
                'peraturan'           => "- Menjaga tata krama dan kesopanan\n- Penggunaan listrik watt besar konfirmasi terlebih dahulu\n- Tamu pria hanya di ruang tamu depan",
                'is_unggulan'         => false,
            ],
            [
                'nama'                => 'Kos Campur Griya Harmoni',
                'slug'                => 'kos-campur-griya-harmoni',
                'deskripsi'           => 'Kos campur (pasutri sah / karyawan / mahasiswa) dengan parkir mobil lega, CCTV 24 jam, dan dapur bersama lengkap dengan kompor & kulkas.',
                'alamat'              => 'Jl. Lingkar Luar Bumiayu No. 45, Laren, Bumiayu',
                'wilayah_id'          => $wilayahBumiayu->id,
                'latitude'            => -7.239800,
                'longitude'           => 109.003900,
                'harga_min'           => 750000,
                'harga_max'           => 1000000,
                'tipe'                => 'campur',
                'jumlah_kamar'        => 10,
                'kamar_tersedia'      => 0,
                'status_ketersediaan' => 'penuh',
                'status_publikasi'    => 'aktif',
                'kontak_nama'         => 'Bapak Hendra',
                'kontak_wa'           => '6287711223344',
                'kontak_telepon'      => '087711223344',
                'peraturan'           => "- Pasutri wajib melampirkan fotokopi buku nikah\n- Tertib membuang sampah pada tempatnya\n- Menghemat pemakaian listrik dan air",
                'is_unggulan'         => false,
            ],
            [
                'nama'                => 'Kos Unsoed Harmoni Grendeng',
                'slug'                => 'kos-unsoed-harmoni-grendeng',
                'deskripsi'           => 'Kos putra strategis di belakang gerbang utama Unsoed. Dekat FISIP, FEB, dan Fakultas Hukum. Akses mudah ke banyak kuliner mahasiswa, laundry, dan cafe.',
                'alamat'              => 'Jl. HR Boenyamin Gg. Grendeng No. 17, Purwokerto Utara',
                'wilayah_id'          => $wilayahPwtUtr->id,
                'latitude'            => -7.403500,
                'longitude'           => 109.249200,
                'harga_min'           => 700000,
                'harga_max'           => 950000,
                'tipe'                => 'putra',
                'jumlah_kamar'        => 20,
                'kamar_tersedia'      => 5,
                'status_ketersediaan' => 'tersedia',
                'status_publikasi'    => 'aktif',
                'kontak_nama'         => 'Bapak Slamet',
                'kontak_wa'           => '6281299887766',
                'kontak_telepon'      => '081299887766',
                'peraturan'           => "- Gerbang 24 jam dengan fingerprint\n- Dilarang membawa miras / narkoba\n- Menjaga kebersihan kamar mandi dan dapur",
                'is_unggulan'         => true,
            ],
            [
                'nama'                => 'Kos Putri Kusuma Unsoed Karangwangkal',
                'slug'                => 'kos-putri-kusuma-unsoed-karangwangkal',
                'deskripsi'           => 'Kos putri asri dengan fasilitas AC, WiFi fiber, springbed ukuran queen, lemari cermin, dan kamar mandi dalam. Terdapat penjaga kos 24 jam.',
                'alamat'              => 'Jl. DR. Soeparno No. 64, Karangwangkal, Purwokerto Utara',
                'wilayah_id'          => $wilayahPwtUtr->id,
                'latitude'            => -7.401800,
                'longitude'           => 109.253400,
                'harga_min'           => 1100000,
                'harga_max'           => 1400000,
                'tipe'                => 'putri',
                'jumlah_kamar'        => 16,
                'kamar_tersedia'      => 3,
                'status_ketersediaan' => 'tersedia',
                'status_publikasi'    => 'aktif',
                'kontak_nama'         => 'Ibu Kusuma Wardani',
                'kontak_wa'           => '6281566778899',
                'kontak_telepon'      => '081566778899',
                'peraturan'           => "- Jam malam gerbang pukul 22.00 WIB\n- Tamu menginap wajib konfirmasi pengelola\n- Dilarang memasak di dalam kamar tidur",
                'is_unggulan'         => true,
            ],
            [
                'nama'                => 'Kos Executive Omah Timur',
                'slug'                => 'kos-executive-omah-timur',
                'deskripsi'           => 'Pilihan terbaik untuk profesional muda dan karyawan kantor di Purwokerto. Kamar luas 4x5 meter, Smart TV, AC inverter, dan pembersihan kamar berkala.',
                'alamat'              => 'Jl. Jenderal Soedirman Timur No. 102, Purwokerto Timur',
                'wilayah_id'          => $wilayahPwtTmr->id,
                'latitude'            => -7.426500,
                'longitude'           => 109.251500,
                'harga_min'           => 1300000,
                'harga_max'           => 1600000,
                'tipe'                => 'campur',
                'jumlah_kamar'        => 10,
                'kamar_tersedia'      => 2,
                'status_ketersediaan' => 'tersedia',
                'status_publikasi'    => 'aktif',
                'kontak_nama'         => 'Bapak Bagus Prasetyo',
                'kontak_wa'           => '6282133445577',
                'kontak_telepon'      => '082133445577',
                'peraturan'           => "- Tenang dan kondusif untuk istirahat\n- Parkir mobil teratur di slot masing-masing\n- No smoking di koridor dan kamar ber-AC",
                'is_unggulan'         => true,
            ],
            [
                'nama'                => 'Kos Wisma Flamboyan Paguyangan',
                'slug'                => 'kos-wisma-flamboyan-paguyangan',
                'deskripsi'           => 'Kos ekonomis dan ramah kantong di Paguyangan. Udara sejuk dekat kawasan perkebunan teh Kaligua dan pusat oleh-oleh.',
                'alamat'              => 'Jl. Raya Paguyangan KM 2, Paguyangan, Brebes',
                'wilayah_id'          => $wilayahPgy->id,
                'latitude'            => -7.264200,
                'longitude'           => 109.019100,
                'harga_min'           => 450000,
                'harga_max'           => 600000,
                'tipe'                => 'putri',
                'jumlah_kamar'        => 8,
                'kamar_tersedia'      => 2,
                'status_ketersediaan' => 'tersedia',
                'status_publikasi'    => 'aktif',
                'kontak_nama'         => 'Ibu Flamboyan',
                'kontak_wa'           => '6289912345678',
                'kontak_telepon'      => '089912345678',
                'peraturan'           => "- Saling menghormati sesama penghuni kos\n- Menutup keran air setelah digunakan",
                'is_unggulan'         => false,
            ],
        ];

        // Collect all facilities
        $allFasilitas = $db->table('fasilitas')->get()->getResultArray();
        $fasMap = [];
        foreach ($allFasilitas as $f) {
            $fasMap[$f['nama']] = $f['id'];
        }

        foreach ($kosList as $idx => $k) {
            $exist = $db->table('kos')->where('slug', $k['slug'])->get()->getRow();
            if (!$exist) {
                $db->table('kos')->insert(array_merge($k, [
                    'created_at' => date('Y-m-d H:i:s', strtotime("-" . ($idx * 2) . " days")),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]));
                $kosId = $db->insertID();

                // Attach facilities
                $randomFas = array_slice($allFasilitas, 0, rand(5, 10));
                foreach ($randomFas as $rf) {
                    $db->table('kos_fasilitas')->insert([
                        'kos_id'       => $kosId,
                        'fasilitas_id' => $rf['id'],
                    ]);
                }

                // Attach Photos
                $photoPaths = [
                    'uploads/kos/sample_kos_1.svg',
                    'uploads/kos/sample_kos_2.svg',
                    'uploads/kos/sample_kos_3.svg',
                ];

                foreach ($photoPaths as $pIndex => $pPath) {
                    $db->table('kos_foto')->insert([
                        'kos_id'     => $kosId,
                        'path_file'  => $pPath,
                        'keterangan' => ($pIndex === 0) ? 'Tampak Depan & Kamar' : 'Fasilitas & Kamar Mandi',
                        'is_utama'   => ($pIndex === 0),
                        'urutan'     => $pIndex,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }

                // Add sample visits
                $db->table('kos_kunjungan')->insert([
                    'kos_id'     => $kosId,
                    'jenis'      => 'view',
                    'ip_address' => '127.0.0.1',
                    'created_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
                ]);
                $db->table('kos_kunjungan')->insert([
                    'kos_id'     => $kosId,
                    'jenis'      => 'klik_wa',
                    'ip_address' => '127.0.0.1',
                    'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                ]);
            }
        }
    }
}
