<?php

namespace App\Models\Android;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Android\Topic;
use App\Models\Android\Task;
use App\Models\User;
use App\Models\Android\SubmitsTestCase;

class Submits extends Model
{
    protected $table = "android_submits";
    protected $fillable = ["user_id", "android_topic_id", "android_task_id", "duration", "upload", "comment", "validator"];


    public function topic() : BelongsTo {

    	return $this->belongsTo(Topic::class, 'android_topic_id', 'id');
    }

    public function task() : BelongsTo {

    	return $this->belongsTo(Task::class, 'android_task_id', 'id');
    }

    public function user() : BelongsTo {

    	return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
