<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KosModel;
use App\Models\WilayahModel;
use App\Models\FasilitasModel;
use App\Models\KosKunjunganModel;

class Dashboard extends BaseController
{
    public function index(): string
    {
        $kosModel       = new KosModel();
        $wilayahModel   = new WilayahModel();
        $fasilitasModel = new FasilitasModel();
        $kunjunganModel = new KosKunjunganModel();

        $totalKos         = $kosModel->countAllResults();
        $kosAktif         = $kosModel->where('status_publikasi', 'aktif')->countAllResults();
        $kosTersedia      = $kosModel->where('status_ketersediaan', 'tersedia')->where('status_publikasi', 'aktif')->countAllResults();
        $kosPenuh         = $kosModel->where('status_ketersediaan', 'penuh')->where('status_publikasi', 'aktif')->countAllResults();
        $totalWilayah     = $wilayahModel->countAllResults();
        $totalFasilitas   = $fasilitasModel->countAllResults();

        $summaryStats     = $kunjunganModel->getSummaryStats();

        $recentKos        = $kosModel->select('kos.*, wilayah.nama as nama_wilayah')
                                     ->join('wilayah', 'wilayah.id = kos.wilayah_id', 'left')
                                     ->orderBy('kos.is_unggulan', 'DESC')
                                     ->orderBy('kos.created_at', 'DESC')
                                     ->limit(8)
                                     ->findAll();

        $totalKamar = 0;
        $kamarTersediaTotal = 0;
        $totalPrice = 0;
        $allRooms = $kosModel->select('jumlah_kamar, kamar_tersedia, harga_min')->findAll();
        foreach ($allRooms as $r) {
            $totalKamar += (int) $r['jumlah_kamar'];
            $kamarTersediaTotal += (int) $r['kamar_tersedia'];
            $totalPrice += (float) $r['harga_min'];
        }

        $kamarTerisiTotal = max(0, $totalKamar - $kamarTersediaTotal);
        $occupancyRate    = $totalKamar > 0 ? round(($kamarTerisiTotal / $totalKamar) * 100) : 0;
        $avgPrice         = count($allRooms) > 0 ? round($totalPrice / count($allRooms)) : 0;

        $views = $summaryStats['total_views'] ?? 0;
        $waClicks = $summaryStats['total_wa'] ?? 0;
        $waConversionRate = $views > 0 ? round(($waClicks / $views) * 100, 1) : 0;

        // Featured Kos for the top highlight card
        $featuredKos = $kosModel->select('kos.*, wilayah.nama as nama_wilayah')
                                ->join('wilayah', 'wilayah.id = kos.wilayah_id', 'left')
                                ->where('kos.is_unggulan', true)
                                ->first();
        if (!$featuredKos && !empty($recentKos)) {
            $featuredKos = $recentKos[0];
        }

        $data = [
            'title'              => 'Admin Dashboard — kosKu',
            'totalKos'           => $totalKos,
            'kosAktif'           => $kosAktif,
            'kosTersedia'        => $kosTersedia,
            'kosPenuh'           => $kosPenuh,
            'totalWilayah'       => $totalWilayah,
            'totalFasilitas'     => $totalFasilitas,
            'totalKamar'         => $totalKamar,
            'kamarTersediaTotal' => $kamarTersediaTotal,
            'kamarTerisiTotal'   => $kamarTerisiTotal,
            'occupancyRate'      => $occupancyRate,
            'avgPrice'           => $avgPrice,
            'waConversionRate'   => $waConversionRate,
            'summaryStats'       => $summaryStats,
            'recentKos'          => $recentKos,
            'featuredKos'        => $featuredKos,
        ];

        return view('admin/dashboard/index', $data);
    }
}
