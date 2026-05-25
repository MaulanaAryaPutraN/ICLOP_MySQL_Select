<?php

namespace App\Models\Flutter;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCheck extends Model
{
    protected $table    = 'flutter_user_submits'; // Sesuaikan dengan nama tabel di database
    protected $fillable = ['userid'];
}
