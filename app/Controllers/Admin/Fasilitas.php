<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\FasilitasModel;
use App\Models\KosFasilitasModel;

class Fasilitas extends BaseController
{
    protected $fasilitasModel;
    protected $kosFasilitasModel;

    public function __construct()
    {
        $this->fasilitasModel    = new FasilitasModel();
        $this->kosFasilitasModel = new KosFasilitasModel();
    }

    public function index(): string
    {
        $fasilitasList = $this->fasilitasModel->orderBy('kategori', 'ASC')->orderBy('nama', 'ASC')->findAll();

        $data = [
            'title'         => 'Kelola Master Fasilitas — kosKu Admin',
            'fasilitasList' => $fasilitasList
        ];

        return view('admin/fasilitas/index', $data);
    }

    public function store()
    {
        $rules = [
            'nama'     => 'required|min_length[2]|max_length[100]',
            'ikon'     => 'required|max_length[50]',
            'kategori' => 'required|in_list[kamar,umum]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->fasilitasModel->insert([
            'nama'       => trim((string) $this->request->getPost('nama')),
            'ikon'       => trim((string) $this->request->getPost('ikon')),
            'kategori'   => $this->request->getPost('kategori'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url('admin/fasilitas'))->with('success', 'Fasilitas baru berhasil ditambahkan.');
    }

    public function update(int $id)
    {
        $fasilitas = $this->fasilitasModel->find($id);
        if (!$fasilitas) {
            return redirect()->to(base_url('admin/fasilitas'))->with('error', 'Fasilitas tidak ditemukan.');
        }

        $rules = [
            'nama'     => 'required|min_length[2]|max_length[100]',
            'ikon'     => 'required|max_length[50]',
            'kategori' => 'required|in_list[kamar,umum]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->fasilitasModel->update($id, [
            'nama'       => trim((string) $this->request->getPost('nama')),
            'ikon'       => trim((string) $this->request->getPost('ikon')),
            'kategori'   => $this->request->getPost('kategori'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url('admin/fasilitas'))->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $fasilitas = $this->fasilitasModel->find($id);
        if (!$fasilitas) {
            return redirect()->to(base_url('admin/fasilitas'))->with('error', 'Fasilitas tidak ditemukan.');
        }

        $this->kosFasilitasModel->where('fasilitas_id', $id)->delete();
        $this->fasilitasModel->delete($id);

        return redirect()->to(base_url('admin/fasilitas'))->with('success', 'Fasilitas berhasil dihapus.');
    }
}
