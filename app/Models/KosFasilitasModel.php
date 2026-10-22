<?php

namespace App\Models;

use CodeIgniter\Model;

class KosFasilitasModel extends Model
{
    protected $table            = 'kos_fasilitas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['kos_id', 'fasilitas_id'];
    protected $useTimestamps    = false;

    public function getFasilitasByKosId(int $kosId)
    {
        return $this->select('fasilitas.*')
                    ->join('fasilitas', 'fasilitas.id = kos_fasilitas.fasilitas_id')
                    ->where('kos_fasilitas.kos_id', $kosId)
                    ->orderBy('fasilitas.kategori', 'ASC')
                    ->orderBy('fasilitas.nama', 'ASC')
                    ->findAll();
    }
}
