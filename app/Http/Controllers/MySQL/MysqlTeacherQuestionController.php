<?php

namespace App\Http\Controllers\MySQL;

use App\Http\Controllers\Controller;
use App\Models\MySQL\MysqlExpectedQuery;
use App\Models\MySQL\MySqlTopicDetails;
use App\Models\MySQL\MySqlTopics;
use Illuminate\Http\Request;

class MysqlTeacherQuestionController extends Controller
{
    public function questionsTable()
    {
        $questions = MysqlExpectedQuery::all();
        $topics = MySqlTopics::all();
        $subtopics = MySqlTopicDetails::all();
        return view('mysql_dml.teacher.questions_management', [
            'questions' => $questions,
            'topics' => $topics,
            'subtopics' => $subtopics
        ]);
    }

    public function saveQuestion(Request $request)
    {
        $validated = $request->validate([
            'topic_detail_id' => 'required|exists:mysql_topic_details,id',
            'answer_number' => 'required|integer|min:1',
            'expected_query' => 'required|string',
            'expected_table' => 'nullable|string',
        ]);

        // Simpan atau update question
        MysqlExpectedQuery::updateOrCreate(
            [
                'topic_detail_id' => $validated['topic_detail_id'],
                'answer_number' => $validated['answer_number'],
            ],
            [
                'expected_query' => $validated['expected_query'],
                'expected_table' => $validated['expected_table'],
            ]
        );

        // Update kolom total_question jika perlu
        $subtopic = MySqlTopicDetails::find($validated['topic_detail_id']);
        if ($subtopic && $validated['answer_number'] > $subtopic->total_question) {
            $subtopic->total_question = $validated['answer_number'];
            $subtopic->save();
        }

        return response()->json(['success' => true]);
    }

    public function getQuestionList()
    {
        return MysqlExpectedQuery::all();
    }

    public function getSubtopicList()
    {
        return MySqlTopicDetails::all();
    }

    public function getTopicList()
    {
        return MySqlTopics::all();
    }

    public function deleteQuestion($id)
    {
        // Temukan question yang akan dihapus
        $question = MysqlExpectedQuery::find($id);

        if (!$question) {
            return response()->json(['success' => false, 'message' => 'Question not found.'], 404);
        }

        $topicDetailId = $question->topic_detail_id;

        // Hapus question
        $deleted = $question->delete();

        // Hitung ulang total_question untuk subtopic terkait
        $maxAnswerNumber = MysqlExpectedQuery::where('topic_detail_id', $topicDetailId)->max('answer_number');
        $subtopic = MySqlTopicDetails::find($topicDetailId);
        if ($subtopic) {
            $subtopic->total_question = $maxAnswerNumber ? $maxAnswerNumber : 0;
            $subtopic->save();
        }

        return response()->json(['success' => $deleted]);
    }
}
