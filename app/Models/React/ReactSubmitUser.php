<?php

namespace App\Models\React;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\React\ReactTopic_detail;

class ReactSubmitUser extends Model
{
    use HasFactory;

    protected $table = 'react_submit_user';

    protected $fillable = [
        'id_user', 'nama_user', 'materi', 'topic_id', 'nilai', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function topicDetail()
    {
        return $this->belongsTo(ReactTopic_detail::class, 'topic_id');
    }
}
