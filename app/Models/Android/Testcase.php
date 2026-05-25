<?php

namespace App\Models\Android;

use Illuminate\Database\Eloquent\Model;

class Testcase extends Model
{
    protected $table = "android_testcase";
    protected $fillable = ["task_id", "case", "score"];

    public $timestamps = false;
}
