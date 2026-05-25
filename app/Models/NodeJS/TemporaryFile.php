<?php

namespace App\Models\NodeJS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryFile extends Model
{
    use HasFactory;

    protected $connection = 'nodejsDB';

    protected $fillable = [
        'folder_path',
        'file_name',
    ];
}
