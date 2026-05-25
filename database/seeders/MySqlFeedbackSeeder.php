<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MySqlFeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar data pesan kesalahan yang akan disimpan
        $feedbacks = [
            [
                'feedback' => 'Error: Syntax error near unexpected token \'FROM\'',
            ],
            [
                'feedback' => 'Error: Unknown column \'users.name\' in \'field list\'',
            ],
            [
                'feedback' => 'Error: Table \'mysql_topics\' doesn\'t exist',
            ],
            [
                'feedback' => 'Warning: Query is not optimized. Consider adding indexes.',
            ],
            [
                'feedback' => 'Error: Duplicate entry \'1\' for key \'PRIMARY\'',
            ],
            [
                'feedback' => 'Error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version.',
            ],
            [
                'feedback' => 'Error: Cannot add or update a child row: a foreign key constraint fails.',
            ],
            [
                'feedback' => 'Warning: Large dataset detected. Query may take longer to execute.',
            ],
        ];

        // Loop melalui setiap data dan gunakan updateOrInsert
        foreach ($feedbacks as $feedback) {
            DB::table('mysql_feedback')->updateOrInsert(
                ['feedback' => $feedback['feedback']], // Kondisi: Cari berdasarkan kolom 'query'
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}