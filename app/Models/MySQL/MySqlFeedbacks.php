<?php

namespace App\Models\MySQL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MySqlFeedbacks extends Model
{
    use HasFactory;

    protected $table = 'mysql_feedbacks'; //Name of the table in the database

    protected $fillable = [
        'query_id',
        'feedback',
    ];

    // Relasi: Setiap feedback memiliki banyak student submissions
    public function studentSubmissions()
    {
        return $this->hasMany(MySqlStudentSubmissions::class, 'feedback_id');
    }

    // Relasi: Setiap feedback milik satu query
    public function mysqlQuery()
    {
        return $this->belongsTo(MySqlQueries::class, 'query_id');
    }
}
