<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
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
            Task List
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
        <div id="modul-wrapper">
            @if($pdf_reader == 0)
                {!! $html_start !!}
            @else
                <iframe src="{{ asset('select/modul/' . $html_start) }}" style="width:100%; height:510px;"></iframe>
            @endif
        </div>
    </div>

    {{-- Answer Section --}}
    <div id="answer-section-wrapper" style="padding-top:20px; padding-bottom:2rem; margin-bottom:5rem;">
        <div id="answer-section">
            @include('select.student._answer_section')
        </div>
    </div>
</div>

@include('select.student.modal.detail_submission')

{{-- ============================================================
     MODAL POPUP HINT
     Ditaruh di luar #answer-section-wrapper supaya tidak ikut
     hilang/ter-replace tiap kali innerHTML #answer-section diganti
     via AJAX (submit jawaban / pindah soal).
============================================================ --}}
<div id="hint-modal" class="hint-overlay" style="display:none;">
    <div class="hint-card">
        <div class="hint-icon-circle hint-icon-warning">
            <i class="fas fa-lightbulb"></i>
        </div>
        <h5 class="hint-title">Butuh Bantuan?</h5>
        <p class="hint-subtitle">Kamu sudah mencoba lebih dari 2 kali untuk soal ini.</p>

        <div class="hint-warning-box">
            <i class="fas fa-exclamation-triangle"></i>
            <span>Membuka kunci jawaban mengurangi skor soal ini sebesar <strong>20 poin</strong>.</span>
        </div>

        <div class="hint-actions">
            <button id="hint-decline-btn" type="button" class="btn btn-outline-secondary flex-fill">
                Coba Lagi
            </button>
            <button id="hint-confirm-btn" type="button" class="btn btn-warning flex-fill fw-semibold">
                Tampilkan Hint
            </button>
        </div>
    </div>
</div>

<div id="hint-result-modal" class="hint-overlay" style="display:none;">
    <div class="hint-card">
        <div class="hint-header-row">
            <div class="hint-icon-circle hint-icon-success">
                <i class="fas fa-key"></i>
            </div>
            <h5 class="hint-title mb-0">Kunci Jawaban</h5>
        </div>

        <pre id="hint-result-content" class="hint-query-box"></pre>

        <div class="hint-penalty-note">
            <i class="fas fa-minus-circle"></i>
            <span>Skor soal ini sudah dikurangi 20 poin.</span>
        </div>

        <button id="hint-result-close-btn" type="button" class="btn btn-secondary w-100 fw-semibold">
            Tutup
        </button>
    </div>
</div>

<style>
.hint-overlay {
    position: fixed; top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(20,20,25,0.55);
    display: flex; align-items: center; justify-content: center;
    z-index: 1050;
    padding: 16px;
    animation: hintFadeIn 0.15s ease-out;
}
@keyframes hintFadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}
.hint-card {
    background: #fff;
    border-radius: 16px;
    padding: 28px;
    max-width: 400px;
    width: 100%;
    box-shadow: 0 12px 40px rgba(0,0,0,0.18);
    animation: hintSlideUp 0.2s ease-out;
}
@keyframes hintSlideUp {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}
.hint-icon-circle {
    width: 48px; height: 48px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; margin-bottom: 14px; flex-shrink: 0;
}
.hint-icon-warning { background: #fff3cd; color: #b8860b; }
.hint-icon-success { background: #d4edda; color: #1e7e34; }
.hint-header-row {
    display: flex; align-items: center; gap: 12px; margin-bottom: 16px;
}
.hint-header-row .hint-icon-circle { margin-bottom: 0; }
.hint-title {
    font-weight: 600; margin: 0 0 6px; font-size: 18px; color: #1a1a1a;
}
.hint-subtitle {
    font-size: 14px; color: #6b6b6b; margin: 0 0 16px; line-height: 1.6;
}
.hint-warning-box {
    background: #fff8e6;
    border: 1px solid #ffe7a0;
    border-radius: 10px;
    padding: 12px 14px;
    display: flex; align-items: flex-start; gap: 10px;
    margin-bottom: 20px;
    font-size: 13px;
    color: #856404;
    line-height: 1.5;
}
.hint-warning-box i { font-size: 15px; margin-top: 2px; flex-shrink: 0; }
.hint-actions {
    display: flex; gap: 10px;
}
.hint-query-box {
    background: #f4f4f6;
    border: 1.5px solid #d4d4d9;
    border-radius: 10px;
    padding: 16px 18px;
    font-family: 'SFMono-Regular', 'Consolas', 'Liberation Mono', Menlo, Monaco, monospace;
    font-size: 15px;
    font-weight: 700;
    color: #16161f;
    white-space: pre-wrap;
    word-break: break-word;
    margin: 0 0 16px;
    line-height: 1.8;
    letter-spacing: 0.3px;
    text-shadow: 0 0 0.4px currentColor, 0 0 0.4px currentColor;
    letter-spacing: 0.2px;
}
.hint-penalty-note {
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; color: #cc0000;
    margin-bottom: 18px;
}
.hint-penalty-note i { font-size: 14px; }
</style>

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
                bindHintPopup();
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
    const HINT_URL   = '{{ route('v2.student.show.hint') }}';

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

        const phase       = btn.getAttribute('data-phase');
        const page        = btn.getAttribute('data-page');
        const start       = btn.getAttribute('data-start')
                            || document.querySelector('#answer-section input[name="start"]')?.value;
        const mysqlid     = document.querySelector('#answer-section input[name="mysqlid"]')?.value
                            || MYSQLID;
        const currentStart = document.querySelector('#answer-section input[name="start"]')?.value;
        const isSubtopicChange = start && currentStart && start !== currentStart;

        fetch(`{{ route('v2.showTopicDetail') }}?mysqlid=${mysqlid}&start=${start}&phase=${phase}&page=${page}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.text())
        .then(html => {
            document.getElementById('answer-section').innerHTML = html;
            bindAnswerSectionForm();
            bindRunQueryForm();
            bindHintPopup();
            if (typeof updateSidebarProgress === 'function') updateSidebarProgress();
            if (typeof updateSidebar === 'function') updateSidebar();

            // Jika pindah subtopik, update juga konten modul/PDF
            if (isSubtopicChange) {
                fetch(`{{ route('v2.showTopicDetail') }}?mysqlid=${mysqlid}&start=${start}&modul_only=1`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(r => r.json())
                .then(data => {
                    const modulWrapper = document.getElementById('modul-wrapper');
                    if (!modulWrapper) return;
                    if (data.pdf_reader == 1) {
                        modulWrapper.innerHTML = `<iframe src="${data.modul_url}" style="width:100%; height:510px;"></iframe>`;
                    } else {
                        modulWrapper.innerHTML = data.html_start;
                    }
                })
                .catch(() => {});
            }
        });
    });

    // -----------------------------------------------
    // FITUR HINT — popup penawaran kunci jawaban
    //
    // Dipanggil tiap kali #answer-section dirender ulang:
    //   1. Saat halaman pertama kali dimuat
    //   2. Setelah submit jawaban (bindAnswerSectionForm)
    //   3. Setelah pindah soal/subtopik (pagination)
    //
    // Membaca data dari <input id="hint-context"> yang dikirim
    // controller (data-show-offer, data-hint-used, dll).
    // -----------------------------------------------
    function bindHintPopup() {
        const ctx = document.getElementById('hint-context');
        if (!ctx) return; // bukan soal tugas, tidak ada hint

        const showOffer = ctx.getAttribute('data-show-offer') === '1';

        if (showOffer) {
            document.getElementById('hint-modal').style.display = 'flex';
        }

        // Tombol "Tidak, saya coba lagi"
        const declineBtn = document.getElementById('hint-decline-btn');
        if (declineBtn) {
            declineBtn.onclick = function() {
                document.getElementById('hint-modal').style.display = 'none';
            };
        }

        // Tombol "Ya, tampilkan hint"
        const confirmBtn = document.getElementById('hint-confirm-btn');
        if (confirmBtn) {
            confirmBtn.onclick = function() {
                confirmBtn.disabled = true;
                confirmBtn.textContent = 'Memuat...';

                fetch(HINT_URL, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN'     : CSRF,
                        'X-Requested-With' : 'XMLHttpRequest',
                        'Content-Type'     : 'application/json',
                    },
                    body: JSON.stringify({
                        topic_detail_id : ctx.getAttribute('data-topic-detail-id'),
                        mysqlid         : ctx.getAttribute('data-mysqlid'),
                        submission_type : 'tugas',
                        answer_number   : ctx.getAttribute('data-answer-number'),
                    }),
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('hint-modal').style.display = 'none';
                    confirmBtn.disabled = false;
                    confirmBtn.textContent = 'Ya, tampilkan hint (-20 poin)';

                    if (data.success) {
                        document.getElementById('hint-result-content').textContent = data.hint;
                        document.getElementById('hint-result-modal').style.display = 'flex';

                        // Skor berubah → update progress bar & sidebar
                        if (typeof updateSidebarProgress === 'function') updateSidebarProgress();
                        if (typeof updateSidebar === 'function') updateSidebar();
                    } else {
                        alert(data.message || 'Gagal membuka hint.');
                    }
                })
                .catch(() => {
                    document.getElementById('hint-modal').style.display = 'none';
                    confirmBtn.disabled = false;
                    confirmBtn.textContent = 'Ya, tampilkan hint (-20 poin)';
                    alert('Terjadi kesalahan saat membuka hint.');
                });
            };
        }

        // Tombol tutup di modal hasil hint
        const closeBtn = document.getElementById('hint-result-close-btn');
        if (closeBtn) {
            closeBtn.onclick = function() {
                document.getElementById('hint-result-modal').style.display = 'none';
            };
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        bindAnswerSectionForm();
        bindRunQueryForm();
        bindHintPopup();

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