<?php

namespace App\Models;

use CodeIgniter\Model;

class KosKunjunganModel extends Model
{
    protected $table            = 'kos_kunjungan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['kos_id', 'jenis', 'ip_address', 'user_agent', 'created_at'];
    protected $useTimestamps    = false;

    public function recordVisit(int $kosId, string $jenis, ?string $ip = null, ?string $userAgent = null)
    {
        return $this->insert([
            'kos_id'     => $kosId,
            'jenis'      => $jenis,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function getSummaryStats()
    {
        $db = \Config\Database::connect();
        $totalViews = $this->where('jenis', 'view')->countAllResults();
        $totalWA    = $this->where('jenis', 'klik_wa')->countAllResults();
        $totalMaps  = $this->where('jenis', 'klik_maps')->countAllResults();

        // 7 days weekly chart
        $days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $count = $this->where('DATE(created_at)', $date)->countAllResults();
            $days[] = [
                'date'  => date('D, d M', strtotime($date)),
                'short' => date('d/m', strtotime($date)),
                'count' => $count
            ];
        }

        return [
            'total_views' => $totalViews,
            'total_wa'    => $totalWA,
            'total_maps'  => $totalMaps,
            'weekly'      => $days
        ];
    }
}
