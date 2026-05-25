<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>iCLOP</title>
    <link rel="icon" href="./images/logo.png" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        .container-fluid {
            flex: 1 0 auto;
            display: flex;
            flex-direction: column;
        }
        .row {
            flex-grow: 1;
        }
        main {
            overflow-y: auto;
        }

        /* .footer {
            flex-shrink: 0;
        } */
        .text {
            font-family: 'Poppins', sans-serif;
            color: #3F3F46;
        }


        .text-list {
            font-family: 'Poppins', sans-serif;
            color: #3F3F46;
        }

        .footer {
            background-color: #EAEAEA;
            color: #636363;
            text-align: center;
            padding: 10px 0;
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
        }

        .progress-bar {
            width: 0;
            height: 30px;
            background-color: #4caf50;
            text-align: center;
            line-height: 30px;
            color: white;
        }

        .progress-text {
            margin-top: 10px;
            font-size: 18px;
            text-align: center;
        }

        .main-container {
            min-height: 100vh;
            overflow-y: auto;
        }
    </style>
</head>
<!-- This is body test -->

<body class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-light bg-light" style="padding: 15px 20px; border-bottom: 1px solid #E4E4E7; font-family: 'Poppins', sans-serif;">
        <a class="navbar-brand" href="{{ route('learning_student') }}">
        <img src="{{ asset('images/left-arrow.png') }}" style="height: 24px; margin-right: 10px;">
            {{ $task->task_name }}
        </a>
    </nav>

     <!-- Sidebar -->
     <div id="sidebar" class="sidebar" style="border-left: 1px solid #E4E4E7; padding: 30px 30px; width: 400px">
        <p class="text-list" style="font-size: 18px; font-weight: 600; font-size: 20px">Task List</p>
        <div class="progress-container">
            <div class="progress-bar" id="myProgressBar"></div>
        </div>
        <div class="progress-text" id="progressText">0%</div>
        <ul class="list">
            <li class="list-item" onclick="toggleItem(this)">
                <img class="list-item-icon" src="{{ asset('images/down-arrow.png') }}" style="height: 24px">
                <span class="list-item-title">Start to learn Data Analytics with Python</span>
            </li>
            <div class="expandable-content">

                <div style="display: flex; flex-direction: column; align-items: left;">

                    <div class="row">
                        <div class="col-sm-1">
                            <label class="radio-label">
                                <input type="radio" name="itemSelection" value="item1">
                            </label>
                        </div>
                        <div class="col">
                            <p class="text" id="requirement">Requirement</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-1">
                            <label class="radio-label">
                                <input type="radio" name="itemSelection" value="item2">
                            </label>
                        </div>
                        <div class="col">
                            <p class="text" id="description">Description</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-1">
                            <label class="radio-label">
                                <input type="radio" name="itemSelection" value="item3">
                            </label>
                        </div>
                        <div class="col">
                            <p class="text" id="resource">Resource</p>
                        </div>
                    </div>

                </div>

            </div>

            <li class="list-item" onclick="toggleItem(this)">
                <img class="list-item-icon" src="{{ asset('images/down-arrow.png') }}" style="height: 24px">
                <span class="list-item-title">Task</span>
            </li>
            <div class="expandable-content">
                <div style="display: flex; flex-direction: column; align-items: left;">
                    @if($task->material && $task->material->tasks)
                        @foreach($task->material->tasks as $index => $materialTask)
                        <div class="row">
                            <div class="col-sm-1">
                                <label class="radio-label">
                                    <input type="radio" name="taskSelection" value="{{ $materialTask->id }}" onchange="goToTaskPage({{ $materialTask->id }})" {{ $materialTask->id == $task->id ? 'checked' : '' }}>
                                </label>
                            </div>
                            <div class="col">
                                <p class="text">
                                    @php
                                        $fileName = pathinfo($materialTask->pdf_path, PATHINFO_FILENAME);
                                        $displayName = str_replace('Data_analitik_', '', $fileName);
                                        $displayName = ucfirst(str_replace('_', ' ', $displayName));
                                    @endphp
                                    {{ $displayName }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p>Tidak ada tugas tersedia untuk materi ini.</p>
                    @endif
                </div>
            </div>

            <li class="list-item" onclick="toggleItem(this)">
               <img class="list-item-icon" src="{{ asset('images/down-arrow.png') }}" style="height: 24px">
                <span class="list-item-title">Assignment Answer Submission</span>
            </li>
            <div class="expandable-content">
                <div style="display: flex; flex-direction: column; align-items: left;">

                    <div class="row">
                        <div class="col-sm-1">
                            <label class="radio-label">
                                <input type="radio" name="itemSelection" value="item1">
                            </label>
                        </div>
                        <div class="col">
                            <p class="text">Requirement</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-1">
                            <label class="radio-label">
                                <input type="radio" name="itemSelection" value="item2">
                            </label>
                        </div>
                        <div class="col">
                            <p class="text">Description</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-1">
                            <label class="radio-label">
                                <input type="radio" name="itemSelection" value="item3">
                            </label>
                        </div>
                        <div class="col">
                            <p class="text">Resource</p>
                        </div>
                    </div>

                </div>
            </div>
        </ul>
    </div>

    <!-- Content -->
    <div class="container-fluid flex-grow-1 d-flex">
        <div class="row flex-grow-1">
            <main class="col-md-9 col-lg-10 px-md-4">
            
                <div style="padding-top: 36px; margin-right: 200px">
                    <p class="text-list" style="font-size: 36px; font-weight: 600">
                        <h1>{{ $task->task_name }}</h1>
                    </p>

                    @if($task->pdf_path)
                        <a href="{{ route('task.download-pdf', $task) }}" class="btn btn-primary">Download PDF</a>
                    @endif

                    @if($task->pdf_path)
                        <div style="margin-top: 20px;">
                            <h2>Task PDF</h2>
                            @php
                                $pdfPath = 'pdfs/' . $task->pdf_path;
                            @endphp
                            @if(Storage::disk('public')->exists($pdfPath))
                                <embed src="{{ asset('storage/' . $pdfPath) }}" type="application/pdf" width="100%" height="600px" />
                            @else
                                <p>PDF tidak ditemukan. File: {{ $task->pdf_path }}</p>
                            @endif
                        </div>
                    @endif

                    <!-- Tampilkan informasi task lainnya -->

                    @if($task->material)
                        <h3>Related Tasks:</h3>
                        <ul>
                        @foreach($task->material->tasks as $relatedTask)
                            <li>
                                <a href="{{ route('task.show', $relatedTask->id+1) }}">{{ $relatedTask->task_name }}</a>
                            </li>
                        @endforeach
                        </ul>
                    @endif
                    <!-- <div class="container texts" style="padding-top: 36px; margin-right: 200px; margin-bottom: 80px"> -->
                    <div class="container" style="padding-top: 36px; margin-right: 200px; margin-bottom: 80px">
                        <h1>Task Submission</h1>

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- <form action="{{ route('student.submission.store') }}" method="POST" enctype="multipart/form-data"> -->
                        <form>
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <div class="form-group">
                                <label for="file">Upload your Python file:</label>
                                <input type="file" name="file" id="file" required>
                            </div>
                            <button class="button" type="btn btn-primary" id="submitButton">Submit</button>
                            <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                        </form>

                        @if(isset($submission))
                            <h2>Submission Details</h2>
                            <p>Submission Count: {{ $submission->submission_count }}</p>
                            <h3>Test Results:</h3>
                            <pre>{{ $submission->test_result }}</pre>
                        @endif
                        @if(isset($submission) && $submission->file_path)
                            <h3>Kode yang Disubmit:</h3>
                            <pre><code>{{ $submittedCode }}</code></pre>
                        @else
                            <p>Belum ada submission untuk tugas ini.</p>
                        @endif
                    </div>
                </div>

                

            </main>
        </div>
    </div>
     <!-- Footer -->
     <footer class="footer py-3 bg-light">
        <div class="container text-center">
            <span class="text-muted">© 2023 Your Website. All rights reserved.</span>
        </div>
    </footer>

    
    <script>
    function goToTaskPage(taskId) {
        window.location.href = "/task/" + taskId;
    }
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

        function move() {
            var progressBar = document.getElementById("myProgressBar");
            var progressText = document.getElementById("progressText");
            var width = 0;
            var interval = setInterval(frame, 10);

            function frame() {
            if (width >= 50) {
                clearInterval(interval);
            } else {
                width++;
                progressBar.style.width = width + "%";
                progressText.innerHTML = width + "%";
            }
            }
        }

        move();

        function showTask(taskId) {
            $.ajax({
                url: '/get-task-content/' + taskId,
                method: 'GET',
                success: function(response) {
                    $('#taskContent').html(response);
                },
                error: function(xhr) {
                    console.log('Error:', xhr);
                    alert('Gagal memuat konten task. Silakan coba lagi.');
                }
            });
        }
    </script>
    
<!-- // // TERBARUUUU  NEW -->
<script>
    $(document).ready(function() {
        $('#submitButton').click(function(event) {
            event.preventDefault();

            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            const resultsDiv = $('.texts');
            let resultData = {};

            // Membuat objek FormData untuk mengirim file
            var formData = new FormData();
            formData.append('file', $('#file')[0].files[0]);
            formData.append('_token', csrfToken);
            formData.append('task_id', {{ $task->id }});

            $.ajax({
                url: "http://127.0.0.1:8080/api/run-python",
                type: "POST",
                data: formData,
                
                processData: false,  // Penting untuk mengirim FormData
                contentType: false,  // Penting untuk mengirim FormData
                                                                                                beforeSend: function() {
                   
                    $('#submitButton').hide();
                    $('#spinner').show();
                    $.ajax({
                        url: "{{ route('student.submission.store') }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            console.log('Data berhasil dikirim ke server:', response);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log('Error route(student.submission.store):', textStatus, errorThrown);
                            $('#message').text('An error occurred while submitting the request.');
                        }
                    });
                },
                success: function(data) {
                    $('#spinner').hide();
                    console.log(data);

                    resultsDiv.empty();

                    if (data.output) {
                        resultsDiv.append('<h2>Output:</h2><p>' + data.output + '</p>');
                    }

                    resultData = {
                        output: data.output || ''
                    };
                    console.log(resultData);
                    // Mengirim resultData ke controller Laravel
                    $.ajax({
                        url: "{{ route('store_python_result_data') }}",
                        type: "POST",
                        
                        data: {
                            _token: csrfToken,
                            output: resultData.output,
                            task_id: {{ $task->id }}
                        },
                        beforeSend: function() {
                          console.log(csrfToken);
                        },
                        success: function(response) {
                            console.log('Data berhasil dikirim ke server:', response);
                            window.location.reload(true);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log('Error route(store_python_result_data) :', textStatus, errorThrown);
                            $('#message').text('An error occurred while submitting the request.');
                        }
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error', textStatus, errorThrown);
                    $('#message').text('An error occurred while submitting the request.');
                }
            });
        });
    });
</script> 
    
</body>

</html>