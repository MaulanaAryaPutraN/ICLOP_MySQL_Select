<?php

namespace App\Models\Android;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $table = "android_topics";
    protected $fillable = ['title', 'description', 'folder_path', 'picturePath', 'status'];

    public $timestamp = true;
    


    // create and update at menggunakan timestamp dari server (epoch)
    public function getCreatedAtAttribute( $value ) {

        return Carbon::parse($value)->timestamp;
    }
    public function getUpdatedAtAttribute( $value ) {

        return Carbon::parse($value)->timestamp;
    }
}
