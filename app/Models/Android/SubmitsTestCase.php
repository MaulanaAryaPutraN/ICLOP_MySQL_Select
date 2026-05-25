<?php

namespace App\Models\Android;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Android\Testcase;

class SubmitsTestCase extends Model
{
    protected $table = "android_submits_testcase";
    protected $fillable = ["user_id", "android_submit_id", "android_testcase_id", "status", "status_waiting"];

    public function android_testcase() : BelongsTo{

    	return $this->belongsTo( Testcase::class, 'android_testcase_id', 'id' );
    }
}
