<?php

namespace App\Models\MySQL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MysqlExpectedQuery extends Model
{
    use HasFactory;

    protected $table = 'mysql_expected_queries';

    protected $fillable = [
        'topic_detail_id',
        'answer_number',
        'expected_query',
        'expected_table',
    ];

    // Relasi: ExpectedQuery milik satu subtopik
    public function topicDetail()
    {
        return $this->belongsTo(MySqlTopicDetails::class, 'topic_detail_id');
    }
}
