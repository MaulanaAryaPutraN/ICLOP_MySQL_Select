<?php

namespace App\Models\Android;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = "android_task";
    protected $fillable = ['android_topic_id', 'task_no', 'task_name', 'caption', 'material', 'tipe'];

    public $timestamp = true;
    


    // create and update at menggunakan timestamp dari server (epoch)
    public function getCreatedAtAttribute( $value ) {

        return Carbon::parse($value)->timestamp;
    }
    public function getUpdatedAtAttribute( $value ) {

        return Carbon::parse($value)->timestamp;
    }
}
