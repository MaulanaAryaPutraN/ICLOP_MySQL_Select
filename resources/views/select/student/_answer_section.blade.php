@php
    $enrollId       = $enrollId ?? null;
    $currentPhase   = $currentPhase ?? 'percobaan';
    $page           = $page ?? 1;
    $totalPercobaan = $totalPercobaan ?? 0;
    $totalTugas     = $totalTugas ?? 0;
    $isPercobaan    = ($currentPhase === 'percobaan');

    // Apakah soal ini sudah terjawab benar?
    $isCorrect = ($lastStatus === 'true');

    // Disabled jika sudah benar (berlaku untuk percobaan maupun tugas) atau sesi di-reset
    $disableSubmit = $isCorrect || $isReset;

    // Textarea selalu tampilkan query terakhir; auto-run pakai nilai yang sama
    $textareaValue   = $lastAnswer;
    $queryForAutoRun = ($isCorrect && $lastAnswer) ? $lastAnswer : '';

    $allTugasDone = $tugasDone;

    $nextDetail = \App\Models\Select\SelectTopicDetails::where('topic_id', $mysqlid)
        ->where('id', '>', $detail->id)
        ->orderBy('id')->first();

    $allSubtopicsDone = \App\Models\Select\SelectTopicDetails::where('topic_id', $mysqlid)->get()
        ->every(function($sub) use ($enrollId) {
            if ($sub->total_tugas == 0) return true;
            $correct = DB::table('select_student_submissions')
                ->where('user_id', Auth::id())->where('topic_detail_id', $sub->id)
                ->where('submission_type', 'tugas')->where('status', 'true')
                ->where('enroll_id', $enrollId)->distinct('answer_number')->count('answer_number');
            return $correct >= $sub->total_tugas;
        });

    $feedback = null;
    if (isset($lastSubmission) && $lastSubmission && $lastSubmission->feedback_id) {
        $feedback = \App\Models\Select\SelectFeedbacks::find($lastSubmission->feedback_id);
    }

    // Auto-transition dihapus — navigasi via tombol Next
    $autoTransitionToTugas = false;
@endphp

<style>
.phase-indicator { display:flex; gap:8px; margin-bottom:16px; }
.phase-badge { padding:4px 14px; border-radius:20px; font-size:13px; font-weight:600; }
.phase-active-percobaan { background:#f0ad4e; color:#fff; }
.phase-active-tugas     { background:#0066ff; color:#fff; }
.phase-done             { background:#28a745; color:#fff; }
.phase-locked           { background:#e0e0e0; color:#999; }
</style>

@if($autoTransitionToTugas)
{{-- Tampilkan feedback & hasil query dulu, baru transisi ke tugas setelah 4 detik --}}
<div style="padding-top:15px; padding-bottom:15px;">
        <div style="border:1px solid #ccc; padding:20px 10px 10px 30px; border-radius:10px; margin-bottom:20px;">
            <div class="phase-indicator">
                <span class="phase-badge phase-done">✓ Percobaan Selesai</span>
                <span style="align-self:center; color:#ccc;">→</span>
                <span class="phase-badge phase-active-tugas">Tugas ({{ $totalTugas }} soal)</span>
            </div>

            <div class="mt-3" style="color:#25923e; font-weight:600;">
                ✓ Your Query Is Correct!
                @if(isset($feedback) && $feedback && $feedback->feedback)
                    <div style="font-weight:400; font-size:13px;">{!! nl2br(e($feedback->feedback)) !!}</div>
                @endif
                <div style="font-size:12px; color:#888; font-style:italic;">Ini soal percobaan — tidak dihitung nilai.</div>
            </div>

            {{-- Hasil query --}}
            <div id="query-result" class="mt-3">
                <div class="d-flex align-items-center gap-2 mb-2" style="color:#555; font-size:13px;">
                    <span class="spinner-border spinner-border-sm text-secondary" role="status"></span>
                    Menjalankan query...
                </div>
            </div>
            <input type="hidden" id="query-for-autorun" value="{{ $queryForAutoRun }}">
            <input type="hidden" name="mysqlid" value="{{ $mysqlid }}">

            <div class="alert alert-success py-2 px-3 mt-3" style="font-size:14px;" id="transition-notice">
                ✓ Semua percobaan selesai! Melanjutkan ke soal tugas dalam <strong id="countdown-num">4</strong> detik...
                <button class="btn btn-primary btn-sm ms-3" id="lanjut-btn">Lanjut Sekarang</button>
            </div>
        </div>
    </div>
<script>
(function() {
    // Auto-run query hasil percobaan
    const queryResult = document.getElementById('query-result');
    const autoRunEl   = document.getElementById('query-for-autorun');
    const query       = autoRunEl ? autoRunEl.value.trim() : '';
    const mysqlid     = document.querySelector('input[name="mysqlid"]').value;

    if (query && mysqlid) {
        fetch('{{ route('v2.runUserSelectQuery') }}', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({ mysqlid, userSelectQuery: query, _token: document.querySelector('meta[name="csrf-token"]').content })
        })
        .then(r => r.json())
        .then(d => { queryResult.innerHTML = d.html; })
        .catch(() => { queryResult.innerHTML = '<div class="alert alert-danger">Gagal menjalankan query.</div>'; });
    } else {
        queryResult.innerHTML = '';
    }

    // Countdown lalu transisi ke tugas
    function goToTugas() {
        const start = '{{ $detail->id }}';
        fetch(`{{ route('v2.showTopicDetail') }}?mysqlid=${mysqlid}&start=${start}&phase=tugas&page=1`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.text())
        .then(html => {
            document.getElementById('answer-section').innerHTML = html;
            document.dispatchEvent(new Event('answer-section-updated'));
            if (typeof updateSidebarProgress === 'function') updateSidebarProgress();
            if (typeof updateSidebar === 'function') updateSidebar();
        });
    }

    let sisa = 4;
    const interval = setInterval(function() {
        sisa--;
        const el = document.getElementById('countdown-num');
        if (el) el.textContent = sisa;
        if (sisa <= 0) { clearInterval(interval); goToTugas(); }
    }, 1000);

    document.getElementById('lanjut-btn').addEventListener('click', function() {
        clearInterval(interval);
        goToTugas();
    });
})();
</script>
@else

<div style="padding-top:15px; padding-bottom:15px;">
        <div style="border:1px solid #ccc; padding:20px 10px 10px 30px; border-radius:10px; margin-bottom:20px;">

            {{-- Indikator phase --}}
            <div class="phase-indicator">
                <span class="phase-badge {{ $percobaanDone ? 'phase-done' : ($currentPhase === 'percobaan' ? 'phase-active-percobaan' : 'phase-locked') }}">
                    {{ $percobaanDone ? '✓' : '' }} Percobaan ({{ $totalPercobaan }} soal)
                </span>
                <span style="align-self:center; color:#ccc;">→</span>
                <span class="phase-badge {{ $tugasDone ? 'phase-done' : ($currentPhase === 'tugas' ? 'phase-active-tugas' : 'phase-locked') }}">
                    {{ $tugasDone ? '✓' : '' }} Tugas ({{ $totalTugas }} soal)
                </span>
            </div>

            {{-- Info phase --}}
            @if($isPercobaan)
                <div class="alert alert-warning py-1 px-3 mb-3" style="font-size:13px;">
                    &#128396; <strong>Percobaan {{ $page }} dari {{ $totalPercobaan }}</strong> — tidak dihitung nilai. Selesaikan semua percobaan untuk membuka soal tugas.
                </div>
            @else
                <div class="alert alert-primary py-1 px-3 mb-3" style="font-size:13px;">
                    &#9997; <strong>Tugas {{ $page }} dari {{ $totalTugas }}</strong> — dihitung nilai.
                </div>
            @endif

            {{-- Form submit --}}
            <form action="{{ route('v2.submitUserInput') }}" method="POST" id="submit-answer-form"
                  style="margin-bottom:1rem;">
                @csrf
                <input type="hidden" name="mysqlid"         value="{{ $mysqlid }}">
                <input type="hidden" name="start"           value="{{ $detail->id }}">
                <input type="hidden" name="topic_detail_id" value="{{ $detail->id }}">
                <input type="hidden" name="submission_type" value="{{ $currentPhase }}">
                <input type="hidden" name="answer_number"   value="{{ $page }}">
                <input type="hidden" name="pdf_filename"    value="{{ $detail->file_name ?? '' }}">

                <div class="form-group mb-2">
                    <label class="mb-2"><h4>{{ $isPercobaan ? 'Percobaan' : 'Tugas' }} {{ $page }}</h4></label>
                    <textarea name="userInput" id="query-textarea" class="form-control" rows="4"
                        @if($disableSubmit) disabled style="background-color:#f1f1f1; color:#525252;" @endif
                        placeholder="Input your SQL query here" required>{{ $textareaValue }}</textarea>
                    {{-- Query tersimpan untuk auto-run hasil (dipakai saat textarea dikosongkan pada percobaan benar) --}}
                    <input type="hidden" id="query-for-autorun" value="{{ $queryForAutoRun }}">
                </div>

                <div class="mt-2">
                    <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center"
                        id="submit-btn"
                        style="width:max-content; min-width:90px; min-height:40px; padding:0 22px;"
                        @if($disableSubmit) disabled @endif>
                        <span id="submit-btn-text" style="font-weight:600;">Submit</span>
                        <span id="submit-spinner" class="spinner-border spinner-border-sm" style="display:none; margin-left:8px;" role="status"></span>
                    </button>
                </div>
            </form>

            {{-- Feedback --}}
            @if($isCorrect)
                <div class="mt-3" style="color:#25923e; font-weight:600;">
                    ✓ Your Query Is Correct!
                    @if($feedback && $feedback->feedback)
                        <div style="font-weight:400; font-size:13px;">{!! nl2br(e($feedback->feedback)) !!}</div>
                    @endif
                    @if($isPercobaan)
                        <div style="font-size:12px; color:#888; font-style:italic;">Ini soal percobaan — tidak dihitung nilai.</div>
                    @endif
                </div>

                {{-- Hasil query — langsung dari server jika sudah ada, tidak perlu fetch ulang --}}
                <div id="query-result" class="mt-3">
                    @if(!empty($queryResultHtml))
                        {!! $queryResultHtml !!}
                    @else
                        <div class="d-flex align-items-center gap-2 mb-2" style="color:#555; font-size:13px;">
                            <span class="spinner-border spinner-border-sm text-secondary" role="status"></span>
                            Menjalankan query...
                        </div>
                    @endif
                </div>

            @elseif($lastStatus === 'false')
                <div class="mt-3" style="color:#cc0000; font-weight:600;">
                    ✗ Your Query Is Wrong!
                    @if($feedback && $feedback->validation_error)
                        <div style="font-weight:400; font-size:13px;">{!! nl2br(e($feedback->validation_error)) !!}</div>
                    @endif
                </div>
            @endif
        </div>

        {{-- Navigasi soal --}}
        <div class="d-flex justify-content-between align-items-center mt-4">
            @php
                $prevDetail = \App\Models\Select\SelectTopicDetails::where('topic_id', $mysqlid)
                    ->where('id', '<', $detail->id)
                    ->orderByDesc('id')->first();

                if ($isPercobaan) {
                    if ($page > 1) {
                        // Soal percobaan sebelumnya dalam subtopik ini
                        $prevDisabled = false;
                        $prevPhase    = 'percobaan';
                        $prevPage     = $page - 1;
                        $prevStart    = $detail->id;
                    } elseif ($prevDetail) {
                        // Kembali ke subtopik sebelumnya — soal tugas terakhir atau percobaan terakhir
                        $prevDisabled = false;
                        $prevPhase    = $prevDetail->total_tugas > 0 ? 'tugas' : 'percobaan';
                        $prevPage     = $prevDetail->total_tugas > 0 ? $prevDetail->total_tugas : $prevDetail->total_percobaan;
                        $prevStart    = $prevDetail->id;
                    } else {
                        // Sudah di subtopik pertama percobaan pertama
                        $prevDisabled = true;
                        $prevPhase    = 'percobaan';
                        $prevPage     = 1;
                        $prevStart    = $detail->id;
                    }
                } else {
                    if ($page > 1) {
                        // Soal tugas sebelumnya dalam subtopik ini
                        $prevDisabled = false;
                        $prevPhase    = 'tugas';
                        $prevPage     = $page - 1;
                        $prevStart    = $detail->id;
                    } elseif ($totalPercobaan > 0) {
                        // Kembali ke percobaan terakhir subtopik ini
                        $prevDisabled = false;
                        $prevPhase    = 'percobaan';
                        $prevPage     = $totalPercobaan;
                        $prevStart    = $detail->id;
                    } elseif ($prevDetail) {
                        // Kembali ke subtopik sebelumnya
                        $prevDisabled = false;
                        $prevPhase    = $prevDetail->total_tugas > 0 ? 'tugas' : 'percobaan';
                        $prevPage     = $prevDetail->total_tugas > 0 ? $prevDetail->total_tugas : $prevDetail->total_percobaan;
                        $prevStart    = $prevDetail->id;
                    } else {
                        $prevDisabled = true;
                        $prevPhase    = 'tugas';
                        $prevPage     = 1;
                        $prevStart    = $detail->id;
                    }
                }

                if ($isPercobaan) {
                    if ($page < $totalPercobaan) {
                        $nextLabel    = 'Next';
                        $nextPhase    = 'percobaan';
                        $nextPage     = $page + 1;
                        $nextStart    = $detail->id;
                        $nextDisabled = !$isCorrect;
                    } elseif ($totalTugas > 0) {
                        $nextLabel    = 'Lanjut ke Tugas →';
                        $nextPhase    = 'tugas';
                        $nextPage     = 1;
                        $nextStart    = $detail->id;
                        $nextDisabled = !$percobaanDone;
                    } else {
                        $nextLabel    = '';
                        $nextPhase    = '';
                        $nextPage     = 0;
                        $nextStart    = $detail->id;
                        $nextDisabled = true;
                    }
                } else {
                    if ($page < $totalTugas) {
                        $nextLabel    = 'Next';
                        $nextPhase    = 'tugas';
                        $nextPage     = $page + 1;
                        $nextStart    = $detail->id;
                        $nextDisabled = !$isCorrect;
                    } elseif ($nextDetail) {
                        // Tugas terakhir subtopik ini → subtopik berikutnya
                        // Selalu mulai dari awal: percobaan 1, atau tugas 1 jika tidak ada percobaan
                        $nextLabel    = 'Sub-Topik Berikutnya →';
                        $nextPhase    = $nextDetail->total_percobaan > 0 ? 'percobaan' : 'tugas';
                        $nextPage     = 1;
                        $nextStart    = $nextDetail->id;
                        $nextDisabled = !$allTugasDone;
                    } else {
                        $nextLabel    = '';
                        $nextPhase    = '';
                        $nextPage     = 0;
                        $nextStart    = $detail->id;
                        $nextDisabled = true;
                    }
                }
            @endphp

            <button class="btn btn-outline-secondary answer-pagination"
                data-phase="{{ $prevPhase }}" data-page="{{ $prevPage }}" data-start="{{ $prevStart }}"
                {{ $prevDisabled ? 'disabled' : '' }}>
                Previous
            </button>

            <span class="fw-semibold text-center" style="font-size:14px;">
                @if($isPercobaan)
                    Percobaan {{ $page }} / {{ $totalPercobaan }}
                @else
                    Tugas {{ $page }} / {{ $totalTugas }}
                @endif
            </span>

            @if($nextLabel)
                <button class="btn btn-outline-primary answer-pagination fw-semibold"
                    data-phase="{{ $nextPhase }}" data-page="{{ $nextPage }}" data-start="{{ $nextStart ?? $detail->id }}"
                    {{ $nextDisabled ? 'disabled' : '' }}>
                    {{ $nextLabel }}
                </button>
            @else
                @if(!$isPercobaan)
                    @if($nextDetail)
                        {{-- Selalu mulai dari percobaan 1 (atau tugas 1 jika tidak ada percobaan) --}}
                        <button class="btn btn-primary fw-semibold answer-pagination {{ !$allTugasDone ? 'disabled' : '' }}"
                            data-phase="{{ $nextDetail->total_percobaan > 0 ? 'percobaan' : 'tugas' }}"
                            data-page="1"
                            data-start="{{ $nextDetail->id }}"
                            {{ !$allTugasDone ? 'disabled' : '' }}>
                            Sub-Topik Berikutnya &rarr;
                        </button>
                    @else
                        <button id="submit-all-btn" class="btn btn-success fw-semibold"
                            data-mysqlid="{{ $mysqlid }}"
                            {{ !$allSubtopicsDone ? 'disabled' : '' }}>
                            Submit All Answer ✓
                        </button>
                    @endif
                @else
                    <span></span>
                @endif
            @endif
        </div>
    </div>

<script>
// Auto-run query — hanya jika server belum menyediakan hasil (queryResultHtml kosong)
(function() {
    const queryResult = document.getElementById('query-result');
    if (!queryResult) return;

    // Jika sudah ada konten dari server (bukan spinner), tidak perlu fetch
    const hasServerResult = {{ !empty($queryResultHtml) ? 'true' : 'false' }};
    if (hasServerResult) return;

    const textarea = document.getElementById('query-textarea');
    const autoRunEl = document.getElementById('query-for-autorun');
    if (!textarea) return;

    const query   = textarea.value.trim() || (autoRunEl ? autoRunEl.value.trim() : '');
    const mysqlid = document.querySelector('input[name="mysqlid"]') ? document.querySelector('input[name="mysqlid"]').value : '';
    if (!query || !mysqlid) {
        queryResult.innerHTML = '';
        return;
    }
    fetch('{{ route('v2.runUserSelectQuery') }}', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({ mysqlid: mysqlid, userSelectQuery: query, _token: document.querySelector('meta[name="csrf-token"]').content })
    })
    .then(r => r.json())
    .then(d => { queryResult.innerHTML = d.html; })
    .catch(() => { queryResult.innerHTML = '<div class="alert alert-danger">Gagal menjalankan query.</div>'; });
})();

(function() {
    // Submit All Answer
    const submitAllBtn = document.getElementById('submit-all-btn');
    if (submitAllBtn) {
        submitAllBtn.addEventListener('click', function() {
            if (submitAllBtn.disabled) return;
            submitAllBtn.disabled = true;
            const mysqlid = submitAllBtn.getAttribute('data-mysqlid');
            fetch('{{ route('v2.student.finish.topic') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ mysqlid })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    fetch('{{ route('v2.student.reset.testing.db') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ mysqlid })
                    })
                    .then(r => r.json())
                    .then(d => {
                        if (d.success) window.location.href = '{{ url("/select/start") }}';
                        else { alert('Gagal reset: ' + d.message); submitAllBtn.disabled = false; }
                    });
                } else {
                    alert('Gagal menyimpan.'); submitAllBtn.disabled = false;
                }
            });
        });
    }
})();
</script>

@endif