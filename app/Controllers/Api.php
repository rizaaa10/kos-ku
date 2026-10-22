<?php

namespace App\Controllers;

use App\Models\KosModel;
use App\Models\WilayahModel;
use App\Models\FasilitasModel;
use App\Models\KosKunjunganModel;
use CodeIgniter\RESTful\ResourceController;

class Api extends ResourceController
{
    protected $format = 'json';

    public function getKos()
    {
        $kosModel = new KosModel();

        $filters = [
            'wilayah_id'          => $this->request->getGet('wilayah_id'),
            'tipe'                => $this->request->getGet('tipe'),
            'status_ketersediaan' => $this->request->getGet('ketersediaan'),
            'harga_min'           => $this->request->getGet('harga_min'),
            'harga_max'           => $this->request->getGet('harga_max'),
            'q'                   => $this->request->getGet('q'),
            'sort'                => $this->request->getGet('sort') ?? 'terbaru',
            'fasilitas'           => $this->request->getGet('fasilitas'),
        ];

        // Ensure fasilitas is parsed as array if passed as comma separated or array
        if (!empty($filters['fasilitas']) && !is_array($filters['fasilitas'])) {
            $filters['fasilitas'] = explode(',', (string) $filters['fasilitas']);
        }

        $items = $kosModel->getFilteredList($filters, 'aktif');

        // Bounding box filter (optional, if sent by Leaflet)
        $south = $this->request->getGet('south');
        $north = $this->request->getGet('north');
        $west  = $this->request->getGet('west');
        $east  = $this->request->getGet('east');

        if ($south !== null && $north !== null && $west !== null && $east !== null) {
            $items = array_values(array_filter($items, function($k) use ($south, $north, $west, $east) {
                return ($k['latitude'] >= $south && $k['latitude'] <= $north &&
                        $k['longitude'] >= $west && $k['longitude'] <= $east);
            }));
        }

        return $this->respond([
            'status' => 'success',
            'total'  => count($items),
            'data'   => $items
        ]);
    }

    public function getKosDetail($idOrSlug)
    {
        $kosModel = new KosModel();
        $detail = $kosModel->getDetail($idOrSlug);

        if (!$detail) {
            return $this->failNotFound('Data kos tidak ditemukan.');
        }

        // Record page view in background
        $kunjunganModel = new KosKunjunganModel();
        $kunjunganModel->recordVisit(
            (int) $detail['id'],
            'view',
            $this->request->getIPAddress(),
            $this->request->getUserAgent()->getAgentString()
        );

        return $this->respond([
            'status' => 'success',
            'data'   => $detail
        ]);
    }

    public function getWilayah()
    {
        $wilayahModel = new WilayahModel();
        $items = $wilayahModel->getWithKosCount();

        return $this->respond([
            'status' => 'success',
            'total'  => count($items),
            'data'   => $items
        ]);
    }

    public function getWilayahGeojson($id)
    {
        $wilayahModel = new WilayahModel();
        $wilayah = $wilayahModel->find($id);

        if (!$wilayah) {
            return $this->failNotFound('Wilayah tidak ditemukan.');
        }

        $geojson = $wilayah['geojson'] ? json_decode($wilayah['geojson'], true) : null;

        return $this->respond([
            'status'     => 'success',
            'id'         => $wilayah['id'],
            'nama'       => $wilayah['nama'],
            'center_lat' => (float) $wilayah['center_lat'],
            'center_lng' => (float) $wilayah['center_lng'],
            'zoom'       => (int) $wilayah['zoom_default'],
            'geojson'    => $geojson
        ]);
    }

    public function getFasilitas()
    {
        $fasilitasModel = new FasilitasModel();
        $items = $fasilitasModel->orderBy('kategori', 'ASC')->orderBy('nama', 'ASC')->findAll();

        return $this->respond([
            'status' => 'success',
            'total'  => count($items),
            'data'   => $items
        ]);
    }

    public function trackKos($id)
    {
        $jenis = $this->request->getPost('jenis') ?? $this->request->getGet('jenis');
        if (!in_array($jenis, ['view', 'klik_wa', 'klik_maps'])) {
            return $this->fail('Jenis tracking tidak valid.');
        }

        $kunjunganModel = new KosKunjunganModel();
        $kunjunganModel->recordVisit(
            (int) $id,
            $jenis,
            $this->request->getIPAddress(),
            $this->request->getUserAgent()->getAgentString()
        );

        return $this->respond([
            'status'  => 'success',
            'message' => 'Tracking berhasil dicatat.'
        ]);
    }
}
