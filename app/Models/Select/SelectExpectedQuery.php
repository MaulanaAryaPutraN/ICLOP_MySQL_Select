<?php

// =============================================
// File: app/Models/Select/SelectExpectedQuery.php
// =============================================

namespace App\Models\Select;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectExpectedQuery extends Model
{
    use HasFactory;

    protected $table = 'select_expected_queries';

    protected $fillable = [
        'topic_detail_id',
        'type',
        'answer_number',
        'expected_query',
        'expected_table',
    ];

    // Relasi: ExpectedQuery milik satu subtopik
    public function topicDetail()
    {
        return $this->belongsTo(SelectTopicDetails::class, 'topic_detail_id');
    }
}
