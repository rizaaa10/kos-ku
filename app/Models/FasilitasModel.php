<?php

namespace App\Models;

use CodeIgniter\Model;

class FasilitasModel extends Model
{
    protected $table            = 'fasilitas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama', 'ikon', 'kategori', 'created_at', 'updated_at'
    ];
    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}
