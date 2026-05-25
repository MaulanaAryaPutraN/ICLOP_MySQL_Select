<?php

namespace App\Models\React;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReactUserLogin extends Model
{
    protected $table    = 'users'; // Sesuaikan dengan nama tabel di database
    protected $fillable = ['name','email'];
}
