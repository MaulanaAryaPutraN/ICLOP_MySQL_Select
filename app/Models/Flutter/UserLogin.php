<?php

namespace App\Models\Flutter;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLogin extends Model
{
    protected $table    = 'users'; // Sesuaikan dengan nama tabel di database
    protected $fillable = ['name', 'email'];
}
