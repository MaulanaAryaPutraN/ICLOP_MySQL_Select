<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        /* Additional styles */
        .sidebar {
            background-color: #FFFFFF;
            width: 245px;
        }

        .content {
            min-height: 400px;
            background-color: #FFFFFF;
            padding: 20px;
        }

        .footer {
            background-color: #FAFAFA;
            padding: 10px;
            text-align: center;
        }

        /* NAV LINK */
        .nav-link {
            display: flex;
            align-items: center;
        }

        .nav-link:hover {
            color: blue !important;
        }

        .nav-link .icon {
            margin-right: 5px;
        }

        .custom-button {
            color: #A0A0A0;
            /* Warna teks saat tombol normal */
            transition: background-color 0.3s, color 0.3s;
            /* Efek transisi ketika hover */
            /* outline: none; */
        }

        .custom-button:hover {
            background-color: #007BFF;
            /* Warna latar belakang saat tombol dihover */
            color: white;
            /* Warna teks saat tombol dihover menjadi putih */
        }

        .custom-card {
            padding: 30px;
            width: 395px;
            height: 280px;
            background-color: #FFFFFF;
            border-radius: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .circle-image {
            width: 79px;
            height: 79px;
            border-radius: 50%;
        }

        .custom-title {
            font-weight: 600;
            font-size: 25px;
            color: #252525;
            font-family: 'Poppins', sans-serif;
            margin-top: 10px;
        }

        .custom-subtitle {
            font-weight: 400;
            font-size: 20px;
            color: #898989;
            font-family: 'Poppins', sans-serif;
            margin-top: 10px;
        }

        .custom-button {
            width: 335px;
            height: 43px;
            border-radius: 10px;
            background-color: #EAEAEA;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 15px;
            outline: none;
        }

        .custom-button-detail {
            width: 180px;
            height: 45px;
            border-radius: 10px;
            background-color: #EAEAEA;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 15px;
            margin-left: auto;
            color: #A0A0A0;
            /* Warna teks saat tombol normal */
            transition: background-color 0.3s, color 0.3s;
            /* Efek transisi ketika hover */
        }

        .custom-button-detail:hover {
            background-color: #007BFF;
            /* Warna latar belakang saat tombol dihover */
            color: white;
            /* Warna teks saat tombol dihover menjadi putih */
        }

        .button-text {
            font-weight: 500;
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            margin-left: 10px;
            margin-right: 10px;
            text-decoration: none;
            color: #A0A0A0;
        }

        .button-text:hover {
            text-decoration: none;
            color: #fff;
        }

        .text {
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
        }

        .sidebar-right-shadow {
            box-shadow: 1px 0px 8px rgba(0, 0, 0, 0.1);
            /* Menambahkan bayangan ke sisi kanan */
        }

        .footer {
            background-color: #EAEAEA;
            color: #636363;
            text-align: center;
            padding: 10px 0;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
    </style>

    <title>Tab Example</title>

    <!-- CSS Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- JavaScript Bootstrap -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="icon" href="./images/logo.png" type="image/png">
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg" style="background-color: #FEFEFE;">
        <div class="container-fluid">
            <!-- <a class="navbar-brand" href="#">Navbar</a> -->
            <img src={{ asset('./images/logo.png') }} alt="logo" width="104" height="65">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="mx-auto">
                    <ul class="navbar-nav mb-2 mb-lg-0 justify-content-center">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/dashboard-student">Dashboard
                                Student</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Learning</a>
                        </li>
                    </ul>
                </div>
                <div class="dropdown">
                    <p style="margin-top: 10px; margin-right: 10px;">{{ auth()->user()->name }}
                        <img src="{{ asset('./images/Group.png') }}" alt="Group"
                            style="height: 50px; margin-right: 10px;">
                        <i class="fas fa-chevron-down" style="color: #0079FF;"></i>
                    <div class="dropdown-content" id="dropdownContent">
                        <form id="logout-form" action="{{ route('logoutt') }}" method="POST">
                            @csrf
                            <a href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        </form>

                    </div>
                    </p>
                </div>
                <!-- <button class="btn btn-primary custom-button-sign-up" onclick="window.location.href='register.html'">Sign Up</button> -->
            </div>
        </div>
    </nav>
    <!-- ------------------------------------------------------------------------------------------ -->

    <div class="container-fluid">
        <div class="row">
            <!-- SIDEBAR -->
            <nav class="col-md-2 d-none d-md-block sidebar sidebar-right-shadow">
                <div class="sidebar-sticky" style="margin-top: 20px;">
                    <ul class="nav flex-column">
                        <li class="nav-item" style="margin-bottom: 40px;">
                            <div class="row align-items-start">
                                <div class="col">
                                    <p style="font-weight: 600; font-size: 14px; color: #34364A; margin-left: 15px;">
                                        STUDENT WEBAPPS</p>
                                </div>
                                <div class="col">
                                    <img src="{{ asset('./images/php/php.png') }}" alt="learning-logo"
                                        style="height: 45px;">
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="row align-items-start">
                                <div class="col-2">
                                    <i class="fas fa-book" style="margin-top: 12px; margin-left: 15px; color: #676767;"
                                        id="learningIcon"></i>
                                </div>
                                <div class="col">
                                    <a class="nav-link active" href="#" onclick="showContent('start-learning')"
                                        style="color: #34364A;" id="learningLink">Start Learning</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="row align-items-start">
                                <div class="col-2">
                                    <i class="fas fa-check-circle"
                                        style="margin-top: 12px; margin-left: 15px; color: #676767;"
                                        id="validationIcon"></i>
                                </div>
                                <div class="col">
                                    <a class="nav-link" href="#" onclick="showContent('validation')"
                                        style="color: #34364A;" id="validationLink">Validation Result</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="row align-items-start">
                                <div class="col-2">
                                    <i class="fas fa-trophy"
                                        style="margin-top: 12px; margin-left: 15px; color: #676767;"
                                        id="rankIcon"></i>
                                </div>
                                <div class="col">
                                    <a class="nav-link" href="#" onclick="showContent('rank')"
                                        style="color: #34364A;" id="rankLink">Top 20 Rank</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="row align-items-start">
                                <div class="col-2">
                                    <i class="fas fa-cog" style="margin-top: 12px; margin-left: 15px; color: #676767;"
                                        id="settingsIcon"></i>
                                </div>
                                <div class="col">
                                    <a class="nav-link" href="#" onclick="showContent('settings')"
                                        style="color: #34364A;" id="settingsLink">Settings</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- ------------------------------------------------------------------------------------------ -->

            <!-- CONTENT -->
            <main class="col-md-9">
                <div class="content" id="start-learning">
                    <p style="font-size: 24px; font-weight: 500; color: #34364A;">Start Learning</p>
                    <div>
                        <div class="container mt-4">
                            <!-- NAV TAB -->
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" id="learning-tab" data-toggle="tab"
                                        href="#learning">Learning Topic</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="finished-tab" data-toggle="tab" href="#finished">Topic
                                        Finished</a>
                                </li>
                            </ul>

                            <!-- TAB CONTENT -->
                            <div class="tab-content mt-3">
                                <div class="tab-pane fade show active" id="learning">
                                    @foreach ($topics as $topic)
                                        @php
                                            $limit_id = $topic->id;
                                            $row = DB::table('flutter_topics_detail')
                                                ->where('id_topics', $limit_id)
                                                ->first();
                                            $rowArray = (array) $row;
                                            if ($row) {
                                                $rows = $row->id;
                                            }
                                        @endphp
                                        <div class="row">
                                            <div class="col">
                                                <div class="p-3">{{ $topic->title }}</div>

                                            </div>
                                            <div class="col" style="text-align: right;">
                                                <div class="custom-button-detail">
                                                    <a class="button-text" data-toggle="modal"
                                                        data-target="#exampleModal"
                                                        onclick="materialModal('{{ $topic->id }}','{{ $topic->title }}','{{ $rows }}')">
                                                        <i class="fas fa-key" style="margin-right: 5px;"></i>
                                                        <!-- Ikon kunci -->
                                                        Material Details
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="tab-pane fade" id="finished">
                                    <h3>Topic Finished</h3>
                                    <p>This is the content for the Topic Finished.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="validation" class="content" style="display: none;">
                    <h1>Validation Result</h1>
                    <p>This is the products content.</p>
                </div>

                <div id="rank" class="content" style="display: none;">
                    <h1>Top 20 Rank</h1>
                    <p>This is the orders content.</p>
                </div>

                <div id="settings" class="content" style="display: none;">
                    <h1>Settings</h1>
                    <p>Possible account settings
                        needed<br>during the learning process</p>

                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <div class="custom-card">
                                    <img src="./images/profile.png" alt="Image 1" class="circle-image">
                                    <h2 class="custom-title">My Profile</h2>
                                    <p class="custom-subtitle">Ubah data diri kamu</p>
                                    {{-- <button type="button" class="btn btn-primary custom-button"><p class="button-text">Edit Now</p></button> --}}
                                    <div class="custom-button">
                                        <p class="button-text">Edit Now</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-card">
                                    <img src="./images/my-password.png" alt="Image 2" class="circle-image">
                                    <h2 class="custom-title">My Password</h2>
                                    <p class="custom-subtitle">Ganti kata sandimu</p>
                                    <div class="custom-button">
                                        <p class="button-text">Change Now</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>


        </div>
    </div>
    <!-- The Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width: 80%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><span id="span_title"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="text-center">
                                <img src={{ asset('./images/online_virtual_machine.png') }} alt="logo"
                                    width="400" height="300">
                            </div>
                            <h5>Materi Flutter Framework</h5>
                            <input type="hidden" id="id" />
                            <input type="hidden" id="title" />
                            <input type="hidden" id="controller" />
                            <span class="text-sm">Memiliki {{ $topicsCount }} materi yang akan dibahas secara
                                detail</span>
                        </div>

                        <div class="col-md-7">
                            <b>Prerequisite knowledge : </b>
                            <div class="text-sm" style="margin-bottom: 20px">
                                <p style="margin-bottom: 5px !important">Sebelum memulai pembelajaran PHP, Anda harus
                                    memiliki pengetahuan dasar tentang <b>Dart Programming Language</b>.</p>
                                1. Memahami dasar-dasar bahasa Dart, seperti deklarasi variabel,
                                tipe data, operator, kontrol aliran, dan fungsi.<br>
                                2. Memahami bagaimana mengimplementasikan konsep OOP dalam bahasa
                                Dart.<br />
                                3. Memahami konsep asynchronous dalam Dart seperti Future,
                                async, await, dan Stream.
                            </div>

                            <b>Requirement : </b>
                            <div class="text-sm mb-10" style="margin-bottom: 20px">
                                1. Prosesor Intel Core i3 atau Setara.<br>
                                2. Setidaknya memiliki RAM 8 GB atau lebih<br>
                                3. Hardisk 120 GB HDD dengan penyimpanan tersedia minimal 10 GB<br>
                                4. Koneksi Ethernet and Wi-Fi capabilities<br>
                                5. OS minimal Windows 7 SP1 keatas disarankan Windows 10 64-bit
                            </div>

                            <b>Tools : </b><br>
                            <div class="row">
                                <div class="col-md-6 text-center text-sm">
                                    <a href="https://code.visualstudio.com/" target="_blank">
                                        <img style="width: 90px; height: 80px;"
                                            src="{{ asset('./images/flutter/vscode.png') }}" alt="">
                                    </a>
                                    <br>
                                    VISUAL STUDIO CODE
                                </div>
                                <div class="col-md-6 text-center text-sm">
                                    <a href="https://developer.android.com/" target="_blank">
                                        <img style="width: 90px; height: 80px; object-fit: cover"
                                            src="{{ asset('./images/flutter/androidstudio.png') }}" alt="">
                                    </a>
                                    <br>
                                    ANDROID STUDIO
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" style="margin-left: 10px; width: 160px;"
                        onclick="materialDetailPage()">
                        <i class="fas fa-key" style="margin-right: 5px;"></i>Enroll Material
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- JavaScript untuk mengubah konten tab -->
    <script>
        function materialModal(id, title, controller) {
            $("#id").val(id);
            $("#title").val(title);
            $("#controller").val(controller);
            $("#span_title").text(title);
        }

        function materialDetailPage() {
            var csrfToken = "{{ csrf_token() }}";
            let id = $("#id").val();
            let title = $("#title").val();
            let controller = $("#controller").val();
            window.location.href = "{{ route('flutter_material_detail') }}?flutterid=" + id + "&start=" + controller;

            /*$.ajax({
                type: "POST",
                data: {
                    id: id,
                    title: title,
                    _token: csrfToken // Menambahkan token CSRF ke dalam data permintaan
                },
                dataType: 'html',
                url: "{{ route('php_material_detail') }}",
                success: function(res) {
                    
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                }
            });*/
        }

        // Fungsi untuk mengubah warna ikon, teks, dan link menjadi biru
        function changeColor(id) {
            var icon = document.getElementById(id + 'Icon');
            var link = document.getElementById(id + 'Link');
            var text = document.getElementById(id + 'Text');

            // Mengembalikan warna ikon, teks, dan link ke warna awal
            var icons = document.getElementsByClassName('fas');
            var links = document.getElementsByClassName('nav-link');
            var texts = document.getElementsByClassName('nav-link-text');
            for (var i = 0; i < icons.length; i++) {
                icons[i].style.color = '#676767';
            }
            for (var j = 0; j < links.length; j++) {
                links[j].style.color = '#34364A';
            }
            for (var k = 0; k < texts.length; k++) {
                texts[k].style.color = '#34364A';
            }

            // Mengubah warna ikon, teks, dan link menjadi biru
            icon.style.color = '#1A79E3';
            link.style.color = '#1A79E3';
            text.style.color = '#1A79E3';
        }

        // Menambahkan event listener pada setiap link
        var startLearningLink = document.getElementById('learningLink');
        startLearningLink.addEventListener('click', function() {
            changeColor('learning');
        });

        var validationLink = document.getElementById('validationLink');
        validationLink.addEventListener('click', function() {
            changeColor('validation');
        });

        var rankLink = document.getElementById('rankLink');
        rankLink.addEventListener('click', function() {
            changeColor('rank');
        });

        var settingsLink = document.getElementById('settingsLink');
        settingsLink.addEventListener('click', function() {
            changeColor('settings');
        });


        // Function to show the selected content based on sidebar link click
        function showContent(contentId) {
            // Hide all content divs
            var contentDivs = document.getElementsByClassName('content');
            for (var i = 0; i < contentDivs.length; i++) {
                contentDivs[i].style.display = 'none';
            }

            // Show the selected content div
            var selectedContent = document.getElementById(contentId);
            if (selectedContent) {
                selectedContent.style.display = 'block';
            }
        }

        //  Change TAB
        $(document).ready(function() {
            $('#learning-tab').on('click', function(e) {
                e.preventDefault();
                $('#finished-tab').removeClass('active');
                $(this).tab('show');
            });

            $('#finished-tab').on('click', function(e) {
                e.preventDefault();
                $('#learning-tab').removeClass('active');
                $(this).tab('show');
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $("#dropdownContainer").click(function() {
                $("#dropdownContainer").toggleClass("active");
            });
            $("#dropdownContent").click(function(e) {
                e.stopPropagation();
            });
            $(document).click(function() {
                $("#dropdownContainer").removeClass("active");
            });
        });
    </script>


    <style>
        .dropdown {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #fff;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 5px;
            overflow: hidden;
            transition: 0.3s;
            opacity: 0;
            transform: translateY(-10px);
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: 0.3s;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        .dropdown.active .dropdown-content {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
    </style>
    <footer class="footer">
        © 2023 Your Website. All rights reserved.
    </footer>
</body>


</html>
