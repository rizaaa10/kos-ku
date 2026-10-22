<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\WilayahModel;
use App\Models\KosModel;

class Wilayah extends BaseController
{
    protected $wilayahModel;
    protected $kosModel;

    public function __construct()
    {
        $this->wilayahModel = new WilayahModel();
        $this->kosModel     = new KosModel();
    }

    public function index(): string
    {
        $wilayahList = $this->wilayahModel->getWithKosCount();

        $data = [
            'title'       => 'Kelola Wilayah & Batas Spasial — kosKu Admin',
            'wilayahList' => $wilayahList
        ];

        return view('admin/wilayah/index', $data);
    }

    public function create(): string
    {
        $data = [
            'title' => 'Tambah Wilayah Baru — kosKu Admin'
        ];

        return view('admin/wilayah/create', $data);
    }

    public function store()
    {
        $rules = [
            'nama'         => 'required|min_length[3]|max_length[100]',
            'tipe'         => 'required',
            'center_lat'   => 'required|numeric',
            'center_lng'   => 'required|numeric',
            'zoom_default' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $geojson = $this->request->getPost('geojson');
        if (!empty($geojson)) {
            // validate json
            json_decode($geojson);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return redirect()->back()->withInput()->with('error', 'Format GeoJSON tidak valid.');
            }
        }

        $this->wilayahModel->insert([
            'nama'         => trim((string) $this->request->getPost('nama')),
            'tipe'         => $this->request->getPost('tipe'),
            'geojson'      => !empty($geojson) ? $geojson : null,
            'center_lat'   => (float) $this->request->getPost('center_lat'),
            'center_lng'   => (float) $this->request->getPost('center_lng'),
            'zoom_default' => (int) $this->request->getPost('zoom_default'),
            'created_at'   => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url('admin/wilayah'))->with('success', 'Wilayah berhasil ditambahkan.');
    }

    public function edit(int $id): string
    {
        $wilayah = $this->wilayahModel->find($id);
        if (!$wilayah) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Wilayah tidak ditemukan.');
        }

        $data = [
            'title'   => 'Ubah Wilayah: ' . $wilayah['nama'] . ' — kosKu Admin',
            'wilayah' => $wilayah
        ];

        return view('admin/wilayah/edit', $data);
    }

    public function update(int $id)
    {
        $wilayah = $this->wilayahModel->find($id);
        if (!$wilayah) {
            return redirect()->to(base_url('admin/wilayah'))->with('error', 'Wilayah tidak ditemukan.');
        }

        $rules = [
            'nama'         => 'required|min_length[3]|max_length[100]',
            'tipe'         => 'required',
            'center_lat'   => 'required|numeric',
            'center_lng'   => 'required|numeric',
            'zoom_default' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $geojson = $this->request->getPost('geojson');
        if (!empty($geojson)) {
            json_decode($geojson);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return redirect()->back()->withInput()->with('error', 'Format GeoJSON tidak valid.');
            }
        }

        $this->wilayahModel->update($id, [
            'nama'         => trim((string) $this->request->getPost('nama')),
            'tipe'         => $this->request->getPost('tipe'),
            'geojson'      => !empty($geojson) ? $geojson : null,
            'center_lat'   => (float) $this->request->getPost('center_lat'),
            'center_lng'   => (float) $this->request->getPost('center_lng'),
            'zoom_default' => (int) $this->request->getPost('zoom_default'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url('admin/wilayah'))->with('success', 'Wilayah berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $wilayah = $this->wilayahModel->find($id);
        if (!$wilayah) {
            return redirect()->to(base_url('admin/wilayah'))->with('error', 'Wilayah tidak ditemukan.');
        }

        $kosCount = $this->kosModel->where('wilayah_id', $id)->countAllResults();
        if ($kosCount > 0) {
            return redirect()->back()->with('error', 'Wilayah tidak dapat dihapus karena masih digunakan oleh ' . $kosCount . ' data kos.');
        }

        $this->wilayahModel->delete($id);
        return redirect()->to(base_url('admin/wilayah'))->with('success', 'Wilayah berhasil dihapus.');
    }
}
