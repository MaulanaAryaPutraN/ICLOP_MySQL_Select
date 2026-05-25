<?php

namespace App\Models\Select;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectTopicDetails extends Model
{
    use HasFactory;

    protected $table = 'select_topic_details';

    protected $fillable = [
        'topic_id',
        'title',
        'file_path',
        'file_name',
        'created_by',
        'total_percobaan',
        'total_tugas',
    ];

    public function topic()
    {
        return $this->belongsTo(SelectTopics::class, 'topic_id');
    }

    public function expectedQueries()
    {
        return $this->hasMany(SelectExpectedQuery::class, 'topic_detail_id');
    }

    public function percobaans()
    {
        return $this->hasMany(SelectExpectedQuery::class, 'topic_detail_id')->where('type', 'percobaan')->orderBy('answer_number');
    }

    public function tugas()
    {
        return $this->hasMany(SelectExpectedQuery::class, 'topic_detail_id')->where('type', 'tugas')->orderBy('answer_number');
    }

    public function studentSubmissions()
    {
        return $this->hasMany(SelectStudentSubmissions::class, 'topic_detail_id');
    }
}
