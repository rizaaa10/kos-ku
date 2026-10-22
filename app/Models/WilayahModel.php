<?php

namespace App\Models;

use CodeIgniter\Model;

class WilayahModel extends Model
{
    protected $table            = 'wilayah';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama', 'tipe', 'geojson', 'center_lat', 'center_lng', 'zoom_default',
        'created_at', 'updated_at'
    ];
    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    public function getWithKosCount()
    {
        return $this->select('wilayah.*, COUNT(kos.id) as total_kos')
                    ->join('kos', 'kos.wilayah_id = wilayah.id AND kos.status_publikasi = \'aktif\'', 'left')
                    ->groupBy('wilayah.id')
                    ->orderBy('wilayah.nama', 'ASC')
                    ->findAll();
    }
}
