{{-- filepath: resources/views/select/teacher/student_submissions.blade.php --}}
<div>
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <h4 class="mb-0 fw-bold">Student Submissions</h4>
    </div>
    <div class="mb-3 d-flex justify-content-end align-items-center gap-2">
        <button id="exportAllExcelBtn" class="btn btn-export-excel">
            <i class="fas fa-file-excel"></i> Export Excel
        </button>
        <button id="exportAllPdfBtn" class="btn btn-export-pdf">
            <i class="fas fa-file-pdf"></i> Export PDF
        </button>
    </div>
    <div class="mb-3 d-flex gap-2 align-items-center flex-wrap">
        <div class="d-flex align-items-center">
            <button id="filterTopicBtn" class="btn btn-outline-primary filter-btn" data-bs-toggle="modal" data-bs-target="#filterTopicModal" type="button">
                <span class="filter-label">Filter by Topic</span>
            </button>
            <span class="filter-clear d-none ms-1" id="clearTopic">&times;</span>
        </div>
        <div class="d-flex align-items-center">
            <button id="filterUserBtn" class="btn btn-outline-primary filter-btn" data-bs-toggle="modal" data-bs-target="#filterUserModal" type="button">
                <span class="filter-label">Filter by Username</span>
            </button>
            <span class="filter-clear d-none ms-1" id="clearUser">&times;</span>
        </div>
        <div class="d-flex align-items-center">
            <button id="filterDateBtn" class="btn btn-outline-primary filter-btn" data-bs-toggle="modal" data-bs-target="#filterDateModal" type="button">
                <span class="filter-label">Filter by Date</span>
            </button>
            <span class="filter-clear d-none ms-1" id="clearDate">&times;</span>
        </div>
        <button id="resetFilterBtn" class="btn btn-outline-secondary filter-btn">Reset Filter</button>
    </div>

    <div class="card shadow-sm p-4 mb-4" style="border-radius: 18px;">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0 align-middle" id="submissionsTable">
                <thead class="table-primary">
                    <tr class="text-center align-middle">
                        <th style="width:45px;">No</th>
                        <th>Username</th>
                        <th>Topic</th>
                        <th>Date</th>
                        <th title="Total submit (benar + salah)">Total Submit</th>
                        <th title="Soal tugas yang berhasil dijawab benar">Soal Selesai</th>
                        <th>Duration</th>
                        <th title="Percobaan ke-berapa untuk topik ini">Attempt</th>
                        <th>Score Topik</th>
                        <th style="width:90px;">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- diisi via JS --}}
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Filter Topic -->
<div class="modal fade" id="filterTopicModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="filterTopicForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Filter by Topic</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <select class="form-select" id="filterTopicSelect">
          <option value="">-- Select Topic --</option>
          @foreach($topics as $topic)
            <option value="{{ $topic->title }}">{{ $topic->title }}</option>
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

<!-- Modal Filter Username -->
<div class="modal fade" id="filterUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="filterUserForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Filter by Username</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <select class="form-select" id="filterUserSelect">
          <option value="">-- Select Username --</option>
          @foreach(collect($studentSubmissions)->pluck('UserName')->unique() as $user)
            <option value="{{ $user }}">{{ $user }}</option>
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

<!-- Modal Filter Date -->
<div class="modal fade" id="filterDateModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="filterDateForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Filter by Date</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="date" class="form-control" id="filterDateInput">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Apply</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Detail Score Subtopik -->
<div class="modal fade" id="subtopicScoreModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
    <div class="modal-content" style="border-radius:16px;">
      <div class="modal-header" style="border-bottom:none; padding-bottom:0;">
        <div>
          <h5 class="modal-title fw-bold mb-0" id="subtopicModalTitle">Detail Score</h5>
          <div class="text-muted" style="font-size:13px;" id="subtopicModalMeta"></div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body pt-2">
        <!-- Score topik ringkasan -->
        <div class="d-flex align-items-center justify-content-between mb-3 p-3"
             style="background:#f0f7ff; border-radius:12px;">
          <span class="fw-semibold" style="font-size:14px;">Score Topik</span>
          <span id="subtopicModalTopicScore"
                class="badge fs-6 px-3 py-2" style="min-width:56px;font-size:15px!important;"></span>
        </div>
        <!-- Tabel subtopik -->
        <table class="table table-sm mb-0" style="border-radius:10px;overflow:hidden;">
          <thead style="background:#e9ecef;">
            <tr>
              <th style="font-size:13px;font-weight:600;padding:8px 12px;">Subtopik</th>
              <th style="font-size:13px;font-weight:600;padding:8px 12px;text-align:center;width:100px;">Score</th>
            </tr>
          </thead>
          <tbody id="subtopicModalBody">
          </tbody>
        </table>
        <div id="subtopicModalEmpty" class="text-center text-muted py-3 d-none" style="font-size:13px;">
          Belum ada data subtopik.
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function () {
    var allStudentSubmissions = @json($studentSubmissions);
    var filteredSubmissions   = allStudentSubmissions.slice();
    var filterState = { topic: '', username: '', date: '' };

    // ── helpers ────────────────────────────────────────────────
    function scoreColor(s) {
        return s >= 80 ? '#198754' : s >= 60 ? '#e6a817' : '#dc3545';
    }
    function scoreBadge(s) {
        return `<span class="badge" style="background:${scoreColor(s)};color:#fff;font-size:13px;min-width:44px;display:inline-block;text-align:center;">${s}</span>`;
    }
    function fmtDur(durasi) {
        if (durasi === null || durasi === undefined) return '-';
        let j = Math.floor(durasi/3600), m = Math.floor((durasi%3600)/60), s = durasi%60;
        return ('0'+j).slice(-2)+':'+('0'+m).slice(-2)+':'+('0'+s).slice(-2);
    }
    function buildAttemptMap(data) {
        let map = {}, no = {};
        data.slice().sort((a,b) => {
            if (a.UserName !== b.UserName) return a.UserName.localeCompare(b.UserName);
            if (a.SubmissionTopic !== b.SubmissionTopic) return a.SubmissionTopic.localeCompare(b.SubmissionTopic);
            return a.enroll_id - b.enroll_id;
        }).forEach(sub => {
            let k = sub.UserName+'|'+sub.SubmissionTopic;
            map[k] = (map[k] || 0) + 1;
            no[sub.enroll_id] = map[k];
        });
        return no;
    }

    // ── render tabel ───────────────────────────────────────────
    function renderTable(data) {
        let atNo = buildAttemptMap(data);
        let sorted = data.slice().sort((a,b) => {
            if (a.Time > b.Time) return -1;
            if (a.Time < b.Time) return  1;
            return b.enroll_id - a.enroll_id;
        });

        let tbody = '';
        if (!sorted.length) {
            tbody = `<tr><td colspan="10" class="text-center text-muted py-4">No submissions found.</td></tr>`;
        } else {
            sorted.forEach((sub, idx) => {
                let tidakMengerjakan = sub.TidakMengerjakan || (sub.TotalJawaban == 0);
                let totalSubmit  = (sub.TotalJawaban ?? 0);
                let soalSelesai  = (sub.Benar ?? 0);
                let totalSoal    = (sub.TotalSoal ?? 0);
                let attempt      = atNo[sub.enroll_id] || '-';
                let hasDetail    = !tidakMengerjakan && (sub.SubtopicScores && sub.SubtopicScores.length > 0);
                let rowStyle     = tidakMengerjakan ? 'background:#fff8f0;' : '';

                let scoreCell = tidakMengerjakan
                    ? `<span class="badge" style="background:#6c757d;color:#fff;font-size:12px;padding:5px 10px;border-radius:8px;">
                           <i class="fas fa-minus-circle me-1"></i>Tidak Mengerjakan
                       </span>`
                    : scoreBadge(sub.Score ?? 0);

                let soalCell = tidakMengerjakan
                    ? `<span class="text-muted" style="font-size:12px;">-</span>`
                    : `<span style="font-size:13px;">${soalSelesai} / ${totalSoal}</span>`;

                tbody += `<tr class="text-center align-middle" style="${rowStyle}">
                    <td>${idx+1}</td>
                    <td class="text-start">${sub.UserName}</td>
                    <td class="text-start">${sub.SubmissionTopic}</td>
                    <td>${sub.Time ? sub.Time.substring(0,16).replace('T',' ') : '-'}</td>
                    <td>${tidakMengerjakan ? '<span class="text-muted">-</span>' : totalSubmit}</td>
                    <td>${soalCell}</td>
                    <td>${fmtDur(sub.Durasi)}</td>
                    <td>${attempt}</td>
                    <td>${scoreCell}</td>
                    <td>
                        ${hasDetail
                            ? `<button class="btn btn-sm btn-outline-primary btn-detail-subtopic"
                                style="border-radius:8px;font-size:12px;"
                                data-idx="${idx}"
                                data-username="${sub.UserName}"
                                data-topic="${sub.SubmissionTopic}"
                                data-score="${sub.Score ?? 0}"
                                data-subtopics='${JSON.stringify(sub.SubtopicScores)}'>
                                <i class="fas fa-chart-bar me-1"></i>Subtopik
                               </button>`
                            : '<span class="text-muted" style="font-size:12px;">-</span>'}
                    </td>
                </tr>`;
            });
        }
        $('#submissionsTable tbody').html(tbody);
    }

    // ── modal detail subtopik ──────────────────────────────────
    $(document).on('click', '.btn-detail-subtopic', function () {
        let username   = $(this).data('username');
        let topic      = $(this).data('topic');
        let score      = $(this).data('score');
        let subtopics  = $(this).data('subtopics');

        $('#subtopicModalTitle').text(username);
        $('#subtopicModalMeta').text(topic);

        let sc = parseFloat(score);
        $('#subtopicModalTopicScore')
            .text(score)
            .css('background', scoreColor(sc));

        let rows = '';
        subtopics.forEach(s => {
            let sc2 = parseFloat(s.score);
            rows += `<tr>
                <td style="padding:8px 12px;font-size:13px;">${s.title}</td>
                <td style="padding:8px 12px;text-align:center;">
                    <span class="badge" style="background:${scoreColor(sc2)};color:#fff;min-width:44px;font-size:13px;">${s.score}</span>
                </td>
            </tr>`;
        });

        if (rows) {
            $('#subtopicModalBody').html(rows);
            $('#subtopicModalEmpty').addClass('d-none');
        } else {
            $('#subtopicModalBody').html('');
            $('#subtopicModalEmpty').removeClass('d-none');
        }

        new bootstrap.Modal(document.getElementById('subtopicScoreModal')).show();
    });

    // ── filter ─────────────────────────────────────────────────
    function updateFilterButtons() {
        [
            ['#filterTopicBtn', '#clearTopic', 'topic', 'Filter by Topic'],
            ['#filterUserBtn',  '#clearUser',  'username', 'Filter by Username'],
            ['#filterDateBtn',  '#clearDate',  'date', 'Filter by Date'],
        ].forEach(([btn, clr, key, def]) => {
            if (filterState[key]) {
                $(`${btn} .filter-label`).text(filterState[key]);
                $(clr).removeClass('d-none');
                $(btn).addClass('active');
            } else {
                $(`${btn} .filter-label`).text(def);
                $(clr).addClass('d-none');
                $(btn).removeClass('active');
            }
        });
    }
    function applyFilters() {
        filteredSubmissions = allStudentSubmissions.filter(sub => {
            let ok1 = !filterState.topic    || sub.SubmissionTopic === filterState.topic;
            let ok2 = !filterState.username || sub.UserName        === filterState.username;
            let ok3 = true;
            if (filterState.date) {
                ok3 = (sub.Time ? sub.Time.substring(0,10) : '') === filterState.date;
            }
            return ok1 && ok2 && ok3;
        });
        renderTable(filteredSubmissions);
        updateFilterButtons();
    }

    renderTable(filteredSubmissions);

    $('#filterTopicForm').on('submit', e => { e.preventDefault(); filterState.topic    = $('#filterTopicSelect').val(); applyFilters(); $('#filterTopicModal').modal('hide'); });
    $('#filterUserForm').on('submit',  e => { e.preventDefault(); filterState.username = $('#filterUserSelect').val();  applyFilters(); $('#filterUserModal').modal('hide'); });
    $('#filterDateForm').on('submit',  e => { e.preventDefault(); filterState.date     = $('#filterDateInput').val();   applyFilters(); $('#filterDateModal').modal('hide'); });
    $('#resetFilterBtn').on('click', () => {
        $('#filterTopicSelect, #filterUserSelect').val(''); $('#filterDateInput').val('');
        filterState = { topic:'', username:'', date:'' }; applyFilters();
    });
    $('#clearTopic').on('click', () => { filterState.topic='';    $('#filterTopicSelect').val(''); applyFilters(); });
    $('#clearUser').on('click',  () => { filterState.username=''; $('#filterUserSelect').val('');  applyFilters(); });
    $('#clearDate').on('click',  () => { filterState.date='';     $('#filterDateInput').val('');   applyFilters(); });

    // ── export excel ───────────────────────────────────────────
    $('#exportAllExcelBtn').on('click', function () {
        let atNo = buildAttemptMap(filteredSubmissions);
        let sorted = filteredSubmissions.slice().sort((a,b) => b.Time > a.Time ? -1 : b.Time < a.Time ? 1 : b.enroll_id - a.enroll_id);

        let csv = 'No,Username,Topic,Date,Total Submit,Soal Selesai,Total Soal,Duration,Attempt,Score Topik,Subtopik,Score Subtopik\n';
        sorted.forEach((sub, idx) => {
            let tidakMengerjakan = sub.TidakMengerjakan || (sub.TotalJawaban == 0);
            let subs = sub.SubtopicScores || [];
            let scoreVal = tidakMengerjakan ? 'Tidak Mengerjakan' : (sub.Score ?? 0);
            let base = `"${idx+1}","${sub.UserName}","${sub.SubmissionTopic}","${sub.Time?sub.Time.substring(0,16).replace('T',' '):'-'}","${tidakMengerjakan?'-':sub.TotalJawaban??0}","${tidakMengerjakan?'-':sub.Benar??0}","${tidakMengerjakan?'-':sub.TotalSoal??0}","${fmtDur(sub.Durasi)}","${atNo[sub.enroll_id]||'-'}","${scoreVal}"`;
            if (!subs.length || tidakMengerjakan) { csv += base + `,"",""\n`; }
            else subs.forEach((s,si) => {
                csv += si===0 ? base+`,"${s.title}","${s.score}"\n` : `"","","","","","","","","","","${s.title}","${s.score}"\n`;
            });
        });
        let a = document.createElement('a');
        a.href = URL.createObjectURL(new Blob([csv],{type:'text/csv'}));
        a.download = 'student_submissions.csv'; a.click();
    });

    // ── export pdf ─────────────────────────────────────────────
    $('#exportAllPdfBtn').on('click', function () {
        let atNo = buildAttemptMap(filteredSubmissions);
        let sorted = filteredSubmissions.slice().sort((a,b) => b.Time > a.Time ? -1 : b.Time < a.Time ? 1 : b.enroll_id - a.enroll_id);

        let hdr = row => row.map(t => ({text:t, bold:true, alignment:'center', fillColor:'#dbeafe'}));
        let body = [hdr(['No','Username','Topic','Date','Total Submit','Soal Selesai','Duration','Attempt','Score Topik','Subtopik','Score Subtopik'])];

        sorted.forEach((sub, idx) => {
            let tidakMengerjakan = sub.TidakMengerjakan || (sub.TotalJawaban == 0);
            let subs = sub.SubtopicScores || [];
            let scoreVal = tidakMengerjakan ? 'Tidak Mengerjakan' : (sub.Score??0).toString();
            let base = [
                {text:(idx+1).toString(), alignment:'center'},
                sub.UserName, sub.SubmissionTopic,
                sub.Time?sub.Time.substring(0,16).replace('T',' '):'-',
                {text: tidakMengerjakan ? '-' : (sub.TotalJawaban??0).toString(), alignment:'center'},
                {text: tidakMengerjakan ? '-' : `${sub.Benar??0} / ${sub.TotalSoal??0}`, alignment:'center'},
                {text:fmtDur(sub.Durasi), alignment:'center'},
                {text:(atNo[sub.enroll_id]||'-').toString(), alignment:'center'},
                {text: scoreVal, alignment:'center', bold:true, color: tidakMengerjakan ? '#6c757d' : '#000'},
            ];
            if (!subs.length || tidakMengerjakan) { body.push([...base,'','']); }
            else subs.forEach((s,si) => {
                body.push(si===0
                    ? [...base, s.title, {text:s.score.toString(), alignment:'center'}]
                    : ['','','','','','','','','', s.title, {text:s.score.toString(), alignment:'center'}]
                );
            });
        });

        pdfMake.createPdf({
            pageOrientation: 'landscape',
            content: [{text:'Student Submissions', style:'header'}, {table:{headerRows:1,widths:[18,'auto','auto',52,32,38,42,30,36,'auto',36],body}}],
            styles:{header:{fontSize:16,bold:true,margin:[0,0,0,10]}}
        }).download('student_submissions.pdf');
    });
});
</script>

<style>
    .filter-btn { border-radius: 18px; font-weight: 500; }
    .filter-btn.active { background:#2563eb!important; color:#fff!important; border-color:#2563eb!important; }
    .filter-clear {
        pointer-events:auto; cursor:pointer; font-weight:bold; font-size:1.1em;
        background:transparent; border:none; padding:0 4px; line-height:1;
        display:inline-flex; align-items:center; color:#000;
    }
    .filter-btn.active ~ .filter-clear { color:#2563eb; }
    .filter-clear:hover { color:#333; background:#d6d6d686; border-radius:50%; }
    .btn-export-excel {
        background:#e6f4ea!important; color:#198754!important;
        border:1.5px solid #198754!important; border-radius:10px!important; font-weight:500;
    }
    .btn-export-excel:hover { background:#198754!important; color:#fff!important; }
    .btn-export-pdf {
        background:#fdeaea!important; color:#dc3545!important;
        border:1.5px solid #dc3545!important; border-radius:10px!important; font-weight:500;
    }
    .btn-export-pdf:hover { background:#dc3545!important; color:#fff!important; }
    .btn-export-excel .fa-file-excel { color:#198754!important; }
    .btn-export-pdf .fa-file-pdf { color:#dc3545!important; }
    .btn-export-excel:hover .fa-file-excel,
    .btn-export-pdf:hover .fa-file-pdf { color:#fff!important; }
    #subtopicScoreModal .modal-content { box-shadow: 0 8px 32px rgba(0,0,0,.13); }
</style>