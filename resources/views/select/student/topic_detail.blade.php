<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>iCLOP - Select</title>
    <link rel="icon" href="{{ asset('./images/logo.png') }}" type="image/png">
    <style>
        .text { font-family:'Poppins',sans-serif; color:#3F3F46; text-decoration:none; }
        .text:hover { color:black; text-decoration:underline; }
        .text-list { font-family:'Poppins',sans-serif; color:#3F3F46; }
        .footer { background-color:#EAEAEA; color:#636363; text-align:center; font-size:12px;
                  padding:5px 0; position:fixed; bottom:0; width:100%; }
        .sidebar { width:250px; background-color:#ffffff; height:100%; position:fixed;
                   top:0; right:0; overflow-x:hidden; padding-top:20px; }
        .list { list-style:none; padding:0; margin:0; }
        .list-item { display:flex; align-items:center; padding:10px; cursor:pointer;
                     margin-bottom:10px; border:none; }
        .list-item:hover { background-color:#F5F5F8; }
        .list-item-title { font-size:16px; margin-left:10px; font-weight:600;
                           font-family:'Poppins',sans-serif; color:#3F3F46; }
        .progress-container { width:100%; background-color:#f1f1f1; border-radius:10px; margin-bottom:10px; }
        #progressbar { height:20px; background-color:#4caf50; border-radius:10px; transition:width 0.5s; }
        .progress-text { font-size:18px; text-align:end; }
        @media only screen and (max-width: 600px) {
            #sidebar { display:none; }
        }
    </style>
</head>
<body>

{{-- Navbar --}}
<nav class="navbar navbar-light bg-light" style="padding:15px 20px; border-bottom:1px solid #E4E4E7; font-family:'Poppins',sans-serif;">
    <a class="navbar-brand" href="{{ route('select_welcome') }}">
        <img src="{{ asset('images/left-arrow.png') }}" style="height:24px; margin-right:10px;">
        {{ $topicsNavbar->title }}
    </a>
</nav>

{{-- Sidebar --}}
<div class="sidebar" style="border-left:1px solid #E4E4E7; padding:20px; width:100%; max-width:400px;">
    <div class="d-flex align-items-center justify-content-between mb-3" style="gap:10px;">
        <p class="text-list my-2" style="font-size:20px; font-weight:600;">
            <img src="{{ asset('images/right.png') }}" style="height:24px; margin-right:10px; border:1px solid; border-radius:50%"> Task List
        </p>
        @if(!isset($isFinished) || !$isFinished)
            <div id="countdown-timer" style="font-size:16px; color:white; font-weight:bold;
                 background:#0066ff; border-radius:10px; padding:5px; text-align:center; border:1px solid #0066ff;"></div>
        @endif
    </div>

    <div class="progress-text" id="progress-text">{{ $progressPercent }}%</div>
    <div class="progress-container">
        <div id="progressbar" style="width:{{ $progressPercent }}%;"></div>
    </div>

    <div id="sidebar-content">
        @include('select.student.sidebar', [
            'rows'        => $rows,
            'detailCount' => $detailCount,
            'mysqlid'     => $mysqlid,
            'detailId'    => $detail->id ?? null,
            'enrollId'    => $enrollId,
        ])
    </div>
</div>

{{-- Konten utama --}}
<div style="padding:20px; max-width:68%; margin-left:5px;">

    {{-- Modul / PDF --}}
    <div style="border:1px solid #ccc; padding:20px 10px 10px 30px; border-radius:10px; margin-bottom:10px;">
        @if($pdf_reader == 0)
            {!! $html_start !!}
        @else
            <iframe src="{{ asset('select/modul/' . $html_start) }}" style="width:100%; height:510px;"></iframe>
        @endif
    </div>

    {{-- Answer Section --}}
    <div id="answer-section-wrapper" style="padding-top:20px; padding-bottom:2rem; margin-bottom:5rem;">
        <div id="answer-section">
            @include('select.student._answer_section')
        </div>
    </div>
</div>

@include('select.student.modal.detail_submission')

<footer class="footer">© 2025 iCLOP. All rights reserved.</footer>

<script>
    // -----------------------------------------------
    // Bind form submit answer section (AJAX)
    // -----------------------------------------------
    function bindAnswerSectionForm() {
        const form = document.querySelector('#answer-section form[action*="submitUserInput"]');
        if (!form) return;
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = form.querySelector('#submit-btn');
            const spinner   = form.querySelector('#submit-spinner');
            const btnText   = form.querySelector('#submit-btn-text');
            const textarea  = form.querySelector('textarea[name="userInput"]');

            if (submitBtn && spinner && btnText) {
                submitBtn.disabled = true;
                btnText.style.display = 'none';
                spinner.style.display = 'inline-block';
            }

            const formData = new FormData(form);
            if (textarea) textarea.disabled = true;

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('answer-section').innerHTML = html;
                bindAnswerSectionForm();
                bindRunQueryForm();
                updateSidebarProgress();
                updateSidebar();
            })
            .catch(err => {
                alert('Error: ' + err);
                if (submitBtn && spinner && btnText) {
                    submitBtn.disabled = false;
                    btnText.style.display = '';
                    spinner.style.display = 'none';
                }
                if (textarea) textarea.disabled = false;
            });
        });
    }


    // -----------------------------------------------
    // Run Query form (AJAX)
    // -----------------------------------------------
    function bindRunQueryForm() {
        const form = document.getElementById('run-query-form');
        if (!form) return;
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = form.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.textContent = 'Loading...';
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams(new FormData(form))
            })
            .then(res => res.json())
            .then(data => { document.getElementById('query-result').innerHTML = data.html; })
            .catch(()  => { document.getElementById('query-result').innerHTML = '<div class="alert alert-danger">Query failed.</div>'; })
            .finally(() => { btn.disabled = false; btn.textContent = 'Run Query'; });
        });
    }

    // -----------------------------------------------
    // Update progress bar via AJAX
    // -----------------------------------------------
    function updateSidebarProgress() {
        const mysqlid = document.querySelector('input[name="mysqlid"]').value;
        fetch('{{ route('v2.student.progress.ajax') }}?mysqlid=' + mysqlid, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            document.querySelector('.progress-text').textContent = data.progress + '%';
            document.getElementById('progressbar').style.width = data.progress + '%';
        });
    }

    // -----------------------------------------------
    // Update sidebar checklist via AJAX
    // -----------------------------------------------
    function updateSidebar() {
        const mysqlid = document.querySelector('input[name="mysqlid"]').value;
        const start   = document.querySelector('input[name="start"]').value;
        fetch('{{ route('v2.student.sidebar.ajax') }}?mysqlid=' + mysqlid + '&start=' + start, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => { document.getElementById('sidebar-content').innerHTML = html; });
    }

    // -----------------------------------------------
    // Countdown timer
    // -----------------------------------------------
    let countdownSeconds  = {{ $countdownSeconds ?? 3600 }};
    let timerInterval     = null;
    let heartbeatInterval = null;
    const MYSQLID    = '{{ $mysqlid }}';
    const CSRF       = document.querySelector('meta[name="csrf-token"]')?.content;
    const PAUSE_URL  = '{{ route('v2.student.pause.timer') }}';
    const RESUME_URL = '{{ route('v2.student.resume.timer') }}';

    function formatTime(s) {
        const h  = Math.floor(s / 3600);
        const m  = Math.floor((s % 3600) / 60);
        const sc = s % 60;
        return (h > 0 ? String(h).padStart(2, '0') + ':' : '') +
               String(m).padStart(2, '0') + ':' +
               String(sc).padStart(2, '0');
    }

    // -----------------------------------------------
    // Heartbeat: simpan remaining_seconds ke server
    // setiap 10 detik agar selalu akurat walau refresh
    // -----------------------------------------------
    function saveRemainingToServer() {
        fetch(PAUSE_URL, {
            method   : 'POST',
            headers  : {
                'X-CSRF-TOKEN'     : CSRF,
                'X-Requested-With' : 'XMLHttpRequest',
                'Content-Type'     : 'application/x-www-form-urlencoded',
            },
            body     : 'mysqlid=' + MYSQLID + '&remaining_seconds=' + countdownSeconds + '&heartbeat=1',
            keepalive: true,
        });
    }

    function startHeartbeat() {
        clearInterval(heartbeatInterval);
        heartbeatInterval = setInterval(saveRemainingToServer, 10000);
    }

    function stopHeartbeat() {
        clearInterval(heartbeatInterval);
        heartbeatInterval = null;
    }

    function startCountdown() {
        clearInterval(timerInterval);
        const timerDisplay = document.getElementById('countdown-timer');
        if (!timerDisplay) return;

        // Gunakan Date.now() sebagai referensi waktu agar tidak drift.
        // setInterval tidak dijamin tepat 1000ms; akumulasi error bisa 3-5 detik
        // per menit jika hanya mengandalkan counter.
        const startedAt   = Date.now();
        const initialSecs = countdownSeconds; // snapshot saat timer mulai

        function tick() {
            const elapsed = Math.floor((Date.now() - startedAt) / 1000);
            countdownSeconds = Math.max(0, initialSecs - elapsed);
            timerDisplay.textContent = formatTime(countdownSeconds);

            if (countdownSeconds <= 0) {
                clearInterval(timerInterval);
                stopHeartbeat();
                timerDisplay.textContent = 'Waktu Habis!';
                autoSubmitAllAnswer();
                return;
            }
        }
        tick();
        timerInterval = setInterval(tick, 1000);
        startHeartbeat();
    }

    function stopCountdown() {
        clearInterval(timerInterval);
        timerInterval = null;
        stopHeartbeat();
    }

    // -----------------------------------------------
    // Pause: simpan remaining_seconds lalu stop timer
    // Pakai form-urlencoded agar terbaca Laravel
    // Gunakan sendBeacon agar request tetap terkirim saat beforeunload
    // -----------------------------------------------
    function pauseTimerOnServer() {
        stopCountdown();
        const body = 'mysqlid=' + MYSQLID + '&remaining_seconds=' + countdownSeconds + '&_token=' + encodeURIComponent(CSRF);
        if (navigator.sendBeacon) {
            // sendBeacon: fire-and-forget, dijamin terkirim walau tab ditutup
            const blob = new Blob([body], { type: 'application/x-www-form-urlencoded' });
            navigator.sendBeacon(PAUSE_URL, blob);
        } else {
            fetch(PAUSE_URL, {
                method   : 'POST',
                headers  : {
                    'X-CSRF-TOKEN'     : CSRF,
                    'X-Requested-With' : 'XMLHttpRequest',
                    'Content-Type'     : 'application/x-www-form-urlencoded',
                },
                body     : body,
                keepalive: true,
            });
        }
    }

    // -----------------------------------------------
    // Resume: ambil remaining_seconds dari server
    // -----------------------------------------------
    function resumeTimerFromServer() {
        fetch(RESUME_URL, {
            method  : 'POST',
            headers : {
                'X-CSRF-TOKEN'     : CSRF,
                'X-Requested-With' : 'XMLHttpRequest',
                'Content-Type'     : 'application/x-www-form-urlencoded',
            },
            body: 'mysqlid=' + MYSQLID,
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.sisa_detik !== undefined) {
                countdownSeconds = data.sisa_detik;
            }
            startCountdown();
        })
        .catch(() => startCountdown());
    }

    // -----------------------------------------------
    // Klik tombol "<" / navigasi keluar → pause
    // Dibedakan dari refresh dengan performance.navigation
    // -----------------------------------------------
    window.addEventListener('beforeunload', function () {
        // Tandai bahwa halaman akan unload
        sessionStorage.setItem('_leaving_' + MYSQLID, Date.now());
        pauseTimerOnServer();
    });

    // Kembali via bfcache (back button browser) → resume
    window.addEventListener('pageshow', function (e) {
        if (e.persisted) {
            resumeTimerFromServer();
        }
    });

    // -----------------------------------------------
    // Auto submit saat waktu habis
    // -----------------------------------------------
    function autoSubmitAllAnswer() {
        if (window._autoSubmitRunning) return;
        window._autoSubmitRunning = true;

        // Kirim remaining_seconds=0 langsung di body finishTopic
        // agar server PASTI pakai nilai ini, bukan dari heartbeat terakhir.
        // sendBeacon tidak dijamin urutan kedatangannya di server.
        fetch('{{ route('v2.student.finish.topic') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN'    : CSRF,
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type'    : 'application/json'
            },
            body: JSON.stringify({ mysqlid: MYSQLID, remaining_seconds: 0 })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                fetch('{{ route('v2.student.reset.testing.db') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN'    : CSRF,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type'    : 'application/json'
                    },
                    body: JSON.stringify({ mysqlid: MYSQLID })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) window.location.href = '{{ url("/select/start") }}';
                    else alert('Gagal reset: ' + data.message);
                });
            } else {
                alert('Gagal menyimpan durasi pengerjaan.');
            }
        });
    }

    // -----------------------------------------------
    // Event delegation untuk pagination — satu listener
    // permanen di #answer-section-wrapper, tidak perlu
    // re-bind setelah innerHTML diganti
    // -----------------------------------------------
    document.getElementById('answer-section-wrapper').addEventListener('click', function(e) {
        const btn = e.target.closest('.answer-pagination');
        if (!btn || btn.disabled) return;

        const phase   = btn.getAttribute('data-phase');
        const page    = btn.getAttribute('data-page');
        const start   = btn.getAttribute('data-start')
                        || document.querySelector('#answer-section input[name="start"]')?.value;
        const mysqlid = document.querySelector('#answer-section input[name="mysqlid"]')?.value
                        || MYSQLID;

        fetch(`{{ route('v2.showTopicDetail') }}?mysqlid=${mysqlid}&start=${start}&phase=${phase}&page=${page}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.text())
        .then(html => {
            document.getElementById('answer-section').innerHTML = html;
            if (typeof updateSidebarProgress === 'function') updateSidebarProgress();
            if (typeof updateSidebar === 'function') updateSidebar();
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        bindAnswerSectionForm();
        bindRunQueryForm();

        const navEntry = performance.getEntriesByType('navigation')[0];
        const isRefresh = navEntry && navEntry.type === 'reload';

        if (isRefresh) {
            resumeTimerFromServer();
        } else {
            startCountdown();
        }
    });
</script>
</body>
</html>