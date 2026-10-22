<?php

namespace App\Controllers;

use App\Models\KosModel;
use App\Models\WilayahModel;
use App\Models\FasilitasModel;
use App\Models\KosKunjunganModel;

class Home extends BaseController
{
    protected $kosModel;
    protected $wilayahModel;
    protected $fasilitasModel;

    public function __construct()
    {
        $this->kosModel       = new KosModel();
        $this->wilayahModel   = new WilayahModel();
        $this->fasilitasModel = new FasilitasModel();
    }

    public function index(): string
    {
        $wilayahList   = $this->wilayahModel->getWithKosCount();
        $fasilitasList = $this->fasilitasModel->orderBy('kategori', 'ASC')->orderBy('nama', 'ASC')->findAll();
        
        $selectedWilayahId = $this->request->getGet('wilayah_id') ?? '';
        $selectedTipe      = $this->request->getGet('tipe') ?? '';
        $q                 = $this->request->getGet('q') ?? '';

        $filters = [
            'wilayah_id' => $selectedWilayahId,
            'tipe'       => $selectedTipe,
            'q'          => $q,
        ];

        $kosList = $this->kosModel->getFilteredList($filters, 'aktif');

        // Initial default center (Bumiayu or first wilayah)
        $defaultCenter = [
            'lat'  => -7.243542,
            'lng'  => 109.006528,
            'zoom' => 14
        ];
        if (!empty($selectedWilayahId)) {
            foreach ($wilayahList as $w) {
                if ($w['id'] == $selectedWilayahId) {
                    $defaultCenter['lat']  = (float) $w['center_lat'];
                    $defaultCenter['lng']  = (float) $w['center_lng'];
                    $defaultCenter['zoom'] = (int) $w['zoom_default'];
                    break;
                }
            }
        }

        $data = [
            'title'             => 'kosKu — Cari Kos, Langsung dari Peta',
            'wilayahList'       => $wilayahList,
            'fasilitasList'     => $fasilitasList,
            'kosList'           => $kosList,
            'defaultCenter'     => $defaultCenter,
            'selectedWilayahId' => $selectedWilayahId,
            'selectedTipe'      => $selectedTipe,
            'q'                 => $q
        ];

        return view('public/explorer', $data);
    }

    public function detail(string $slug): string
    {
        $kos = $this->kosModel->getDetail($slug);
        if (!$kos) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kos tidak ditemukan.');
        }

        // Record page view
        $kunjunganModel = new KosKunjunganModel();
        $kunjunganModel->recordVisit(
            (int) $kos['id'],
            'view',
            $this->request->getIPAddress(),
            $this->request->getUserAgent()->getAgentString()
        );

        // Nearby or similar kos
        $relatedKos = $this->kosModel->where('wilayah_id', $kos['wilayah_id'])
                                     ->where('id !=', $kos['id'])
                                     ->where('status_publikasi', 'aktif')
                                     ->limit(3)
                                     ->findAll();

        $fotoModel = new \App\Models\KosFotoModel();
        foreach ($relatedKos as &$r) {
            $r['foto_utama'] = $fotoModel->where('kos_id', $r['id'])->orderBy('is_utama', 'DESC')->first();
        }

        $data = [
            'title'      => $kos['nama'] . ' — kosKu',
            'kos'        => $kos,
            'relatedKos' => $relatedKos
        ];

        return view('public/detail', $data);
    }

    public function about(): string
    {
        $totalKos     = $this->kosModel->where('status_publikasi', 'aktif')->countAllResults();
        $totalWilayah = $this->wilayahModel->countAllResults();

        $data = [
            'title'        => 'Tentang kosKu — Inovasi Pencarian Kos Berbasis Peta',
            'totalKos'     => $totalKos,
            'totalWilayah' => $totalWilayah
        ];

        return view('public/about', $data);
    }

    public function contact(): string
    {
        $data = [
            'title' => 'Kontak & Bantuan — kosKu',
        ];

        return view('public/contact', $data);
    }

    public function favorites(): string
    {
        $data = [
            'title' => 'Kos Favorit Saya — kosKu',
        ];

        return view('public/favorites', $data);
    }
}
