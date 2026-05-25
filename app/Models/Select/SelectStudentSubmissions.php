<?php

namespace App\Models\Select;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectStudentSubmissions extends Model
{
    use HasFactory;

    protected $table = 'select_student_submissions';

    protected $fillable = [
        'enroll_id',
        'user_id',
        'topic_detail_id',
        'answer_number',
        'query_id',
        'feedback_id',
        'status',
        'submission_type', // TAMBAHAN: 'percobaan' | 'tugas'
    ];

    // Relasi BelongsTo dengan User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi: Setiap submission memiliki satu queries
    public function selectQuery()
    {
        return $this->belongsTo(SelectQueries::class, 'query_id');
    }

    // Relasi: Setiap submission milik satu feedback
    public function feedback()
    {
        return $this->belongsTo(SelectFeedbacks::class, 'feedback_id');
    }
}
