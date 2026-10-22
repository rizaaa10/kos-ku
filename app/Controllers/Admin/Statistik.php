<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KosModel;
use App\Models\KosKunjunganModel;

class Statistik extends BaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();
        $kosModel = new KosModel();
        $kunjunganModel = new KosKunjunganModel();

        $summary = $kunjunganModel->getSummaryStats();

        // Breakdown per Kos
        $statsPerKos = $db->query("
            SELECT 
                k.id, k.nama, k.slug, k.tipe, w.nama as nama_wilayah,
                COUNT(CASE WHEN kj.jenis = 'view' THEN 1 END) as view_count,
                COUNT(CASE WHEN kj.jenis = 'klik_wa' THEN 1 END) as wa_count,
                COUNT(CASE WHEN kj.jenis = 'klik_maps' THEN 1 END) as maps_count
            FROM kos k
            LEFT JOIN wilayah w ON w.id = k.wilayah_id
            LEFT JOIN kos_kunjungan kj ON kj.kos_id = k.id
            GROUP BY k.id, k.nama, k.slug, k.tipe, w.nama
            ORDER BY view_count DESC, wa_count DESC
        ")->getResultArray();

        $data = [
            'title'       => 'Statistik & Analisis Kunjungan — kosKu Admin',
            'summary'     => $summary,
            'statsPerKos' => $statsPerKos,
        ];

        return view('admin/statistik/index', $data);
    }
}
