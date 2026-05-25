<?php

namespace App\Models\MySQL;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MySqlStudentSubmissions extends Model
{
    use HasFactory;

    protected $table = 'mysql_student_submissions';

    protected $fillable = [
        'enroll_id',
        'user_id',
        'topic_detail_id',
        'answer_number',
        'query_id',
        'feedback_id',
        'status',
    ];

    // Relasi BelongsTo dengan User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi: Setiap submission milik satu question
    public function question()
    {
        return $this->belongsTo(MySqlQuestions::class, 'question_id');
    }

    // Relasi: Setiap submission memiliki satu queries
    public function mysqlQuery()
    {
        return $this->belongsTo(MySqlQueries::class, 'query_id');
    }

    // Relasi: Setiap submission milik satu feedback
    public function feedback()
    {
        return $this->belongsTo(MySqlFeedbacks::class, 'feedback_id');
    }
}
