<?php

namespace App\Models\Android;

use Illuminate\Database\Eloquent\Model;

class Task_waiting extends Model
{
    protected $table = "android_task_waiting";
    protected $fillable = ["user_id", "android_topic_id", "android_task_id"];
}
