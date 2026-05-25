<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MySqlTopicsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar data yang akan disimpan
        $topics = [
            [
                'title' => 'Data Manipulation Language',
            ],
            [
                'title' => 'Database Design Basics',
            ],
            [
                'title' => 'SQL Queries and Joins',
            ],
        ];

        // Loop melalui setiap data dan gunakan updateOrInsert
        foreach ($topics as $topic) {
            DB::table('mysql_topics')->updateOrInsert(
                ['title' => $topic['title']], // Kondisi: Cari berdasarkan kolom 'title'
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
