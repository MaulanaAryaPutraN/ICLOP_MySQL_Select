<?php

namespace App\Models\Select;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectFeedbacks extends Model
{
    use HasFactory;

    protected $table = 'select_feedbacks';

    protected $fillable = [
        'query_id',
        'feedback',
        'validation_error', // TAMBAHAN: detail error dari myTAP
    ];

    // Relasi: Setiap feedback memiliki banyak student submissions
    public function studentSubmissions()
    {
        return $this->hasMany(SelectStudentSubmissions::class, 'feedback_id');
    }

    // Relasi: Setiap feedback milik satu query
    public function selectQuery()
    {
        return $this->belongsTo(SelectQueries::class, 'query_id');
    }
}
