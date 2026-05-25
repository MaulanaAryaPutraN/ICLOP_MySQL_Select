<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlutterTestResult extends Model
{
    use HasFactory;

    protected $table = 'flutter_test_results'; // Sesuaikan dengan nama tabel di database
    protected $fillable = ['user_id', 'success_tests', 'failed_tests', 'score', 'flutterid'];
}
