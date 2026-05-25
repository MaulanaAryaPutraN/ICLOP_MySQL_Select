<?php
// =============================================
// File: app/Http/Controllers/Select/SelectTeacherQuestionController.php
// =============================================
namespace App\Http\Controllers\Select;

use App\Http\Controllers\Controller;
use App\Models\Select\SelectExpectedQuery;
use App\Models\Select\SelectTopicDetails;
use App\Models\Select\SelectTopics;
use Illuminate\Http\Request;

class SelectTeacherQuestionController extends Controller
{
    public function questionsTable()
    {
        $questions = SelectExpectedQuery::with('topicDetail')->get();
        $topics    = SelectTopics::all();
        $subtopics = SelectTopicDetails::all();
        return view('select.teacher.questions_management', compact('questions', 'topics', 'subtopics'));
    }

    public function saveQuestion(Request $request)
    {
        $validated = $request->validate([
            'topic_detail_id' => 'required|exists:select_topic_details,id',
            'type'            => 'required|in:percobaan,tugas',
            'answer_number'   => 'required|integer',
            'expected_query'  => 'required|string',
            'expected_table'  => 'nullable|string',
        ]);

        SelectExpectedQuery::updateOrCreate(
            [
                'topic_detail_id' => $validated['topic_detail_id'],
                'type'            => $validated['type'],
                'answer_number'   => $validated['answer_number'],
            ],
            [
                'expected_query' => $validated['expected_query'],
                'expected_table' => $validated['expected_table'],
            ]
        );

        // Update total_percobaan / total_tugas di subtopik
        $subtopic = SelectTopicDetails::find($validated['topic_detail_id']);
        if ($subtopic) {
            $maxPercobaan = SelectExpectedQuery::where('topic_detail_id', $subtopic->id)->where('type', 'percobaan')->max('answer_number') ?? 0;
            $maxTugas     = SelectExpectedQuery::where('topic_detail_id', $subtopic->id)->where('type', 'tugas')->max('answer_number') ?? 0;
            $subtopic->total_percobaan = $maxPercobaan;
            $subtopic->total_tugas     = $maxTugas;
            $subtopic->save();
        }

        return response()->json(['success' => true]);
    }

    public function deleteQuestion($id)
    {
        $question = SelectExpectedQuery::find($id);
        if (!$question) return response()->json(['success' => false, 'message' => 'Not found.'], 404);

        $topicDetailId = $question->topic_detail_id;
        $type          = $question->type;
        $question->delete();

        $subtopic = SelectTopicDetails::find($topicDetailId);
        if ($subtopic) {
            $maxPercobaan = SelectExpectedQuery::where('topic_detail_id', $topicDetailId)->where('type', 'percobaan')->max('answer_number') ?? 0;
            $maxTugas     = SelectExpectedQuery::where('topic_detail_id', $topicDetailId)->where('type', 'tugas')->max('answer_number') ?? 0;
            $subtopic->total_percobaan = $maxPercobaan;
            $subtopic->total_tugas     = $maxTugas;
            $subtopic->save();
        }

        return response()->json(['success' => true]);
    }

    public function getQuestionList()
    {
        return SelectExpectedQuery::with('topicDetail')->get();
    }
    public function getSubtopicList()
    {
        return SelectTopicDetails::all();
    }
    public function getTopicList()
    {
        return SelectTopics::all();
    }
}
