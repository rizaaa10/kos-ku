<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KosModel;
use App\Models\WilayahModel;
use App\Models\FasilitasModel;
use App\Models\KosFasilitasModel;
use App\Models\KosFotoModel;

class Kos extends BaseController
{
    protected $kosModel;
    protected $wilayahModel;
    protected $fasilitasModel;
    protected $kosFasilitasModel;
    protected $kosFotoModel;

    public function __construct()
    {
        $this->kosModel          = new KosModel();
        $this->wilayahModel      = new WilayahModel();
        $this->fasilitasModel    = new FasilitasModel();
        $this->kosFasilitasModel = new KosFasilitasModel();
        $this->kosFotoModel      = new KosFotoModel();
    }

    public function index(): string
    {
        $wilayahId = $this->request->getGet('wilayah_id');
        $status    = $this->request->getGet('status');
        $q         = $this->request->getGet('q');

        $builder = $this->kosModel->select('kos.*, wilayah.nama as nama_wilayah')
                                  ->join('wilayah', 'wilayah.id = kos.wilayah_id', 'left');

        if (!empty($wilayahId)) {
            $builder->where('kos.wilayah_id', $wilayahId);
        }

        if (!empty($status)) {
            $builder->where('kos.status_publikasi', $status);
        }

        if (!empty($q)) {
            $builder->groupStart()
                    ->like('LOWER(kos.nama)', strtolower($q))
                    ->orLike('LOWER(kos.alamat)', strtolower($q))
                    ->groupEnd();
        }

        $kosList = $builder->orderBy('kos.created_at', 'DESC')->findAll();

        foreach ($kosList as &$k) {
            $k['foto_utama'] = $this->kosFotoModel->where('kos_id', $k['id'])->orderBy('is_utama', 'DESC')->first();
        }

        $data = [
            'title'       => 'Kelola Data Kos — kosKu Admin',
            'kosList'     => $kosList,
            'wilayahList' => $this->wilayahModel->findAll(),
            'wilayahId'   => $wilayahId,
            'status'      => $status,
            'q'           => $q
        ];

        return view('admin/kos/index', $data);
    }

    public function create(): string
    {
        $data = [
            'title'         => 'Tambah Kos Baru — kosKu Admin',
            'wilayahList'   => $this->wilayahModel->findAll(),
            'fasilitasList' => $this->fasilitasModel->orderBy('kategori', 'ASC')->findAll(),
        ];

        return view('admin/kos/create', $data);
    }

    public function store()
    {
        $rules = [
            'nama'           => 'required|min_length[3]|max_length[150]',
            'alamat'         => 'required',
            'wilayah_id'     => 'required|is_not_unique[wilayah.id]',
            'latitude'       => 'required|numeric',
            'longitude'      => 'required|numeric',
            'harga_min'      => 'required|numeric',
            'tipe'           => 'required|in_list[putra,putri,campur]',
            'jumlah_kamar'   => 'required|integer',
            'kamar_tersedia' => 'required|integer',
            'kontak_nama'    => 'required|max_length[100]',
            'kontak_wa'      => 'required|max_length[30]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $nama = trim((string) $this->request->getPost('nama'));
        $slug = $this->kosModel->generateSlug($nama);

        $dataInsert = [
            'nama'                => $nama,
            'slug'                => $slug,
            'deskripsi'           => $this->request->getPost('deskripsi'),
            'alamat'              => $this->request->getPost('alamat'),
            'wilayah_id'          => (int) $this->request->getPost('wilayah_id'),
            'latitude'            => (float) $this->request->getPost('latitude'),
            'longitude'           => (float) $this->request->getPost('longitude'),
            'harga_min'           => (float) $this->request->getPost('harga_min'),
            'harga_max'           => $this->request->getPost('harga_max') ? (float) $this->request->getPost('harga_max') : null,
            'tipe'                => $this->request->getPost('tipe'),
            'jumlah_kamar'        => (int) $this->request->getPost('jumlah_kamar'),
            'kamar_tersedia'      => (int) $this->request->getPost('kamar_tersedia'),
            'status_ketersediaan' => ((int) $this->request->getPost('kamar_tersedia') > 0) ? 'tersedia' : 'penuh',
            'status_publikasi'    => $this->request->getPost('status_publikasi') ?? 'aktif',
            'kontak_nama'         => $this->request->getPost('kontak_nama'),
            'kontak_wa'           => $this->formatWa($this->request->getPost('kontak_wa')),
            'kontak_telepon'      => $this->request->getPost('kontak_telepon'),
            'peraturan'           => $this->request->getPost('peraturan'),
            'is_unggulan'         => $this->request->getPost('is_unggulan') ? true : false,
        ];

        $kosId = $this->kosModel->insert($dataInsert);

        // Save Facilities
        $fasilitas = $this->request->getPost('fasilitas');
        if (!empty($fasilitas) && is_array($fasilitas)) {
            foreach ($fasilitas as $fId) {
                $this->kosFasilitasModel->insert([
                    'kos_id'       => $kosId,
                    'fasilitas_id' => (int) $fId
                ]);
            }
        }

        // Handle Photo Uploads
        $photos = $this->request->getFileMultiple('foto');
        $uploadedCount = 0;
        if ($photos) {
            foreach ($photos as $idx => $photo) {
                if ($photo->isValid() && !$photo->hasMoved()) {
                    $newName = $photo->getRandomName();
                    $photo->move(FCPATH . 'uploads/kos', $newName);

                    $this->kosFotoModel->insert([
                        'kos_id'     => $kosId,
                        'path_file'  => 'uploads/kos/' . $newName,
                        'keterangan' => 'Foto Kos',
                        'is_utama'   => ($uploadedCount === 0),
                        'urutan'     => $uploadedCount,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                    $uploadedCount++;
                }
            }
        }

        // If no photo was uploaded, attach default sample photo
        if ($uploadedCount === 0) {
            $this->kosFotoModel->insert([
                'kos_id'     => $kosId,
                'path_file'  => 'uploads/kos/sample_kos_1.svg',
                'keterangan' => 'Foto Standar',
                'is_utama'   => true,
                'urutan'     => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return redirect()->to(base_url('admin/kos'))->with('success', 'Data kos "' . esc($nama) . '" berhasil ditambahkan.');
    }

    public function edit(int $id): string
    {
        $kos = $this->kosModel->find($id);
        if (!$kos) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data kos tidak ditemukan.');
        }

        $currentFasilitas = $this->kosFasilitasModel->where('kos_id', $id)->findAll();
        $selectedFasilitasIds = array_column($currentFasilitas, 'fasilitas_id');

        $data = [
            'title'                => 'Ubah Kos: ' . $kos['nama'] . ' — kosKu Admin',
            'kos'                  => $kos,
            'wilayahList'          => $this->wilayahModel->findAll(),
            'fasilitasList'        => $this->fasilitasModel->orderBy('kategori', 'ASC')->findAll(),
            'selectedFasilitasIds' => $selectedFasilitasIds,
        ];

        return view('admin/kos/edit', $data);
    }

    public function update(int $id)
    {
        $kos = $this->kosModel->find($id);
        if (!$kos) {
            return redirect()->to(base_url('admin/kos'))->with('error', 'Data kos tidak ditemukan.');
        }

        $rules = [
            'nama'           => 'required|min_length[3]|max_length[150]',
            'alamat'         => 'required',
            'wilayah_id'     => 'required|is_not_unique[wilayah.id]',
            'latitude'       => 'required|numeric',
            'longitude'      => 'required|numeric',
            'harga_min'      => 'required|numeric',
            'tipe'           => 'required|in_list[putra,putri,campur]',
            'jumlah_kamar'   => 'required|integer',
            'kamar_tersedia' => 'required|integer',
            'kontak_nama'    => 'required|max_length[100]',
            'kontak_wa'      => 'required|max_length[30]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $nama = trim((string) $this->request->getPost('nama'));
        $slug = ($nama !== $kos['nama']) ? $this->kosModel->generateSlug($nama, $id) : $kos['slug'];

        $dataUpdate = [
            'nama'                => $nama,
            'slug'                => $slug,
            'deskripsi'           => $this->request->getPost('deskripsi'),
            'alamat'              => $this->request->getPost('alamat'),
            'wilayah_id'          => (int) $this->request->getPost('wilayah_id'),
            'latitude'            => (float) $this->request->getPost('latitude'),
            'longitude'           => (float) $this->request->getPost('longitude'),
            'harga_min'           => (float) $this->request->getPost('harga_min'),
            'harga_max'           => $this->request->getPost('harga_max') ? (float) $this->request->getPost('harga_max') : null,
            'tipe'                => $this->request->getPost('tipe'),
            'jumlah_kamar'        => (int) $this->request->getPost('jumlah_kamar'),
            'kamar_tersedia'      => (int) $this->request->getPost('kamar_tersedia'),
            'status_ketersediaan' => ((int) $this->request->getPost('kamar_tersedia') > 0) ? 'tersedia' : 'penuh',
            'status_publikasi'    => $this->request->getPost('status_publikasi') ?? 'aktif',
            'kontak_nama'         => $this->request->getPost('kontak_nama'),
            'kontak_wa'           => $this->formatWa($this->request->getPost('kontak_wa')),
            'kontak_telepon'      => $this->request->getPost('kontak_telepon'),
            'peraturan'           => $this->request->getPost('peraturan'),
            'is_unggulan'         => $this->request->getPost('is_unggulan') ? true : false,
        ];

        $this->kosModel->update($id, $dataUpdate);

        // Update Facilities
        $this->kosFasilitasModel->where('kos_id', $id)->delete();
        $fasilitas = $this->request->getPost('fasilitas');
        if (!empty($fasilitas) && is_array($fasilitas)) {
            foreach ($fasilitas as $fId) {
                $this->kosFasilitasModel->insert([
                    'kos_id'       => $id,
                    'fasilitas_id' => (int) $fId
                ]);
            }
        }

        return redirect()->to(base_url('admin/kos'))->with('success', 'Data kos "' . esc($nama) . '" berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $kos = $this->kosModel->find($id);
        if (!$kos) {
            return redirect()->to(base_url('admin/kos'))->with('error', 'Data kos tidak ditemukan.');
        }

        // Delete photo files
        $fotos = $this->kosFotoModel->where('kos_id', $id)->findAll();
        foreach ($fotos as $f) {
            if (strpos($f['path_file'], 'sample_') === false) {
                $fullPath = FCPATH . $f['path_file'];
                if (file_exists($fullPath)) {
                    @unlink($fullPath);
                }
            }
        }

        $this->kosModel->delete($id);

        return redirect()->to(base_url('admin/kos'))->with('success', 'Data kos "' . esc($kos['nama']) . '" berhasil dihapus.');
    }

    public function toggleStatus(int $id)
    {
        $kos = $this->kosModel->find($id);
        if (!$kos) {
            return redirect()->to(base_url('admin/kos'))->with('error', 'Data kos tidak ditemukan.');
        }

        $newStatus = ($kos['status_publikasi'] === 'aktif') ? 'nonaktif' : 'aktif';
        $this->kosModel->update($id, ['status_publikasi' => $newStatus]);

        return redirect()->back()->with('success', 'Status publikasi diubah menjadi "' . $newStatus . '".');
    }

    public function photos(int $id): string
    {
        $kos = $this->kosModel->find($id);
        if (!$kos) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data kos tidak ditemukan.');
        }

        $photos = $this->kosFotoModel->getByKosId($id);

        $data = [
            'title'  => 'Kelola Galeri Foto: ' . $kos['nama'] . ' — kosKu Admin',
            'kos'    => $kos,
            'photos' => $photos
        ];

        return view('admin/kos/photos', $data);
    }

    public function uploadPhotos(int $id)
    {
        $kos = $this->kosModel->find($id);
        if (!$kos) {
            return redirect()->to(base_url('admin/kos'))->with('error', 'Data kos tidak ditemukan.');
        }

        $photos = $this->request->getFileMultiple('fotos');
        $uploadedCount = 0;

        if ($photos) {
            $hasExistingPhotos = $this->kosFotoModel->where('kos_id', $id)->countAllResults() > 0;

            foreach ($photos as $photo) {
                if ($photo->isValid() && !$photo->hasMoved()) {
                    $newName = $photo->getRandomName();
                    $photo->move(FCPATH . 'uploads/kos', $newName);

                    $this->kosFotoModel->insert([
                        'kos_id'     => $id,
                        'path_file'  => 'uploads/kos/' . $newName,
                        'keterangan' => $this->request->getPost('keterangan') ?? 'Foto Kos',
                        'is_utama'   => (!$hasExistingPhotos && $uploadedCount === 0),
                        'urutan'     => 10,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                    $uploadedCount++;
                }
            }
        }

        return redirect()->back()->with('success', $uploadedCount . ' foto berhasil diunggah.');
    }

    public function setMainPhoto(int $kosId, int $photoId)
    {
        $this->kosFotoModel->where('kos_id', $kosId)->set(['is_utama' => false])->update();
        $this->kosFotoModel->update($photoId, ['is_utama' => true]);

        return redirect()->back()->with('success', 'Foto utama berhasil diubah.');
    }

    public function deletePhoto(int $kosId, int $photoId)
    {
        $photo = $this->kosFotoModel->where('kos_id', $kosId)->find($photoId);
        if ($photo) {
            if (strpos($photo['path_file'], 'sample_') === false) {
                $fullPath = FCPATH . $photo['path_file'];
                if (file_exists($fullPath)) {
                    @unlink($fullPath);
                }
            }
            $this->kosFotoModel->delete($photoId);
            return redirect()->back()->with('success', 'Foto berhasil dihapus.');
        }
        return redirect()->back()->with('error', 'Foto tidak ditemukan.');
    }

    private function formatWa($wa): string
    {
        $clean = preg_replace('/[^0-9]/', '', (string)$wa);
        if (str_starts_with($clean, '0')) {
            $clean = '62' . substr($clean, 1);
        }
        return $clean;
    }
}
