<?php

namespace App\Models\Select;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectQueries extends Model

{
    use HasFactory;

    protected $table = 'select_queries';

    protected $fillable = [
        'query',
    ];

    // Relasi: Setiap query memiliki satu feedback
    public function feedback()
    {
        return $this->hasOne(SelectFeedbacks::class, 'query_id');
    }

    public function submissions()
    {
        return $this->hasMany(SelectStudentSubmissions::class, 'query_id');
    }
}
