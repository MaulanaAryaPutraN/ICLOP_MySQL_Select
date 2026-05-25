<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <title>iCLOP</title>
    <link rel="icon" href={{asset("./images/logo.png")}} type="image/png">
    <style>
        .text {
            font-family: 'Poppins', sans-serif;
            color: #3F3F46;
            text-decoration: none
        }

        .text-list {
            font-family: 'Poppins', sans-serif;
            color: #3F3F46;
        }

        .footer {
            background-color: #EAEAEA;
            color: #636363;
            text-align: center;
            font-size: 12px;
            padding: 5px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        /* CSS untuk mengatur sidebar */
        .sidebar {
            width: 250px;
            background-color: #ffffff;
            height: 100%;
            position: fixed;
            top: 0;
            right: 0;
            overflow-x: hidden;
            padding-top: 20px;
        }

        /* Gaya dropdown */
        .dropdown {
            padding: 6px 8px;
            display: inline-block;
            cursor: pointer;
        }

        /* Gaya dropdown content */
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .list-item {
            display: flex;
            align-items: center;
            /* justify-content: space-between; */
            padding: 10px;
            border: 1px solid #E4E4E7;
            cursor: pointer;
            margin-bottom: 10px;
            border: none;
        }

        .list-item:hover {
            background-color: #F5F5F8;
        }

        .list-item-title {
            font-size: 18px;
            margin-left: 10px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            color: #3F3F46;
        }

        .list-item-icon {
            font-size: 20px;
        }

        .expandable-content {
            margin-top: 0px;
            display: none;
            padding: 10px;
            border-top: 1px solid #E4E4E7;
            border: none;
            margin-left: 32px;
        }

        .radio-label {
            font-weight: bold;
            color: #333;
            font-size: 18px;
        }

        .progress-container {
            width: 100%;
            background-color: #f1f1f1;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        #progressbar {
            height: 20px;
            background-color: #4caf50;
            border-radius: 10px;
            transition: width 0.5s;
        }

        .progress-text {
            /* margin-top: 10px; */
            font-size: 18px;
            text-align: end;
        }

        .text:hover {
        color: black; /* Change text color to blue on hover */
        text-decoration: underline; /* Add underline on hover */
    }
    </style>
    
    <style>
        @media only screen and (max-width: 600px) {
            #sidebar {
                display: none; /* Hide sidebar on small screens */
            }

            div[style*="max-width: 800px"] {
                max-width: 90%; /* Adjust max-width of container */
            }
        }
    </style>
</head>
<!-- This is body test -->

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-light bg-light" style="padding: 15px 20px; border-bottom: 1px solid #E4E4E7; font-family: 'Poppins', sans-serif;">
        <a class="navbar-brand" href="{{ route('mysql_welcome') }}">
            <img src="{{ asset('images/left-arrow.png') }}" style="height: 24px; margin-right: 10px;">
            {{ $topicsNavbar->title; }}
        </a>
    </nav>

        <!-- Sidebar -->
        <div class="sidebar" style="border-left: 1px solid #E4E4E7; padding: 20px; width: 100%; max-width: 400px;">
            <div class="d-flex align-items-center justify-content-between mb-3" style="gap: 10px;">
                <p class="text-list my-2" style="font-size: 18px; font-weight: 600; font-size: 20px">
                    <img src="{{ asset('images/right.png') }}" style="height: 24px; margin-right: 10px; border:1px solid; border-radius:50%"> Task List
                </p>
                @if(!$isFinished)
                    <div  id="countdown-timer" style="font-size: 16px; color: white; font-weight: bold; margin-bottom: 0;
                   background: #0066ff; border-radius: 10px; padding: 5px; text-align: center; border: 1px solid #0066ff;"></div>
                @endif
            </div>
            <div class="progress-text" id="progress-text">{{ $progressPercent }}%</div>
            <div class="progress-container">
                <div id="progressbar" style="width: {{ $progressPercent }}%;"></div>
            </div>
            
            {{-- Bagian checklist subtopik, hanya ini yang AJAX --}}
            <div id="sidebar">
                @include('mysql_dml.student.material.sidebar', [
                    'rows' => $rows,
                    'detailCount' => $detailCount,
                    'mysqlid' => $mysqlid,
                    'detailId' => $detail->id ?? null
                ])
            </div>
        </div>

    <div style="padding: 20px; max-width: 68%; margin-left:5px;  ">
        <div style="border: 1px solid #ccc; padding: 20px 10px 10px 30px; border-radius: 10px;margin-bottom:10px">
            @php
                if($pdf_reader == 0):
                echo $html_start;
            @endphp
                    
                    
            @php
                else:
            @endphp
            
            <iframe src="{{ asset('mysql/DML/'. $html_start ) }}" style="width: 100%; height: 510px"></iframe></iframe>
            @php
                endif;
            @endphp

        </div>
        <div  style="padding-top: 20px; padding-bottom: 2rem; margin-bottom: 5rem;">
            @include('mysql_dml.student.material._answer_section')
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        © 2025 Your Website. All rights reserved.
    </footer>
    
    <script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
    <script type="text/javascript">
        ClassicEditor
            .create(document.querySelector('#editor'), {
                ckfinder: {
                    
                    uploadUrl: '{{route('uploadimage').'?_token='.csrf_token()}}',
                
                }
            });
    </script>
    <script>
        
        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("active");
        }

        function toggleItem(item) {
            const content = item.nextElementSibling;
            const icon = item.querySelector('.list-item-icon');
            content.style.display = content.style.display === 'block' ? 'none' : 'block';
            icon.style.transform = content.style.display === 'block' ? 'rotate(180deg)' : 'none';
        }

        const radioButtons = document.querySelectorAll('input[name="itemSelection"]');
        const textElements = document.querySelectorAll('.text');

        radioButtons.forEach((button, index) => {
            button.addEventListener('change', () => {
                textElements.forEach((textElement, i) => {
                    if (i === index) {
                        textElement.style.fontWeight = 'bold';
                    } else {
                        textElement.style.fontWeight = 'normal';
                    }
                });
            });
        });

        function bindAnswerSectionForm() {
            const form = document.querySelector('#answer-section form');
            if(form){
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    // Spinner & disable
                    const submitBtn = form.querySelector('#submit-btn');
                    const spinner = form.querySelector('#submit-spinner');
                    const btnText = form.querySelector('#submit-btn-text');
                    const textarea = form.querySelector('textarea[name="userInput"]');
                    if(submitBtn && spinner && btnText) {
                        submitBtn.disabled = true;
                        btnText.style.display = 'none';
                        spinner.style.display = 'inline-block';
                    }

                    const formData = new FormData(form); // Ambil data sebelum disable textarea!
                    if(textarea) textarea.disabled = true;

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
                        bindAnswerSectionPagination();
                        bindRunQueryForm();
                        updateSidebarProgress();
                        updateSidebar(); // <-- Tambahkan ini!
                    })
                    .catch(err => {
                        alert('Error: ' + err);
                        if(submitBtn && spinner && btnText) {
                            submitBtn.disabled = false;
                            btnText.style.display = '';
                            spinner.style.display = 'none';
                        }
                        if(textarea) textarea.disabled = false;
                    });
                });
            }
        }

        function bindAnswerSectionPagination() {
            document.querySelectorAll('.answer-pagination').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (btn.disabled) return;
                    const page = btn.getAttribute('data-page');
                    const mysqlid = document.querySelector('input[name="mysqlid"]').value;
                    const start = document.querySelector('input[name="start"]').value;
                    fetch(`{{ route('showTopicDetail') }}?mysqlid=${mysqlid}&start=${start}&page=${page}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('answer-section').innerHTML = html;
                        bindAnswerSectionForm();
                        bindAnswerSectionPagination();
                        bindRunQueryForm();
                        updateSidebarProgress();
                        updateSidebar();
                    })
                    .catch(err => alert('Error: ' + err));
                });
            });
        }

        function bindRunQueryForm() {
            const form = document.getElementById('run-query-form');
            if(form){
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
                    .then(data => {
                        document.getElementById('query-result').innerHTML = data.html;
                    })
                    .catch(() => {
                        document.getElementById('query-result').innerHTML = '<div class="alert alert-danger">Query failed or not allowed.</div>';
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.textContent = 'Run Query';
                    });
                });
            }
        }

        function updateSidebarProgress() {
            // Update progress text dan progressbar AJAX
            const mysqlid = document.querySelector('input[name="mysqlid"]').value;
            fetch('{{ route('student.progress.ajax') }}?mysqlid=' + mysqlid, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                // Update progress text dan progressbar
                document.querySelector('.progress-text').textContent = data.progress + '%';
                document.getElementById('progressbar').style.width = data.progress + '%';
            });
        }

        function updateSidebar() {
            const mysqlid = document.querySelector('input[name="mysqlid"]').value;
            const start = document.querySelector('input[name="start"]').value;
            fetch('{{ route('student.sidebar.ajax') }}?mysqlid=' + mysqlid + '&start=' + start, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.text())
            .then(html => {
                document.getElementById('sidebar').innerHTML = html;
            });
        }

        // Timer dari backend (dalam detik), misal 1 jam = 3600 detik
        let countdownSeconds = {{ $countdownSeconds ?? 3600 }};
        let timerInterval;

        function startCountdown() {
            const timerDisplay = document.getElementById('countdown-timer');
            function updateTimer() {
                let hours = Math.floor(countdownSeconds / 3600);
                let minutes = Math.floor((countdownSeconds % 3600) / 60);
                let seconds = countdownSeconds % 60;
                timerDisplay.textContent = 
                    (hours > 0 ? String(hours).padStart(2, '0') + ':' : '') +
                    String(minutes).padStart(2, '0') + ':' +
                    String(seconds).padStart(2, '0');
                if (countdownSeconds <= 0) {
                    clearInterval(timerInterval);
                    timerDisplay.textContent = 'Waktu Habis!';
                    // Otomatis submit all answer
                    autoSubmitAllAnswer();
                }
                countdownSeconds--;
            }
            updateTimer();
            timerInterval = setInterval(updateTimer, 1000);
        }

        // Fungsi otomatis submit all answer
        function autoSubmitAllAnswer() {
            // Cegah double submit
            if (window._autoSubmitRunning) return;
            window._autoSubmitRunning = true;

            // 1. Simpan durasi pengerjaan
            fetch('{{ route('student.finish.topic') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    mysqlid: '{{ $mysqlid }}'
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    // 2. Jika sukses, lanjut reset database
                    fetch('{{ route('student.reset.testing.db') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            mysqlid: '{{ $mysqlid }}'
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success){
                            window.location.href = '{{ url("/mysql/start") }}';
                        } else {
                            alert('Gagal reset: ' + data.message);
                        }
                    });
                } else {
                    alert('Gagal menyimpan durasi pengerjaan.');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            bindAnswerSectionForm();
            bindAnswerSectionPagination();
            bindRunQueryForm();
            startCountdown();
        });
    </script>
</body>

</html>
