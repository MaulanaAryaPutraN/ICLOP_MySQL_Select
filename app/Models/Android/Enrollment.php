<?php

namespace App\Models\Android;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    protected $table = "android_enrollment";
    protected $fillable = ["android_topic_id", "user_id", "status"];


    public function user() : BelongsTo {

    	return $this->belongsTo(User::class);
    }
}
