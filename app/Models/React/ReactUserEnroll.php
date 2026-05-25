<?php

namespace App\Models\React;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReactUserEnroll extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'id_users',
        'php_topics_detail_id',
        'created_at'
    ];
    protected $table = 'react_student_enroll';

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_users');
    }

    public function reactTopicDetail()
    {
        return $this->belongsTo(ReactTopic_detail::class, 'php_topics_detail_id');

    }
}
