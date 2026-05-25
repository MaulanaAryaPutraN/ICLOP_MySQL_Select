<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskSubmission extends Model
{
    protected $table = 'php_submits_submission'; // Sesuaikan dengan nama tabel di database
    protected $fillable = ['username','userfile','ket'];
}
