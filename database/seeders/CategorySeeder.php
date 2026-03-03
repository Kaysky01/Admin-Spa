<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Plagiarisme',
                'icon' => 'fas fa-copy',
                'description' => 'Laporan terkait plagiarisme atau menjiplak karya orang lain'
            ],
            [
                'name' => 'Menyontek',
                'icon' => 'fas fa-user-secret',
                'description' => 'Laporan terkait kecurangan saat ujian atau tugas'
            ],
            [
                'name' => 'Titip Absen',
                'icon' => 'fas fa-user-check',
                'description' => 'Laporan terkait penitipan absensi atau kehadiran palsu'
            ],
            [
                'name' => 'Kecurangan Ujian',
                'icon' => 'fas fa-exclamation-triangle',
                'description' => 'Laporan terkait kecurangan dalam pelaksanaan ujian'
            ],
            [
                'name' => 'Pemalsuan Data',
                'icon' => 'fas fa-file-signature',
                'description' => 'Laporan terkait pemalsuan dokumen atau data akademik'
            ],
            [
                'name' => 'Lainnya',
                'icon' => 'fas fa-ellipsis-h',
                'description' => 'Laporan pelanggaran atau kejadian lainnya'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
