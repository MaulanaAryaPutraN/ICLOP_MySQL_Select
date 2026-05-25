{{-- filepath: /d:/Semester 8 (Skripsi)/Skripsi/Project/iClOP_V2_MySql_DML/resources/views/mysql_dml/teacher/student_submissions.blade.php --}}
<div>
    {{-- Judul dan tombol export --}}
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <h4 class="mb-3 fw-bold">Student Submissions</h4>
    </div>
    <div class="mb-3 d-flex justify-content-end align-items-center">
        <button id="exportAllExcelBtn" class="btn btn-success me-2">
            <i class="fas fa-file-excel"></i> Export Excel
        </button>
        <button id="exportAllPdfBtn" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Export PDF
        </button>
    </div>
    <div class="mb-3 d-flex gap-3 align-items-center">
        <div class="d-flex align-items-center">
            <button id="filterTopicBtn" class="btn btn-outline-primary filter-btn" data-bs-toggle="modal" data-bs-target="#filterTopicModal" type="button">
                <span class="filter-label">Filter by Topic</span>
            </button>
            <span class="filter-clear d-none ms-2" id="clearTopic" style="margin-left: -4px;">&times;</span>
        </div>
        <div class="d-flex align-items-center">
            <button id="filterUserBtn" class="btn btn-outline-primary filter-btn" data-bs-toggle="modal" data-bs-target="#filterUserModal" type="button">
                <span class="filter-label">Filter by Username</span>
            </button>
            <span class="filter-clear d-none ms-2" id="clearUser" style="margin-left: -4px;">&times;</span>
        </div>
        <div class="d-flex align-items-center">
            <button id="filterDateBtn" class="btn btn-outline-primary filter-btn" data-bs-toggle="modal" data-bs-target="#filterDateModal" type="button">
                <span class="filter-label">Filter by Date</span>
            </button>
            <span class="filter-clear d-none ms-2" id="clearDate" style="margin-left: -4px;">&times;</span>
        </div>
        <button id="resetFilterBtn" class="btn btn-outline-secondary" style="border-radius: 18px; font-weight: 500;">
            Reset Filter
        </button>
    </div>
    <div class="card shadow-sm p-4 mb-4" style="border-radius: 18px;">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0 align-middle">
                <thead class="table-primary">
                    <tr class="text-center align-middle">
                        <th style="width: 45px;">No</th>
                        <th>Username</th>
                        <th>Topic</th>
                        <th>Date</th>
                        <th>Wrong</th>
                        <th>Correct</th>
                        <th>Duration</th>
                        <th>Attempt</th> <!-- Tambahkan jika ingin -->
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($studentSubmissions as $index => $submission)
                        @php
                            $durasiDetik = $submission->Durasi ?? 0;
                            $jam = floor($durasiDetik / 3600);
                            $menit = floor(($durasiDetik % 3600) / 60);
                            $detik = $durasiDetik % 60;
                            $durasiFormat = sprintf('%02d:%02d:%02d', $jam, $menit, $detik);
                        @endphp
                        <tr class="text-center">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $submission->UserName }}</td>
                            <td>{{ $submission->SubmissionTopic }}</td>
                            <td>{{ date('Y-m-d H:i', strtotime($submission->Time)) }}</td>
                            <td>{{ $submission->Salah }}</td>
                            <td>{{ $submission->Benar }}</td>
                            <td>{{ $submission->Durasi !== null ? $durasiFormat : '-' }}</td>
                            <td>{{ $submission->enroll_id }}</td> <!-- Tampilkan enroll_id -->
                            <td>{{ $submission->Score }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No submissions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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
<div class="modal fade" id="filterUserModal" tabindex="-1" aria-labelledby="filterUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="filterUserForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="filterUserModalLabel">Filter by Username</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <select class="form-select" name="username" id="filterUserSelect">
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
<div class="modal fade" id="filterDateModal" tabindex="-1" aria-labelledby="filterDateModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="filterDateForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="filterDateModalLabel">Filter by Date</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="date" class="form-control" name="date" id="filterDateInput">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Apply</button>
      </div>
    </form>
  </div>
</div>

<script>
$(document).ready(function () {
    // Ambil semua data submissions dari backend
    var allStudentSubmissions = @json($studentSubmissions);
    var filteredSubmissions = allStudentSubmissions.slice();

    // State untuk menyimpan filter yang sedang aktif
    var filterState = {
        topic: '',
        username: '',
        date: ''
    };

    // Render tabel
    function renderTable(data) {
        let tbody = '';
        if (data.length === 0) {
            tbody = `<tr><td colspan="9" class="text-center text-muted">No submissions found.</td></tr>`;
        } else {
            // 1. Penomoran percobaan: urutkan ASC enroll_id untuk setiap user+topik
            let percobaanMap = {};
            let percobaanNoByEnroll = {};

            // Urutkan ASC untuk penomoran percobaan
            let forPenomoran = data.slice().sort((a, b) => {
                if (a.UserName !== b.UserName) return a.UserName.localeCompare(b.UserName);
                if (a.SubmissionTopic !== b.SubmissionTopic) return a.SubmissionTopic.localeCompare(b.SubmissionTopic);
                return a.enroll_id - b.enroll_id;
            });

            forPenomoran.forEach(function(sub) {
                let key = sub.UserName + '|' + sub.SubmissionTopic;
                if (!percobaanMap[key]) percobaanMap[key] = 1;
                else percobaanMap[key]++;
                percobaanNoByEnroll[sub.enroll_id] = percobaanMap[key];
            });

            // 2. Urutkan data untuk tampilan: terbaru di atas (Time DESC)
            let sorted = data.slice().sort((a, b) => {
                if (a.Time > b.Time) return -1;
                if (a.Time < b.Time) return 1;
                return b.enroll_id - a.enroll_id;
            });

            // 3. Render tabel
            sorted.forEach(function(sub, idx) {
                let durasiDetik = sub.Durasi ?? 0;
                let jam = Math.floor(durasiDetik / 3600);
                let menit = Math.floor((durasiDetik % 3600) / 60);
                let detik = durasiDetik % 60;
                let durasiFormat = sub.Durasi !== null ? 
                    (('0'+jam).slice(-2) + ':' + ('0'+menit).slice(-2) + ':' + ('0'+detik).slice(-2)) : '-';
                let nilai = sub.Score;
                let percobaanKe = percobaanNoByEnroll[sub.enroll_id] || '-';
                tbody += `<tr>
                    <td class="text-center">${idx + 1}</td>
                    <td>${sub.UserName}</td>
                    <td>${sub.SubmissionTopic}</td>
                    <td>${sub.Time ? sub.Time.substring(0,16).replace('T',' ') : '-'}</td>
                    <td class="text-center">${sub.Salah}</td>
                    <td class="text-center">${sub.Benar}</td>
                    <td class="text-center">${durasiFormat}</td>
                    <td class="text-center">${percobaanKe}</td>
                    <td class="text-center">${nilai}</td>
                </tr>`;
            });
        }
        $('.table tbody').html(tbody);
    }

    // Update label dan icon X pada tombol filter
    function updateFilterButtons() {
        // Topic
        if (filterState.topic) {
            $('#filterTopicBtn .filter-label').text(filterState.topic);
            $('#clearTopic').removeClass('d-none');
            $('#filterTopicBtn').addClass('active');
        } else {
            $('#filterTopicBtn .filter-label').text('Filter by Topic');
            $('#clearTopic').addClass('d-none');
            $('#filterTopicBtn').removeClass('active');
        }
        // Username
        if (filterState.username) {
            $('#filterUserBtn .filter-label').text(filterState.username);
            $('#clearUser').removeClass('d-none');
            $('#filterUserBtn').addClass('active');
        } else {
            $('#filterUserBtn .filter-label').text('Filter by Username');
            $('#clearUser').addClass('d-none');
            $('#filterUserBtn').removeClass('active');
        }
        // Date
        if (filterState.date) {
            $('#filterDateBtn .filter-label').text(filterState.date);
            $('#clearDate').removeClass('d-none');
            $('#filterDateBtn').addClass('active');
        } else {
            $('#filterDateBtn .filter-label').text('Filter by Date');
            $('#clearDate').addClass('d-none');
            $('#filterDateBtn').removeClass('active');
        }
    }

    // Filter logic
    function applyFilters() {
        filteredSubmissions = allStudentSubmissions.filter(function(sub) {
            let matchTopic = !filterState.topic || sub.SubmissionTopic === filterState.topic;
            let matchUser = !filterState.username || sub.UserName === filterState.username;
            let matchDate = true;
            if (filterState.date) {
                let subDate = sub.Time ? sub.Time.substring(0,10) : '';
                matchDate = subDate === filterState.date;
            }
            return matchTopic && matchUser && matchDate;
        });
        renderTable(filteredSubmissions);
        updateFilterButtons();
    }

    renderTable(filteredSubmissions);

    // Export Excel
    $('#exportAllExcelBtn').on('click', function() {
        let csv = 'Name,Topic,Date,Wrong,Correct,Duration,Score,Enroll ID\n';
        filteredSubmissions.forEach(function(sub) {
            let durasiDetik = sub.Durasi ?? 0;
            let jam = Math.floor(durasiDetik / 3600);
            let menit = Math.floor((durasiDetik % 3600) / 60);
            let detik = durasiDetik % 60;
            let durasiFormat = sub.Durasi !== null ? 
                (('0'+jam).slice(-2) + ':' + ('0'+menit).slice(-2) + ':' + ('0'+detik).slice(-2)) : '-';
            let totalPercobaan = sub.Benar + sub.Salah;
            let nilai = sub.Score;
            csv += `"${sub.UserName}","${sub.SubmissionTopic}","${sub.Time}","${sub.Salah}","${sub.Benar}","${durasiFormat}","${nilai}","${sub.enroll_id}"\n`;
        });
        var blob = new Blob([csv], { type: 'text/csv' });
        var url = window.URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = url;
        a.download = 'filtered_student_submissions.csv';
        a.click();
        window.URL.revokeObjectURL(url);
    });

    // Export PDF
    $('#exportAllPdfBtn').on('click', function() {
        var body = [
            [
                {text: 'No', bold: true, alignment: 'center'},
                {text: 'Username', bold: true},
                {text: 'Topic', bold: true},
                {text: 'Date', bold: true},
                {text: 'Wrong', bold: true, alignment: 'center'},
                {text: 'Correct', bold: true, alignment: 'center'},
                {text: 'Duration', bold: true, alignment: 'center'},
                {text: 'Attempt', bold: true, alignment: 'center'},
                {text: 'Score', bold: true, alignment: 'center'}
            ]
        ];

        // Penomoran percobaan (Attempt) sama seperti renderTable
        let percobaanMap = {};
        let percobaanNoByEnroll = {};
        let forPenomoran = filteredSubmissions.slice().sort((a, b) => {
            if (a.UserName !== b.UserName) return a.UserName.localeCompare(b.UserName);
            if (a.SubmissionTopic !== b.SubmissionTopic) return a.SubmissionTopic.localeCompare(b.SubmissionTopic);
            return a.enroll_id - b.enroll_id;
        });
        forPenomoran.forEach(function(sub) {
            let key = sub.UserName + '|' + sub.SubmissionTopic;
            if (!percobaanMap[key]) percobaanMap[key] = 1;
            else percobaanMap[key]++;
            percobaanNoByEnroll[sub.enroll_id] = percobaanMap[key];
        });

        // Urutkan data untuk tampilan: terbaru di atas (Time DESC)
        let sorted = filteredSubmissions.slice().sort((a, b) => {
            if (a.Time > b.Time) return -1;
            if (a.Time < b.Time) return 1;
            return b.enroll_id - a.enroll_id;
        });

        sorted.forEach(function(sub, idx) {
            let durasiDetik = sub.Durasi ?? 0;
            let jam = Math.floor(durasiDetik / 3600);
            let menit = Math.floor((durasiDetik % 3600) / 60);
            let detik = durasiDetik % 60;
            let durasiFormat = sub.Durasi !== null ? 
                (('0'+jam).slice(-2) + ':' + ('0'+menit).slice(-2) + ':' + ('0'+detik).slice(-2)) : '-';
            let percobaanKe = percobaanNoByEnroll[sub.enroll_id] || '-';
            body.push([
                {text: (idx + 1).toString(), alignment: 'center'},
                sub.UserName,
                sub.SubmissionTopic,
                sub.Time ? sub.Time.substring(0,16).replace('T',' ') : '-',
                {text: sub.Salah.toString(), alignment: 'center'},
                {text: sub.Benar.toString(), alignment: 'center'},
                {text: durasiFormat, alignment: 'center'},
                {text: percobaanKe.toString(), alignment: 'center'},
                {text: sub.Score.toString(), alignment: 'center', bold: true}
            ]);
        });

        var docDefinition = {
            content: [
                {text: 'Student Submissions', style: 'header'},
                {
                    table: {
                        headerRows: 1,
                        widths: [24, '*', '*', 70, 35, 40, 55, 40, 40],
                        body: body
                    }
                }
            ],
            styles: {
                header: {
                    fontSize: 16,
                    bold: true,
                    margin: [0, 0, 0, 10]
                }
            }
        };

        pdfMake.createPdf(docDefinition).download('filtered_student_submissions.pdf');
    });

    // Filter by Topic
    $('#filterTopicForm').on('submit', function(e) {
        e.preventDefault();
        filterState.topic = $('#filterTopicSelect').val();
        applyFilters();
        $('#filterTopicModal').modal('hide');
    });

    // Filter by Username
    $('#filterUserForm').on('submit', function(e) {
        e.preventDefault();
        filterState.username = $('#filterUserSelect').val();
        applyFilters();
        $('#filterUserModal').modal('hide');
    });

    // Filter by Date
    $('#filterDateForm').on('submit', function(e) {
        e.preventDefault();
        filterState.date = $('#filterDateInput').val();
        applyFilters();
        $('#filterDateModal').modal('hide');
    });

    // Reset all filter
    $('#resetFilterBtn').on('click', function() {
        $('#filterTopicSelect').val('');
        $('#filterUserSelect').val('');
        $('#filterDateInput').val('');
        filterState = { topic: '', username: '', date: '' };
        applyFilters();
    });

    // Klik icon X pada filter topic (tidak buka modal)
    $('#filterTopicBtn .filter-clear').on('click', function(e) {
        e.stopPropagation();
        filterState.topic = '';
        $('#filterTopicSelect').val('');
        applyFilters();
    });

    // Klik icon X pada filter username (tidak buka modal)
    $('#filterUserBtn .filter-clear').on('click', function(e) {
        e.stopPropagation(); // Mencegah modal muncul
        filterState.username = '';
        $('#filterUserSelect').val('');
        applyFilters();
    });

    // Klik icon X pada filter date (tidak buka modal)
    $('#filterDateBtn .filter-clear').on('click', function(e) {
        e.stopPropagation();
        filterState.date = '';
        $('#filterDateInput').val('');
        applyFilters();
    });

    // Klik icon X pada filter topic (tidak buka modal)
    $('#clearTopic').on('click', function(e) {
        filterState.topic = '';
        $('#filterTopicSelect').val('');
        applyFilters();
    });

    // Klik icon X pada filter username (tidak buka modal)
    $('#clearUser').on('click', function(e) {
        filterState.username = '';
        $('#filterUserSelect').val('');
        applyFilters();
    });

    // Klik icon X pada filter date (tidak buka modal)
    $('#clearDate').on('click', function(e) {
        filterState.date = '';
        $('#filterDateInput').val('');
        applyFilters();
    });
});
</script>

<style>
    .filter-btn {
        border-radius: 18px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px; /* Jarak antara label dan X */
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
    #exportAllExcelBtn {
        background-color: #e6f4ea !important;
        color: #198754 !important;
        border: 1.5px solid #198754 !important;
        border-radius: 10px !important;
        font-weight: 500;
    }
    #exportAllExcelBtn:hover, #exportAllExcelBtn:focus {
        background-color: #198754 !important;
        color: #fff !important;
    }
    #exportAllPdfBtn {
        background-color: #fdeaea !important;
        color: #dc3545 !important;
        border: 1.5px solid #dc3545 !important;
        border-radius: 10px !important;
        font-weight: 500;
    }
    #exportAllPdfBtn:hover, #exportAllPdfBtn:focus {
        background-color: #dc3545 !important;
        color: #fff !important;
    }
    #exportAllExcelBtn .fa-file-excel {
        color: #198754 !important;
    }
    #exportAllPdfBtn .fa-file-pdf {
        color: #dc3545 !important;
    }
    #exportAllExcelBtn:hover .fa-file-excel,
    #exportAllExcelBtn:focus .fa-file-excel,
    #exportAllPdfBtn:hover .fa-file-pdf,
    #exportAllPdfBtn:focus .fa-file-pdf {
        color: #fff !important;
    }
</style>