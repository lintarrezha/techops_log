<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityType;

class ActivityTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['nama_kegiatan' => 'Monitoring Server',  'deskripsi' => 'Pemeriksaan rutin kondisi server harian', 'icon' => '🖥️'],
            ['nama_kegiatan' => 'Backup Server',      'deskripsi' => 'Pencatatan proses backup data server',    'icon' => '💾'],
            ['nama_kegiatan' => 'Troubleshooting',    'deskripsi' => 'Penanganan masalah dan insiden teknis',   'icon' => '🔧'],
            ['nama_kegiatan' => 'Maintenance',        'deskripsi' => 'Perawatan dan pemeliharaan perangkat',    'icon' => '⚙️'],
        ];

        foreach ($types as $type) {
            ActivityType::create($type);
        }
    }
}
