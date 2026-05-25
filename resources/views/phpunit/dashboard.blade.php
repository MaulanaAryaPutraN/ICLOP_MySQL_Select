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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <title>iCLOP</title>
    <style>
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
            position: absolute;
            margin-top: 30px;
            width: 100%;
        }

        /* CSS for sidebar styling */
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

        /* Dropdown style */
        .dropdown {
            padding: 6px 8px;
            display: inline-block;
            cursor: pointer;
        }

        /* Dropdown content style */
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
    </style>
</head>
<!-- This is body test -->

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-light bg-light" style="padding: 15px 20px; border-bottom: 1px solid #E4E4E7; font-family: 'Poppins', sans-serif;">
        <a class="navbar-brand" href="">
            <img src="{!! url("images/left-arrow.png"); !!}" style="height: 24px; margin-right: 10px;">
            <b>Starting Programming With PHP</b>
        </a>
    </nav>

     <!-- Sidebar -->
         @include('phpunit/navigation');
     <!-- /Sidebar -->
     

    <div style="padding-top: 0px; padding-left: 30px; margin-bottom:30px">
        <p class='text-list' style='font-size: 24px; font-weight: 600;width: 800px !important;'> </p>
        <div class="text" style="width: 800px !important;">
           <!-- {!! html_entity_decode($content) !!} -->
           <!-- <embed src="http://localhost/materi/embed.php" height="70px" /> -->
           <embed src="{{ asset('assets/php/GuideA1.pdf') }}" type="application/pdf" width="100%" height="500" />

        </div>
       
    </div>
    @if ($form_upload == 'Y')

    <div style="padding-top: 36px; padding-left: 80px">
        <p class='text-list' style='font-size: 24px; font-weight: 600;width: 400px !important;'> Upload Practicum File </p>
        <div class="text" style="width: 800px !important; position: relative;">
            @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
             @endif    
                
            <form action="{!! url("/phpunit/studi-kasus/upload_jawaban"); !!}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group" style="width: 80%;margin-bottom:15px">
                    <input type="file" name="file" class="form-control">
                </div>
                <input type="submit" value="Upload" class="btn btn-primary" style="position: absolute; bottom:-20px">
            </form>
            <br>
            <a type="submit" style="position: absolute; bottom:-20px; left:100px" class="btn btn-primary" target="_blank" href="{!! url("/phpunit/studi-kasus/akhir-ujian"); !!}">PHP Unit Testing Result</a>
        </div>
    </div>
    @endif   
     <!-- Footer -->
     <footer class="footer">
        © 2023 Your Website. All rights reserved.
    </footer>

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
    </script>
</body>

</html>
