<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MySqlTopicDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar data yang akan disimpan
        $topics = [
            [
                'topic_id' => 1,
                'title' => 'INSERT',
                'description' => 'Learn how to insert data into a database table.',
                'folder_path' => 'C:\xampp\htdocs\iclop\public\mysql/DML/',
                'file_name' => 'MySQL-DML 2022.pdf',
                'flag' => '1',
            ],
            [
                'topic_id' => 1,
                'title' => 'UPDATE',
                'description' => 'Learn how to update existing data in a database table.',
                'folder_path' => 'C:\xampp\htdocs\iclop\public\mysql/DML/',
                'file_name' => 'MySQL-DML 2022.pdf',
                'flag' => '1',
            ],
            [
                'topic_id' => 1,
                'title' => 'DELETE',
                'description' => 'Learn how to delete data from a database table.',
                'folder_path' => 'C:\xampp\htdocs\iclop\public\mysql/DML/',
                'file_name' => 'MySQL-DML 2022.pdf',
                'flag' => '1',
            ],
        ];

        // Loop melalui setiap data dan gunakan updateOrInsert
        foreach ($topics as $topic) {
            DB::table('mysql_topic_details')->updateOrInsert(
                ['title' => $topic['title']], // Kondisi: Cari berdasarkan kolom 'title'
                [
                    'topic_id' => $topic['topic_id'],
                    'description' => $topic['description'],
                    'folder_path' => $topic['folder_path'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
