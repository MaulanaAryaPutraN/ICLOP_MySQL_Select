<?php

namespace App\Models\MySQL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MySqlTopicDetails extends Model
{
    use HasFactory;

    protected $table = 'mysql_topic_details'; //Name of the table in the database

    protected $fillable = [
        'topic_id',
        'title',
        'file_path',
        'file_name',
        'created_by',
        'total_question'
    ];

    // Relasi: Setiap topic detail milik satu topic
    public function topic()
    {
        return $this->belongsTo(MySqlTopics::class, 'topic_id');
    }

    // Relasi: Setiap topic detail memiliki banyak questions
    public function questions()
    {
        return $this->hasMany(MySqlQuestions::class, 'topic_detail_id');
    }

    // Relasi: Setiap topic detail memiliki banyak student submissions
    public function studentSubmissions()
    {
        return $this->hasMany(MySqlStudentSubmissions::class, 'topic_detail_id');
    }

    // Relasi: Setiap topic detail memiliki banyak expected queries
    public function expectedQueries()
    {
        return $this->hasMany(MysqlExpectedQuery::class, 'topic_detail_id');
    }
}
