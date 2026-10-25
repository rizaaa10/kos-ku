<?php

namespace App\Models;

use CodeIgniter\Model;

class KosFotoModel extends Model
{
    protected $table            = 'kos_foto';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kos_id', 'path_file', 'keterangan', 'is_utama', 'urutan', 'created_at'
    ];
    protected $useTimestamps    = false;

    public function getByKosId(int $kosId)
    {
        return $this->where('kos_id', $kosId)
                    ->whereNotIn('path_file', [
                        'uploads/kos/sample_kos_1.svg',
                        'uploads/kos/sample_kos_2.svg',
                        'uploads/kos/sample_kos_3.svg',
                    ])
                    ->orderBy('is_utama', 'DESC')
                    ->orderBy('urutan', 'ASC')
                    ->orderBy('id', 'ASC')
                    ->findAll();
    }
}
