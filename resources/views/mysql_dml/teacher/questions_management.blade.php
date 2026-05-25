{{-- filepath: /d:/Semester 8 (Skripsi)/Skripsi/Project/iClOP_V2_MySql_DML/resources/views/mysql_dml/teacher/answer_key.blade.php --}}
<div>
    <div class="mb-0 d-flex justify-content-between align-items-center">
        <h4 class="mb-5 fw-bold">Questions Management</h4>
    </div>
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div class="d-flex gap-3 align-items-end">
            <div class="d-flex flex-column align-items-start" style="min-width: 180px;">
                <h5 class="fw-semibold">Topic :</h5>
                <button id="filterTopicBtn" class="btn btn-outline-primary filter-btn" data-bs-toggle="modal" data-bs-target="#filterTopicModal" type="button">
                    <span class="filter-label" id="filterTopicLabel">Filter by Topic</span>
                </button>
            </div>
            <div class="d-flex flex-column align-items-start" style="min-width: 180px;">
                <h5 class="fw-semibold">Sub-Topic :</h5>
                <button id="filterSubtopicBtn" class="btn btn-outline-primary filter-btn" data-bs-toggle="modal" data-bs-target="#filterSubtopicModal" type="button">
                    <span class="filter-label" id="filterSubtopicLabel">Filter by Subtopic</span>
                </button>
            </div>
            <div class="d-flex flex-column justify-content-end" style="min-width: 140px; height: 100%;">
                <span style="flex:1"></span>
                <button id="resetFilterBtn" class="btn btn-outline-secondary mt-2" style="border-radius: 18px; font-weight: 500;">
                    Reset Filter
                </button>
            </div>
        </div>
        <div>
            <button class="btn btn-primary fw-bold" id="addQuestionBtn" style="border-radius: 0.5rem;">
                <i class="fas fa-plus"></i> Add Question
            </button>
        </div>
    </div>
    <div class="card shadow-sm p-4 mb-4" style="border-radius: 18px;">
        <div id="questions-table-container">
            @include('mysql_dml.teacher.table.questions_table')
        </div>
    </div>
</div>

<!-- Modal Filter Topic -->
<div class="modal fade" id="filterTopicModal" tabindex="-1" aria-labelledby="filterTopicModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="filterTopicForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="filterTopicModalLabel">Filter by Topic</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <select class="form-select" name="topic" id="filterTopicSelect">
          @foreach($topics as $topic)
            <option value="{{ $topic->id }}">{{ $topic->title }}</option>
          @endforeach
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Apply</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Filter Subtopic -->
<div class="modal fade" id="filterSubtopicModal" tabindex="-1" aria-labelledby="filterSubtopicModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="filterSubtopicForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="filterSubtopicModalLabel">Filter by Subtopic</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <select class="form-select" name="subtopic" id="filterSubtopicSelect">
          {{-- Akan diisi dinamis oleh JS --}}
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Apply</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Add Question -->
<div class="modal fade" id="addQuestionModal" tabindex="-1" aria-labelledby="addQuestionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <form id="addQuestionForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addQuestionModalLabel">Add Question</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body row">
        <div class="col-md-8 mb-3">
            <div id="addModulePreview" style="border:1px solid #eee; border-radius:8px; padding:10px; min-height:120px; background:#f9f9f9;">
                <span class="text-muted">Loading module...</span>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="mb-3">
                <label class="form-label fw-bold">Topik</label>
                <input type="text" class="form-control" id="addTopicTitleInput" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Subtopik</label>
                <input type="text" class="form-control" id="addSubtopicTitleInput" readonly>
                <input type="hidden" name="topic_detail_id" id="addSubtopicIdHidden">
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Question Number</label>
                <input type="text" class="form-control" id="addQuestionNumberInput" readonly>
                <input type="hidden" name="answer_number" id="addQuestionNumberHidden">
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Answer Key (SQL Query)</label>
                <textarea class="form-control" name="expected_query" id="addExpectedQueryInput" rows="6" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Expected Table</label>
                <input type="text" class="form-control" name="expected_table" id="addExpectedTableInput">
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Question</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Question -->
<div class="modal fade" id="questionModal" tabindex="-1" aria-labelledby="questionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <form id="questionForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="questionModalLabel">Add/Edit Question</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body row">
        <!-- Preview Modul Praktikum -->
        <div class="col-md-8 mb-3">
            <div id="modulePreview" style="border:1px solid #eee; border-radius:8px; padding:10px; min-height:120px; background:#f9f9f9;">
                <span class="text-muted">Loading module...</span>
            </div>
        </div>
        <!-- Form Input Question -->
        <div class="col-md-4 mb-3">
            <div class="mb-3">
                <label class="form-label fw-bold">Topik</label>
                <input type="text" class="form-control" id="modalTopicTitle" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Subtopic</label>
                <input type="text" class="form-control" id="modalSubtopicTitle" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Question</label>
                <input type="text" class="form-control" id="modalQuestionNumber" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Answer Key (SQL Query)</label>
                <textarea class="form-control" name="expected_query" id="questionQueryInput" rows="6" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Expected Table</label>
                <input type="text" class="form-control" name="expected_table" id="expectedTableInput">
            </div>
            <input type="hidden" name="topic_detail_id" id="topicDetailIdInput">
            <input type="hidden" name="answer_number" id="answerNumberInput">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Question</button>
      </div>
    </form>
  </div>
</div>

<script>
window.allQuestions = @json($questions);
window.filterState = window.filterState || {};
window.topics = window.topics || @json($topics);
window.subtopics = window.subtopics || @json($subtopics);

// --- Pindahkan renderTable ke global scope ---
function renderTable() {
    const topics = window.topics;
    const subtopics = window.subtopics;
    const filterState = window.filterState;

    let subtopic = subtopics.find(st => st.id == filterState.subtopic);
    let totalQuestion = subtopic ? subtopic.total_question : 0;
    let tbody = '';

    if (!subtopic || totalQuestion == 0) {
        tbody = `<tr><td colspan="4" class="text-center text-muted">No questions found for this subtopic.</td></tr>`;
    } else {
        for (let i = 1; i <= totalQuestion; i++) {
            let question = window.allQuestions.find(q =>
                q.topic_detail_id == subtopic.id && q.answer_number == i
            );
            tbody += `<tr>
                <td class="text-center">Question ${i}</td>
                <td>${question ? question.expected_query : '<span class="text-muted fst-italic">No SQL Query</span>'}</td>
                <td class="text-center">${question ? (question.expected_table ?? '<span class="text-muted fst-italic">-</span>') : '<span class="text-muted fst-italic">No expected table</span>'}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-warning" title="${question ? 'Edit' : 'Add'}"
                        onclick="editQuestion(${question ? question.id : 'null'}, ${subtopic.id}, ${i})">
                        <i class="fas fa-edit"></i>
                    </button>
                    ${question ? `<button class="btn btn-sm btn-danger ms-1 delete-question-btn" data-id="${question.id}" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>` : ''}
                </td>
            </tr>`;
        }
    }
    $('#questionsTbody').html(tbody);
}

// --- END renderTable global ---

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function initQuestionsPage() {
    const topics = window.topics;
    const subtopics = window.subtopics;

    // Gunakan filterState global jika sudah ada
    let filterState = window.filterState && window.filterState.topic
        ? window.filterState
        : {
            topic: topics.length > 0 ? topics[0].id : null,
            subtopic: null
        };

    if (filterState.topic && !filterState.subtopic) {
        const relatedSubtopics = subtopics.filter(st => st.topic_id == filterState.topic);
        filterState.subtopic = relatedSubtopics.length > 0 ? relatedSubtopics[0].id : null;
    }

    function renderSubtopicOptions(topicId) {
        const related = subtopics.filter(st => st.topic_id == topicId);
        let html = '';
        related.forEach(st => {
            html += `<option value="${st.id}">${st.title}</option>`;
        });
        $('#filterSubtopicSelect').html(html);
    }

    function updateFilterLabels() {
        let topic = topics.find(t => t.id == filterState.topic);
        let subtopic = subtopics.find(st => st.id == filterState.subtopic);
        const relatedSubtopics = subtopics.filter(st => st.topic_id == filterState.topic);

        $('#filterTopicLabel').text(topic ? topic.title : 'Filter by Topic');

        if (relatedSubtopics.length === 0) {
            $('#filterSubtopicBtn')
                .removeClass('btn-outline-primary')
                .addClass('btn-danger active')
                // .prop('disabled', true);
            $('#filterSubtopicLabel').text('No Subtopic');
        } else {
            $('#filterSubtopicBtn')
                .removeClass('btn-danger active')
                .addClass('btn-outline-primary')
                // .prop('disabled', false);
            $('#filterSubtopicLabel').text(subtopic ? subtopic.title : 'Filter by Subtopic');
        }
    }

    renderSubtopicOptions(filterState.topic);
    $('#filterSubtopicSelect').val(filterState.subtopic);

    renderTable();
    updateFilterLabels();
    updateAddQuestionBtnState();

    $('#filterTopicForm').off('submit').on('submit', function(e) {
        e.preventDefault();
        filterState.topic = $('#filterTopicSelect').val();
        const related = subtopics.filter(st => st.topic_id == filterState.topic);
        filterState.subtopic = related.length > 0 ? related[0].id : null;
        renderSubtopicOptions(filterState.topic);
        $('#filterSubtopicSelect').val(filterState.subtopic);
        renderTable();
        updateFilterLabels();
        updateAddQuestionBtnState();
        $('#filterTopicModal').modal('hide');
    });

    $('#filterSubtopicForm').off('submit').on('submit', function(e) {
        e.preventDefault();
        filterState.subtopic = $('#filterSubtopicSelect').val();
        renderTable();
        updateFilterLabels();
        updateAddQuestionBtnState();
        $('#filterSubtopicModal').modal('hide');
    });

    $('#resetFilterBtn').off('click').on('click', function() {
        filterState.topic = topics.length > 0 ? topics[0].id : null;
        const related = subtopics.filter(st => st.topic_id == filterState.topic);
        filterState.subtopic = related.length > 0 ? related[0].id : null;
        renderSubtopicOptions(filterState.topic);
        $('#filterSubtopicSelect').val(filterState.subtopic);
        renderTable();
        updateFilterLabels();
        updateAddQuestionBtnState();
    });

    $('#filterTopicSelect').off('change').on('change', function() {
        renderSubtopicOptions($(this).val());
    });

    window.filterState = filterState;
}

// Fungsi untuk reload data dan render ulang tabel
function reloadQuestionsTable() {
    $.ajax({
        url: '/mysql/teacher/questions/list',
        method: 'GET',
        cache: false,
        success: function(data) {
            window.allQuestions = Array.isArray(data) ? data : (data.data || []);
            renderTable();
        }
    });
}

// Fungsi untuk reload subtopics
function reloadSubtopics(callback) {
    $.ajax({
        url: '/mysql/teacher/subtopics/list', // Pastikan endpoint ini mengembalikan array subtopics terbaru
        method: 'GET',
        cache: false,
        success: function(data) {
            window.subtopics = Array.isArray(data) ? data : (data.data || []);
            if (typeof callback === 'function') callback();
        }
    });
}

// Fungsi untuk reload semua data (untuk Sidebar Menu Utama di Index)
function reloadAllQuestionsData(callback) {
    $.when(
        $.get('/mysql/teacher/topics/list'),
        $.get('/mysql/teacher/subtopics/list'),
        $.get('/mysql/teacher/questions/list')
    ).done(function(topicsRes, subtopicsRes, questionsRes) {
        window.topics = Array.isArray(topicsRes[0]) ? topicsRes[0] : (topicsRes[0].data || []);
        window.subtopics = Array.isArray(subtopicsRes[0]) ? subtopicsRes[0] : (subtopicsRes[0].data || []);
        window.allQuestions = Array.isArray(questionsRes[0]) ? questionsRes[0] : (questionsRes[0].data || []);
        if (typeof callback === 'function') callback();
    });
}

// Handler submit add question
$('#addQuestionForm').off('submit').on('submit', function(e) {
    e.preventDefault();
    const formData = $(this).serialize();
    $.ajax({
        url: '/mysql/teacher/questions/save',
        method: 'POST',
        data: formData,
        success: function(res) {
            $('#addQuestionModal').modal('hide');
            // Setelah add, reload subtopics lalu reload questions
            reloadSubtopics(function() {
                reloadQuestionsTable();
            });
        },
        error: function() {
            alert('Failed to add question.');
        }
    });
});

// Handler submit edit question
$('#questionForm').off('submit').on('submit', function(e) {
    e.preventDefault();
    const formData = $(this).serialize();
    $.ajax({
        url: '/mysql/teacher/questions/save',
        method: 'POST',
        data: formData,
        success: function(res) {
            $('#questionModal').modal('hide');
            reloadQuestionsTable();
        },
        error: function() {
            alert('Failed to save question.');
        }
    });
});

// Handler delete question (PASTIKAN hanya ada satu di seluruh file JS Anda)
$(document).off('click', '.delete-question-btn').on('click', '.delete-question-btn', function() {
    if (!confirm('Are you sure you want to delete this question?')) return;
    const id = $(this).data('id');
    $.ajax({
        url: '/mysql/teacher/questions/delete/' + id,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(res) {
            // Setelah delete, reload subtopics lalu reload questions
            reloadSubtopics(function() {
                reloadQuestionsTable();
            });
        },
        error: function() {
            alert('Failed to delete question.');
        }
    });
});

// Handler add question button click
$('#addQuestionBtn').off('click').on('click', function() {
    const topicId = window.filterState && window.filterState.topic ? window.filterState.topic : (window.topics[0] ? window.topics[0].id : null);
    const relatedSubtopics = window.subtopics.filter(st => String(st.topic_id) === String(topicId));
    if (relatedSubtopics.length === 0) {
        // Tidak ada subtopik, jangan tampilkan modal
        return;
    }

    $('#addQuestionForm')[0].reset();
    $('#addModulePreview').html('<span class="text-muted">Loading module...</span>');
    $('#addQuestionNumberInput').val('');
    $('#addQuestionNumberHidden').val('');
    $('#addTopicTitleInput').val('');
    $('#addSubtopicTitleInput').val('');
    $('#addSubtopicIdHidden').val('');

    const subtopic = relatedSubtopics[0];
    const topic = window.topics.find(t => String(t.id) === String(topicId));

    $('#addTopicTitleInput').val(topic ? topic.title : '-');
    $('#addSubtopicTitleInput').val(subtopic ? subtopic.title : '-');
    $('#addSubtopicIdHidden').val(subtopic ? subtopic.id : '');

    let nextNumber = (subtopic && subtopic.total_question ? subtopic.total_question : 0) + 1;
    $('#addQuestionNumberInput').val(nextNumber);
    $('#addQuestionNumberHidden').val(nextNumber);

    if (subtopic && subtopic.file_path && subtopic.file_name) {
        $('#addModulePreview').html(
            `<iframe src="/${subtopic.file_path}${subtopic.file_name}"></iframe>`
        );
    } else {
        $('#addModulePreview').html('<div class="text-danger text-center d-flex align-items-center justify-content-center" style="height: 100%;"><span class="fst-italic">No module (PDF) available.</span></div>');
    }

    $('#addQuestionModal').modal('show');
});

// Handler edit question button click
window.editQuestion = function(questionId, topicDetailId, answerNumber) {
    $('#questionForm')[0].reset();
    $('#topicDetailIdInput').val(topicDetailId);
    $('#answerNumberInput').val(answerNumber);

    $('#questionModalLabel').text(questionId ? 'Edit Question' : 'Add Question');

    const subtopic = window.subtopics.find(st => String(st.id) === String(topicDetailId));
    const topic = subtopic ? window.topics.find(t => String(t.id) === String(subtopic.topic_id)) : null;

    $('#modalTopicTitle').val(topic ? topic.title : '-');
    $('#modalSubtopicTitle').val(subtopic ? subtopic.title : '-');
    $('#modalQuestionNumber').val(answerNumber ? 'Question ' + answerNumber : '-');

    if (answerNumber && topicDetailId) {
        const question = window.allQuestions.find(q =>
            String(q.topic_detail_id) === String(topicDetailId) &&
            q.answer_number == answerNumber
        );
        $('#questionQueryInput').val(question ? question.expected_query : '');
        $('#expectedTableInput').val(question ? question.expected_table ?? '' : '');
    } else {
        $('#questionQueryInput').val('');
        $('#expectedTableInput').val('');
    }

    if (subtopic && subtopic.file_path && subtopic.file_name) {
        $('#modulePreview').html(
            `<iframe src="/${subtopic.file_path}${subtopic.file_name}"></iframe>`
        );
    } else {
        $('#modulePreview').html('<div class="text-danger text-center d-flex align-items-center justify-content-center" style="height: 100%;"><span class="fst-italic">No module (PDF) available.</span></div>');
    }

    $('#questionModal').modal('show');
};

function updateAddQuestionBtnState() {
    const topicId = window.filterState && window.filterState.topic ? window.filterState.topic : (window.topics[0] ? window.topics[0].id : null);
    const relatedSubtopics = window.subtopics.filter(st => String(st.topic_id) === String(topicId));
    if (relatedSubtopics.length === 0) {
        $('#addQuestionBtn').prop('disabled', true).attr('title', 'Please add a subtopic first');
    } else {
        $('#addQuestionBtn').prop('disabled', false).removeAttr('title');
    }
}

// Inisialisasi halaman
$(function() {
    initQuestionsPage();
});
</script>

<style>
    .filter-btn {
        border-radius: 18px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        position: relative;
    }
    .filter-label {
        display: inline-block;
        vertical-align: middle;
    }
    .filter-clear {
        pointer-events: auto;
        color: #000000;
        font-weight: bold;
        font-size: 1.1em;
        background: transparent;
        border: none;
        padding: 0 4px;
        line-height: 1;
        transition: color 0.2s, background 0.2s;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 22px;
    }
    .filter-btn.active {
        background: #2563eb !important;
        color: #fff !important;
        border-color: #2563eb !important;
    }
    .filter-btn.active .filter-clear {
        color: #fff;
    }
    .filter-clear:hover {
        color: #333333;
        background: #d6d6d686;
        border-radius: 50%;
        align-items: center;
    }

    /* Responsive modal dialog */
    #questionModal .modal-dialog,
    #addQuestionModal .modal-dialog {
        max-width: 98vw;
        width: 100%;
    }

    #questionModal .modal-body,
    #addQuestionModal .modal-body {
        height: 70vh;
        min-height: 350px;
        display: flex;
        flex-direction: row;
        gap: 0;
        flex-wrap: wrap;
        overflow: hidden;
    }

    #modulePreview,
    #addModulePreview {
        height: 100%;
        min-height: 100%;
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: stretch;
        padding: 0;
        background: #f9f9f9;
        overflow: auto;
    }

    /* Tambahkan ini untuk kolom form agar bisa scroll */
    #questionModal .col-md-4.mb-3,
    #addQuestionModal .col-md-4.mb-3 {
        height: 100%;
        max-height: 100%;
        overflow-y: auto;
        overflow-x: visible;
        padding-right: 8px;
        box-sizing: border-box;
    }

    /* Pastikan iframe tetap responsif */
    #modulePreview iframe,
    #addModulePreview iframe {
        width: 100%;
        height: 100%;
        min-height: 300px;
        max-height: 65vh;
        border: none;
        border-radius: 8px;
        flex: 1 1 auto;
        background: #fff;
    }

    @media (max-width: 900px) {
        #questionModal .modal-body,
        #addQuestionModal .modal-body {
            flex-direction: column;
            height: auto;
        }
        #modulePreview,
        #addModulePreview {
            min-height: 200px;
            max-height: 40vh;
        }
        #modulePreview iframe,
        #addModulePreview iframe {
            min-height: 200px;
            max-height: 40vh;
        }
        /* Kolom form juga scroll pada mode kolom */
        #questionModal .col-md-4.mb-3,
        #addQuestionModal .col-md-4.mb-3 {
            max-height: 200px;
            overflow-y: auto;
        }
    }

    /* Tambahkan di style block Anda */
    #filterSubtopicBtn.btn-danger {
        background-color: #fdeaea !important;
        color: #dc3545 !important;
        border: 1.5px solid #dc3545 !important;
        border-radius: 18px !important;
        font-weight: 500;
    }
    #filterSubtopicBtn.btn-danger.active,
    #filterSubtopicBtn.btn-danger:active,
    #filterSubtopicBtn.btn-danger:focus,
    #filterSubtopicBtn.btn-danger:hover {
        background-color: #dc3545 !important;
        color: #fff !important;
        border-color: #dc3545 !important;
    }
</style>