<?php

namespace App\Models\PHP;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCheck extends Model
{
    protected $table    = 'php_user_submits'; // Sesuaikan dengan nama tabel di database
    protected $fillable = ['userid'];
}
