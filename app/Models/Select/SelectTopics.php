<?php

namespace App\Models\Select;


use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectTopics extends Model
{
    use HasFactory;

    protected $table = 'select_topics';

    protected $fillable = [
        'title',
        'created_by',
        'countdown_seconds',
        'schema_file_name',
        'schema_file_path',
        'is_sequential',
    ];

    public function topicDetails()
    {
        return $this->hasMany(SelectTopicDetails::class, 'topic_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
