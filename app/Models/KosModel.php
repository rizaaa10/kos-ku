<?php

namespace App\Models;

use CodeIgniter\Model;

class KosModel extends Model
{
    protected $table            = 'kos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama', 'slug', 'deskripsi', 'alamat', 'wilayah_id',
        'latitude', 'longitude', 'harga_min', 'harga_max',
        'tipe', 'jumlah_kamar', 'kamar_tersedia', 'status_ketersediaan',
        'status_publikasi', 'kontak_nama', 'kontak_wa', 'kontak_telepon',
        'peraturan', 'is_unggulan', 'created_at', 'updated_at'
    ];
    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    public function generateSlug(string $nama, ?int $ignoreId = null): string
    {
        $baseSlug = url_title($nama, '-', true);
        $slug = $baseSlug;
        $counter = 1;

        while (true) {
            $builder = $this->where('slug', $slug);
            if ($ignoreId !== null) {
                $builder->where('id !=', $ignoreId);
            }
            if ($builder->countAllResults() === 0) {
                break;
            }
            $counter++;
            $slug = "{$baseSlug}-{$counter}";
        }

        return $slug;
    }

    /**
     * Get filtered kos list for public map and list views
     */
    public function getFilteredList(array $filters = [], string $statusPublikasi = 'aktif')
    {
        $builder = $this->select('kos.*, wilayah.nama as nama_wilayah, wilayah.tipe as tipe_wilayah')
                        ->join('wilayah', 'wilayah.id = kos.wilayah_id', 'left');

        if ($statusPublikasi !== 'all') {
            $builder->where('kos.status_publikasi', $statusPublikasi);
        }

        if (!empty($filters['wilayah_id'])) {
            $builder->where('kos.wilayah_id', (int) $filters['wilayah_id']);
        }

        if (!empty($filters['tipe'])) {
            $builder->where('kos.tipe', strtolower($filters['tipe']));
        }

        if (!empty($filters['status_ketersediaan'])) {
            $builder->where('kos.status_ketersediaan', strtolower($filters['status_ketersediaan']));
        }

        if (!empty($filters['harga_min'])) {
            $builder->where('kos.harga_min >=', (float) $filters['harga_min']);
        }

        if (!empty($filters['harga_max'])) {
            $builder->where('kos.harga_min <=', (float) $filters['harga_max']);
        }

        if (!empty($filters['q'])) {
            $builder->groupStart()
                    ->like('LOWER(kos.nama)', strtolower($filters['q']))
                    ->orLike('LOWER(kos.alamat)', strtolower($filters['q']))
                    ->orLike('LOWER(wilayah.nama)', strtolower($filters['q']))
                    ->groupEnd();
        }

        // Filter by facilities (AND logic)
        if (!empty($filters['fasilitas']) && is_array($filters['fasilitas'])) {
            foreach ($filters['fasilitas'] as $fasilitasId) {
                $fasilitasId = (int)$fasilitasId;
                if ($fasilitasId > 0) {
                    $builder->where("kos.id IN (SELECT kos_id FROM kos_fasilitas WHERE fasilitas_id = {$fasilitasId})");
                }
            }
        }

        // Sorting
        $sort = $filters['sort'] ?? 'terbaru';
        if ($sort === 'harga_asc') {
            $builder->orderBy('kos.harga_min', 'ASC');
        } elseif ($sort === 'harga_desc') {
            $builder->orderBy('kos.harga_min', 'DESC');
        } elseif ($sort === 'kamar_tersedia') {
            $builder->orderBy('kos.kamar_tersedia', 'DESC');
        } else {
            $builder->orderBy('kos.is_unggulan', 'DESC')
                    ->orderBy('kos.created_at', 'DESC');
        }

        $items = $builder->findAll();

        if (empty($items)) {
            return [];
        }

        // Attach main photo and primary facilities for cards
        $fotoModel = new KosFotoModel();
        $fasilitasModel = new KosFasilitasModel();

        foreach ($items as &$item) {
            $item['foto_utama'] = $fotoModel->where('kos_id', $item['id'])
                                             ->orderBy('is_utama', 'DESC')
                                             ->orderBy('urutan', 'ASC')
                                             ->first();
            $item['fasilitas'] = $fasilitasModel->getFasilitasByKosId($item['id']);
        }

        return $items;
    }

    /**
     * Get detail kos by ID or Slug with photos and facilities
     */
    public function getDetail(string|int $idOrSlug)
    {
        $builder = $this->select('kos.*, wilayah.nama as nama_wilayah, wilayah.tipe as tipe_wilayah, wilayah.center_lat as wilayah_lat, wilayah.center_lng as wilayah_lng')
                        ->join('wilayah', 'wilayah.id = kos.wilayah_id', 'left');

        if (is_numeric($idOrSlug)) {
            $kos = $builder->where('kos.id', (int) $idOrSlug)->first();
        } else {
            $kos = $builder->where('kos.slug', $idOrSlug)->first();
        }

        if (!$kos) {
            return null;
        }

        $fotoModel = new KosFotoModel();
        $fasilitasModel = new KosFasilitasModel();

        $kos['fotos'] = $fotoModel->getByKosId((int) $kos['id']);
        $kos['fasilitas'] = $fasilitasModel->getFasilitasByKosId((int) $kos['id']);

        return $kos;
    }
}
