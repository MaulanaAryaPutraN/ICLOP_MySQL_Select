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
    <link rel="icon" href="./images/logo.png" type="image/png">
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
            width: 40;
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
        .text:hover {
        color: black; /* Change text color to blue on hover */
        text-decoration: underline; /* Add underline on hover */
        }
        .loader{
        display: block;
        position: relative;
        height: 12px;
        width: 100%;
        border: 1px solid #fff;
        border-radius: 10px;
        overflow: hidden;
        }
        .loader::after {
        content: '';
        width: 40%;
        height: 100%;
        background: #0d6efd;
        position: absolute;
        top: 0;
        left: 0;
        box-sizing: border-box;
        animation: animloader 2s linear infinite;
        }
        
        @keyframes animloader {
        0% {
            left: 0;
            transform: translateX(-100%);
        }
        100% {
            left: 100%;
            transform: translateX(0%);
        }
        }
        .button {
        position: relative;
        transition: all 0.3s ease-in-out;
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
        padding-block: 0.5rem;
        padding-inline: 1.25rem;
        background-color: rgb(0 107 179);
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffff;
        gap: 10px;
        font-weight: bold;
        border: 3px solid #ffffff4d;
        outline: none;
        overflow: hidden;
        font-size: 15px;
        margin-top: 15px;
        }

        .icon {
        width: 24px;
        height: 24px;
        transition: all 0.3s ease-in-out;
        }

        .button:hover {
        transform: scale(1.05);
        border-color: #fff9;
        }

        .button:hover .icon {
        transform: translate(4px);
        }

        .button:hover::before {
        animation: shine 1.5s ease-out infinite;
        }

        .button::before {
        content: "";
        position: absolute;
        width: 100px;
        height: 100%;
        background-image: linear-gradient(
            120deg,
            rgba(255, 255, 255, 0) 30%,
            rgba(255, 255, 255, 0.8),
            rgba(255, 255, 255, 0) 70%
        );
        top: 0;
        left: -100px;
        opacity: 0.6;
        }

        @keyframes shine {
        0% {
            left: -100px;
        }

        60% {
            left: 100%;
        }

        to {
            left: 100%;
        }
        }
        .input-url {
        width: 200px;
        height: 35px;
        border-radius: 5px;
        outline: none;
        border: 1px solid #303030;
        border-bottom: 2px solid #9a9a9a;
        padding-left: 10px;
        padding-right: 10px;
        background-color: #dddbdb;
        color: black;
        transition: all 0.3s ease;
        }

        .input-url::placeholder {
        color: #9a9a9a;
        }

        .input-url:hover {
        background-color: #083d8b;
        }

        .input-url:active,
        .input-url:focus {
        background-color: #dbdbdb;
        border: 1px solid #dbdbdb;
        border-bottom: 2px solid #4cc2ff;
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
    <style>
        #progressbar {
            width: @php 
                    $width = session('params');
                    echo $width."%"; 
                   @endphp;
            height: 20px;
            background-color: #4caf50;
            border-radius: 10px;
        }

        
    </style>
</head>
<!-- This is body test -->

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-light bg-light" style="padding: 15px 20px; border-bottom: 1px solid #E4E4E7; font-family: 'Poppins', sans-serif;">
        <a class="navbar-brand" href="{{ route('welcome') }}">
            <img src="{{ asset('images/left-arrow.png') }}" style="height: 24px; margin-right: 10px;">
            {{ $row->title; }}
        </a>
    </nav>

     <!-- Sidebar -->
   
    

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar" style="border-left: 1px solid #E4E4E7; padding: 20px; width: 100%; max-width: 400px;">
        <p class="text-list" style="font-size: 18px; font-weight: 600; font-size: 20px"><img src="{{ asset('images/right.png') }}" style="height: 24px; margin-right: 10px; border:1px solid; border-radius:50%"> Task List</p>
        <div class="progress-container">
            <div id="progressbar"></div>
            
        </div>
        <div id="progress">0%</div>
        <ul class="list">
            @foreach($topics as $topic)
            @php
                /*$results = DB::select("select * from flutter_topics where id = ?", [$topic->id]);
                if (!empty($results)) {
                    $result = $results[0]; 
                    $result->id; 
                } else {
                    echo "No results found.";
                }*/
                if($topic->id == $_GET['flutterid'] ){
                    $display = "display:block !important";
                    $transform = "transform: rotate(180deg); !important";
                }else{
                    $display = "";
                    $transform = "";
                }
            @endphp
            <li class="list-item" onclick="toggleItem(this)">
                <img class="list-item-icon" src="{{ asset('images/down-arrow.png') }}" style="height: 24px; @php echo $transform; @endphp">
                <span class="list-item-title">{{ $topic->title }}   </span>
            </li>
            
            <div class="expandable-content" style="@php echo $display; @endphp">
            
                <div style="display: flex; flex-direction: column; align-items: left;">
                    @php
                
                        
                        $row = DB::table('flutter_topics')
                        ->leftJoin('flutter_topics_detail', 'flutter_topics.id', '=', 'flutter_topics_detail.id_topics')
                        ->select('*')
                        ->where('flutter_topics_detail.id_topics', '=',   $topic->id ) 
                        ->get();
                        $no = 1;
                    @endphp
                    @foreach($row as $r)
                    @php
                    $no++;
                    $count_ = ($no/$detailCount)*10;
                        $flutterdid = isset($_GET['start']) ? $_GET['start'] : '';
                        if($r->id == $flutterdid and $r->id_topics == $_GET['flutterid']){
                            $active = 'color:#000; font-weight:bold; text-decoration: underline;';

                        }else{
                            $active = '';
                        }
                    @endphp 
                    <div class="row">
                        <div class="col-sm-1">
                            <label class="radio-label">
                                <svg width="16" height="16" class="" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.9993 2.6665C8.63555 2.6665 2.66602 8.63604 2.66602 15.9998C2.66602 23.3636 8.63555 29.3332 15.9993 29.3332C23.3631 29.3332 29.3327 23.3636 29.3327 15.9998C29.3327 8.63604 23.3631 2.6665 15.9993 2.6665ZM5.33268 15.9998C5.33268 10.1088 10.1083 5.33317 15.9993 5.33317C21.8904 5.33317 26.666 10.1088 26.666 15.9998C26.666 21.8909 21.8904 26.6665 15.9993 26.6665C10.1083 26.6665 5.33268 21.8909 5.33268 15.9998Z" fill="#71717A"></path>
                                </svg>
                            </label>
                        </div>
                        <div class="col" style="padding-bottom: 1rem;">
                            <a class="text" style="{{ $active }};" href="{{ route('flutter_material_detail') }}?flutterid={{$topic->id}}&start={{$r->id}}" id="requirement" onclick="updateProgress(@php echo $count_ @endphp)">{{ $r->title }} </a> 
                        </div>
                    </div>
                    @endforeach
                    @php
                    $top = $topic->id;
                    $task = DB::table('flutter_task')->where('id_topics', $top)->first(); // Menggunakan first() untuk mengambil satu baris pertama
                    
                
                    @endphp
                    
                    @if($task)
                    @php
                    $tsk = $task->id;
                    $task_get = isset($_GET['task']) ? $_GET['task'] : '';
                    if($tsk == $task_get){
                        $active_task = 'color:#000; font-weight:bold; text-decoration: underline;';

                    }else{
                        $active_task = '';
                    }
                    
                    @endphp
                    <div class="row">
                        <div class="col-sm-1">
                            <label class="radio-label">
                                <svg width="16" height="16" class="" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.9993 2.6665C8.63555 2.6665 2.66602 8.63604 2.66602 15.9998C2.66602 23.3636 8.63555 29.3332 15.9993 29.3332C23.3631 29.3332 29.3327 23.3636 29.3327 15.9998C29.3327 8.63604 23.3631 2.6665 15.9993 2.6665ZM5.33268 15.9998C5.33268 10.1088 10.1083 5.33317 15.9993 5.33317C21.8904 5.33317 26.666 10.1088 26.666 15.9998C26.666 21.8909 21.8904 26.6665 15.9993 26.6665C10.1083 26.6665 5.33268 21.8909 5.33268 15.9998Z" fill="#71717A"></path>
                                </svg>
                            </label>
                        </div>
                        <div class="col" style="padding-bottom: 1rem;">
                            
                            <a class="text" onclick="updateProgress(@php echo $count_ @endphp)" style="{{ $active_task }}" href="{{ route('send_task') }}?flutterid={{$topic->id}}&task={{$task->id}}" id="requirement" >{{ $task->task_name }} </a>
                        </div>
                    </div>        
                    @endif
                </div>

            </div>
            @endforeach
        </ul>
    </div>
    <div class="form-group row">

        
    </div>
    <div style="padding: 20px; max-width: 68%; margin-left:5px;  ">
        <div style="border: 1px solid #ccc; padding: 20px 10px 10px 30px; border-radius: 5px;margin-bottom:40px">
            @php
                if($pdf_reader == 0):
                echo $html_start;
            @endphp
                    
                    
            @php
                else:
            @endphp
            
            <iframe src="{{ asset('flutter/docs/'. $html_start ) }}" style="width: 100%; height: 510px"></iframe></iframe>
            @php
                endif;
            @endphp

        </div>
    </div>

    @if($role == 'teacher')
    <div style="padding: 20px; max-width: 68%; margin-left:5px;  ">
        <div style="border: 1px solid #ccc; padding: 20px 10px 10px 30px; border-radius: 5px;margin-bottom:40px">
        <!-- <a href="{{ asset('/storage/private/febri syawaldi/febri syawaldi_db_conn.php') }}" download>Download File</a>
        <a href="{{public_path('storage/private/febri syawaldi/febri syawaldi_db_conn.php')}}" download>Click me</a> -->


            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Download File</th>
                        <!-- Add more table headers as needed -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listTask as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->flag }}</td>
                            <td><a href="{{ asset( $item->path ) }}" download="" class="btn btn-primary">Download Faile</a></td>
                            <!-- Add more table cells as needed -->
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
    @else
        
    @endif
    
    @if($flag == 1)

    <div style="padding: 20px; max-width: 68%; margin-left:5px;  ">
        <div style="border: 1px solid #ccc; padding: 20px 10px 10px 30px; border-radius: 5px;margin-bottom:40px">
            <div style="padding-top: 15px; padding-bottom: 15px">
                
                <div class="texts" style=" position: relative;">   
                    <style>    
                        text:hover{
                            text-decoration: none !important;
                        }
                    </style>
                    @if (!$flutterTestResults)
                    <form id="submitForm">
                        <p class='text-list' style='font-size: 24px; font-weight: 600;width: 400px !important;'> Upload URL Hasil Praktikum </p>
                        {{ csrf_field() }}
                        <label for="url">GitHub URL:</label>
                        <input class="input-url" placeholder="Your URL" type="text" id="url" name="url" required>
                        <button class="button" type="button" id="submitButton">Submit</button>
                    </form>
                    <hr>
                    <div id="spinner" class="loader" style="display: none;"></div>
                    @else
                    <h2>Detail Hasil Praktikum :</h2>
                    <h4 id="score">Score: {{ $flutterTestResults->score }}</h2>
                    <div id="success">
                        @php
                            $successTestsArray = json_decode($flutterTestResults->success_tests, true);
                            $failedTestsArray = json_decode($flutterTestResults->failed_tests, true);
                        @endphp

                        <h5>Success Tests:</h5>
                        @if(json_last_error() === JSON_ERROR_NONE && is_array($successTestsArray))
                            <ul style="max-height: 200px; overflow-y: auto;">
                                @foreach ($successTestsArray as $test)
                                    <li>{{ htmlspecialchars($test, ENT_QUOTES, 'UTF-8') }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p>Error decoding JSON for success tests</p>
                        @endif

                        <h5>Failed Tests:</h5>
                        @if(json_last_error() === JSON_ERROR_NONE && is_array($failedTestsArray))
                            <ul>
                                @foreach ($failedTestsArray as $test)
                                    <li>{{ htmlspecialchars($test, ENT_QUOTES, 'UTF-8') }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p>Error decoding JSON for failed tests</p>
                        @endif

                    </div>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
    @else
        
    @endif

    


    <!-- Footer -->
    <footer class="footer">
        © 2023 Your Website. All rights reserved.
    </footer>
    
    <script>
    $(document).ready(function() {

        // tolong ambilkan url saat ini dan ambil flutterid tanpa &start
        

        $('#submitButton').click(function(event) {
            event.preventDefault();

            const url = $('#url').val();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            const resultsDiv = $('.texts'); // Memastikan Anda memiliki div dengan kelas 'texts' di HTML Anda
            let resultData = {};
            // tolong ambilkan flutterid dari url http://127.0.0.1:8000/flutter/detail-topics?flutterid=8&start=44
            const urlParams = new URLSearchParams(window.location.search);
            const flutterid = String(urlParams.get('flutterid'));
            console.log(flutterid);
            
            $.ajax({
                url: "http://localhost:8080/submit",
                // url: "#",
                type: "POST",
                data: {
                    _token: csrfToken,
                    url: url,
                    flutterid: flutterid
                },
                beforeSend: function() {
                    // Mengganti tombol submit dengan spinner
                    $('#submitButton').hide(); // Sembunyikan tombol submit
                    $('#spinner').show(); // Tampilkan spinner
                },
                success: function(data) {
                    $('#spinner').hide(); 
                    console.log(data);

                    // Mengosongkan hasil sebelum menambahkan yang baru
                    resultsDiv.find('p').empty();
                    resultsDiv.find('ul').empty();

                    if (data.successTests) {
                        let successTests = '<h2>Success Tests (' + data.totalSuccess + '):</h2><ul style="max-height: 200px; overflow-y: auto;">';
                        data.successTests.forEach(test => {
                            successTests += '<li>' + test + '</li>';
                        });
                        successTests += '</ul>';
                        resultsDiv.append(successTests);
                    }

                    if (data.failedTests) {
                        let failedTests = '<h2>Failed Tests (' + data.totalFailed + '):</h2><ul>';
                        data.failedTests.forEach(test => {
                            failedTests += '<li>' + test + '</li>';
                        });
                        failedTests += '</ul>';
                        resultsDiv.append(failedTests);
                    }

                    if (data.score !== undefined) {
                        resultsDiv.append('<h2>Score: ' + data.score + '%</h2>');
                    }

                    resultData = {
                        successTests: data.successTests || [],
                        failedTests: data.failedTests || [],
                        score: data.score !== undefined ? data.score : null
                    };
                    console.log(resultData);
                    console.log(resultData.successTests);
                    console.log(resultData.failedTests);

                    // Mengirim resultData ke controller Laravel
                    $.ajax({
                        url: "{{ route('store_flutter_result_data') }}",
                            type: "POST",
                            data: {
                                _token: csrfToken,
                                success_tests: JSON.stringify(resultData.successTests),
                                failed_tests: JSON.stringify(resultData.failedTests), 
                                score: resultData.score,
                                flutterid: flutterid
                            },
                        success: function(response) {
                            console.log('Data berhasil dikirim ke server:', response);
                            // Lakukan sesuatu setelah data berhasil dikirim
                            window.location.reload(true);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error('Error:', textStatus, errorThrown);
                            $('#message').text('An error occurred while submitting the request.');
                        }
                    });

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error:', textStatus, errorThrown);
                    $('#message').text('An error occurred while submitting the request.');
                }
            });
        });
    });

    </script>
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
        function move() {
            
            var progressBar = document.getElementById("myProgressBar");
            var progressText = document.getElementById("progressText");
            var width = 0;
            var interval = setInterval(frame, progress);

            function frame() {
            if (width >= progress) {
                clearInterval(interval);
            } else {
                width++;
                progressBar.style.width = width + "%";
                progressText.innerHTML = width + "%";
            }
            }
        }
        move();
        function updateProgress(params) {
            // Get CSRF token from the meta tag
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });
            $.ajax({
                type: "POST",
                url: "{{ Route('session_progress') }}",
                data: {params: params},
                success: function(response) { 
                    $('#progressbar').css('width', params + '%');
                   
                }
            });
        }
        $('#progress').text("@php 
                                $width = session('params');
                                echo $width."%"; 
                            @endphp");
    </script>
</body>

</html>
