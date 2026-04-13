<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChecklistTemplate;

class ChecklistTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // Monitoring Server (activity_type_id = 1)
        $templates = [
            // Section A
            ['activity_type_id' => 1, 'section_label' => 'A', 'section_name' => 'Status Umum Server', 'pertanyaan' => 'Kondisi server saat ini', 'tipe_input' => 'radio', 'opsi_jawaban' => ['Normal', 'Ada Kendala', 'Kritis'], 'satuan' => null, 'is_required' => true, 'urutan' => 1],
            ['activity_type_id' => 1, 'section_label' => 'A', 'section_name' => 'Status Umum Server', 'pertanyaan' => 'Server yang dimonitor', 'tipe_input' => 'select', 'opsi_jawaban' => ['DB-01 (Production)', 'DB-02 (Staging)', 'APP-01', 'BACKUP-01'], 'satuan' => null, 'is_required' => true, 'urutan' => 2],
            // Section B
            ['activity_type_id' => 1, 'section_label' => 'B', 'section_name' => 'Parameter Teknis', 'pertanyaan' => 'CPU Usage', 'tipe_input' => 'number', 'opsi_jawaban' => null, 'satuan' => '%', 'is_required' => true, 'urutan' => 3],
            ['activity_type_id' => 1, 'section_label' => 'B', 'section_name' => 'Parameter Teknis', 'pertanyaan' => 'RAM Usage', 'tipe_input' => 'number', 'opsi_jawaban' => null, 'satuan' => '%', 'is_required' => true, 'urutan' => 4],
            ['activity_type_id' => 1, 'section_label' => 'B', 'section_name' => 'Parameter Teknis', 'pertanyaan' => 'Sisa storage disk', 'tipe_input' => 'number', 'opsi_jawaban' => null, 'satuan' => 'GB', 'is_required' => true, 'urutan' => 5],
            ['activity_type_id' => 1, 'section_label' => 'B', 'section_name' => 'Parameter Teknis', 'pertanyaan' => 'Status koneksi network', 'tipe_input' => 'radio', 'opsi_jawaban' => ['Stabil', 'Intermiten', 'Terputus'], 'satuan' => null, 'is_required' => true, 'urutan' => 6],
            // Section C
            ['activity_type_id' => 1, 'section_label' => 'C', 'section_name' => 'Status Service', 'pertanyaan' => 'Status service database (PostgreSQL)', 'tipe_input' => 'radio', 'opsi_jawaban' => ['Running', 'Slow', 'Down'], 'satuan' => null, 'is_required' => false, 'urutan' => 7],
            ['activity_type_id' => 1, 'section_label' => 'C', 'section_name' => 'Status Service', 'pertanyaan' => 'Status service aplikasi (Nginx)', 'tipe_input' => 'radio', 'opsi_jawaban' => ['Running', 'Slow', 'Down'], 'satuan' => null, 'is_required' => false, 'urutan' => 8],
            // Section D
            ['activity_type_id' => 1, 'section_label' => 'D', 'section_name' => 'Log & Catatan', 'pertanyaan' => 'Temuan error log', 'tipe_input' => 'radio', 'opsi_jawaban' => ['Tidak Ada', 'Ada Error'], 'satuan' => null, 'is_required' => false, 'urutan' => 9],
            ['activity_type_id' => 1, 'section_label' => 'D', 'section_name' => 'Log & Catatan', 'pertanyaan' => 'Catatan tambahan', 'tipe_input' => 'textarea', 'opsi_jawaban' => null, 'satuan' => null, 'is_required' => false, 'urutan' => 10],
        ];

        foreach ($templates as $t) {
            ChecklistTemplate::create($t);
        }
    }
}
