<?php

namespace App\Models\MySQL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MySqlQueries extends Model
{
    use HasFactory;

    protected $table = 'mysql_queries';

    protected $fillable = [
        'query',
    ];

    // Relasi: Setiap query memiliki satu feedback
    public function feedback()
    {
        return $this->hasOne(MySqlFeedbacks::class, 'query_id');
    }

    public function submissions()
    {
        return $this->hasMany(MySqlStudentSubmissions::class, 'query_id');
    }
}
