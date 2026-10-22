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
                                     ->orderBy('kos.created_at', 'DESC')
                                     ->limit(6)
                                     ->findAll();

        $totalKamar = 0;
        $kamarTersediaTotal = 0;
        $allRooms = $kosModel->select('jumlah_kamar, kamar_tersedia')->findAll();
        foreach ($allRooms as $r) {
            $totalKamar += (int) $r['jumlah_kamar'];
            $kamarTersediaTotal += (int) $r['kamar_tersedia'];
        }
        $occupancyRate = $totalKamar > 0 ? round((($totalKamar - $kamarTersediaTotal) / $totalKamar) * 100) : 0;

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
            'occupancyRate'      => $occupancyRate,
            'summaryStats'       => $summaryStats,
            'recentKos'          => $recentKos,
        ];

        return view('admin/dashboard/index', $data);
    }
}
