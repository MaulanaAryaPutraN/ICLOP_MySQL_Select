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
    <link rel="icon" href="./images/logo.png" type="image/png">
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
    </style>
</head>
<!-- This is body test -->

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-light bg-light" style="padding: 15px 20px; border-bottom: 1px solid #E4E4E7; font-family: 'Poppins', sans-serif;">
        <a class="navbar-brand" href="{{ route('learning_student') }}">
            <img src="images/left-arrow.png" style="height: 24px; margin-right: 10px;">
            A1:Java - Basic UI Java Edition - for Android Studio 3.x
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
                <img class="list-item-icon" src="images/down-arrow.png" style="height: 24px">
                <span class="list-item-title">Start to learn Android Programming with APLAS</span>
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
               <img class="list-item-icon" src="images/down-arrow.png" style="height: 24px">
                <span class="list-item-title">Task</span>
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

            <li class="list-item" onclick="toggleItem(this)">
               <img class="list-item-icon" src="images/down-arrow.png" style="height: 24px">
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

    <div style="padding-top: 36px; padding-left: 80px">
        <p class="text-list" style="font-size: 36px; font-weight: 600">
            Requirement
        </p>
        <p class="text">
            Student can start learning with preparing a PC to develop Android application.
            <ol>
                <li>A PC with minimum 4 GB RAM, 4 GB HDD, 1280 x 800 screen resolution, Microsoft Windows 7/8/10 (32 or 64 bit).</li>
                <li>Java SDK 1.8 minimum installed.</li>
                <li>Android Studio 3.5 installed.</li>
                <li>A web browser.</li>
                <li>A PDF reader software.</li>
                <li>Internet Connection.</li>
            </ol>
        </p>
    </div>

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
