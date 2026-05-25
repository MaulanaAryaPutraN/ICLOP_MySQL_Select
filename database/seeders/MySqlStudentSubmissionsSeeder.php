<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MySqlStudentSubmissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_id = 1631; // ID pengguna yang ada di tabel users

        // Daftar data yang akan disimpan
        $submissions = [
            [
                'user_id' => $user_id,
                'topic_detail_id' => 1,
                'feedback_id' => 1,
                'query' => 'SELECT * FROM mysql_topic_details;',
            ],
            [
                'user_id' => $user_id,
                'topic_detail_id' => 2,
                'feedback_id' => 2,
                'query' => 'INSERT INTO mysql_topics (title) VALUES ("New Topic");',
            ],
            [
                'user_id' => $user_id,
                'topic_detail_id' => 3,
                'feedback_id' => 3,
                'query' => 'UPDATE mysql_topics SET title = "Updated Title" WHERE id = 1;',
            ],
        ];

        // Loop melalui setiap data dan gunakan updateOrInsert
        foreach ($submissions as $submission) {
            DB::table('mysql_student_submissions')->updateOrInsert(
                ['query' => $submission['query']], // Kondisi: Cari berdasarkan kolom 'query'
                [
                    'user_id' => $submission['user_id'],
                    'topic_detail_id' => $submission['topic_detail_id'],
                    'feedback_id' => $submission['feedback_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}