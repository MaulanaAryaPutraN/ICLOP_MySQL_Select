<?php

namespace App\Models\Android;

use Illuminate\Database\Eloquent\Model;

class SubmitsFinalSubmission extends Model
{
    protected $table = "android_submits_submission";
    protected $fillable = ["user_id", "android_topic_id", "tipe", "userfile"];
}
