<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

//         \App\Models\User::factory()->create([
//             'name' => 'Test User',
//             'email' => 'test@example.com',
//         ]);

        // $admin = new User();
        // $admin->name = 'AdminUser';
        // $admin->email = 'admin@admin.com';
        // $admin->password = Hash::make('password');
        // $admin->save();
        // $admin->addRole('teacher');

         // Panggil seeder untuk mysql_topics terlebih dahulu
         $this->call(MySqlTopicsSeeder::class);

         // Panggil seeder untuk mysql_topic_details
         $this->call(MySqlTopicDetailsSeeder::class);
 
         // Panggil seeder untuk mysql_queries
         $this->call(MySqlFeedbackSeeder::class);
 
         // Panggil seeder untuk mysql_student_submissions
         $this->call(MySqlStudentSubmissionsSeeder::class);
    }
}
