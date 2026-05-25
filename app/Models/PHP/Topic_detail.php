<?php

namespace App\Models\PHP;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic_detail extends Model
{
    protected $table = 'php_topics_detail'; // Sesuaikan dengan nama tabel di database
    protected $fillable = ['title', 'id_topics' ,'controller', 'description', 'folder_path','file_name'];
}
