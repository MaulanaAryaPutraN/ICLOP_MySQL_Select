<!doctype html>
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
    <link rel="icon" href={{asset("./images/logo.png")}} type="image/png">
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg" style="background-color: #FEFEFE;">
        <div class="container-fluid">
            <!-- <a class="navbar-brand" href="#">Navbar</a> -->
            <img src={{asset("./images/logo.png")}} alt="logo" width="104" height="65">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="mx-auto">
                    <ul class="navbar-nav mb-2 mb-lg-0 justify-content-center">
                        <li class="nav-item">
                        @if($role == 'teacher')
                            <a class="nav-link active" aria-current="page" href="/dashboard-teacher">Dashboard Teacher</a>
                        @else
                            <a class="nav-link active" aria-current="page" href="/dashboard-teacher">Dashboard Student</a>
                        @endif
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Tutorials</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Contact Us</a>
                        </li>
                    </ul>
                </div>
                <div class="dropdown">
                    <p style="margin-top: 10px; margin-right: 10px;">{{auth()->user()->name}}
                    <img src="{{ asset('./images/Group.png') }}" alt="Group" style="height: 50px; margin-right: 10px;">
                    <i class="fas fa-chevron-down" style="color: #0079FF;"></i>
                    <div class="dropdown-content" id="dropdownContent">
                        <form id="logout-form" action="{{ route('logoutt') }}" method="POST">
                            @csrf
                            <a href="/" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        </form>

                    </div>
                </p>
                </div>
                <!-- <button class="btn btn-primary custom-button-sign-up" onclick="window.location.href='register.html'">Sign Up</button> -->
            </div>
        </div>
    </nav>
@auth

    <!-- CONTENT -->
    <div class="container" style="margin-top: 70px; justify-content: center; align-items: center;">
        <p style="font-size: 22px;">Choose your<br><span style="font-size: 35px; font-weight: 600; color: #34364A;">Learning Materials</span></p>

        <div class="row" style="margin-top: 45px;">
            <div class="card p-0" style="width: 305px; height: 375px; margin-left: 25px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
                <img src="{{asset("./images/cards/DB.png ")}}" class="card-img-top" style="width: auto; height: 200px;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Database with PHP Programming</h5>
                    <div class="row align-items-start">
                        <div class="col-1">
                            <img src="{{asset("./images/book.png ")}}" style="width: 13px; height: 16px;">
                        </div>
                        <div class="col">
                            <p>18 learning topics</p>
                        </div>
                    </div>
                    <div style="margin-top: auto;">
                        @if($role == 'teacher')
                            <a href="{{ route('welcome') }}" class="btn btn-primary">View Details</a>
                        @else
                            <a href="{{ route('welcome') }}" class="btn btn-primary">Start Learning</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card p-0" style="width: 305px; height: 375px; margin-left: 25px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
                <img src="{{asset("./images/cards/React.jpg")}}" class="card-img-top" style="width: auto; height: 200px;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Learn React JS</h5>
                    <div class="row align-items-start">
                        <div class="col-1">
                            <img src="{{asset("./images/book.png ")}}" style="width: 13px; height: 16px;">
                        </div>
                        <div class="col">
                            <p>6 learning topics</p>
                        </div>
                    </div>
                    <div style="margin-top: auto;">
                        @if($role == 'teacher')
                            <a href="{{ route('react_welcome') }}" class="btn btn-primary">View Details</a>
                        @else
                            <a href="{{ route('react_welcome') }}" class="btn btn-primary">Start Learning</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card p-0" style="width: 305px; height: 375px; margin-left: 25px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
                <img src="{{asset("./images/cards/DML.png")}}" class="card-img-top" style="width: auto; height: 200px;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Database Management System with MySQL</h5>
                    <div class="row align-items-start">
                        <div class="col-1">
                            <img src="{{asset("./images/book.png")}}" style="width: 13px; height: 16px;">
                        </div>
                        <div class="col">
                            <p>3 learning topics</p>
                        </div>
                    </div>
                    <div style="margin-top: auto;">
                        @if($role == 'teacher')
                            <a href="{{ route('mysql_teacher') }}" class="btn btn-primary">View Details</a>
                        @else
                            <a href="{{ route('mysql_welcome') }}" class="btn btn-primary">Start Learning</a>
                        @endif
                    </div>
                </div>
            </div>
        <!-- CARD 1 -->
        <!-- <div class="row" style="margin-top: 45px; display: flex; justify-content: center; align-items: center;"> -->
        <div class="row" style="margin-top: 45px;">
            <div class="card p-0" style="width: 305px; height: 375px; margin-left: 25px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
                <img src={{asset("./images/cards/Android.png")}} class="card-img-top" style="width: auto; height: 200px;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Android programming
                        with Java and Kotlin</h5>
                    <div class="row align-items-start">
                        <div class="col-1">
                            <img src={{asset("./images/book.png")}} style="width: 13px; height: 16px;">
                        </div>
                        <div class="col">
                            <p>18 learning topics</p>
                        </div>
                    </div>
                    <div style="margin-top: auto;">
                        <a href="/android23/topic" class="btn btn-primary">Start Learning</a>
                    </div>
                </div>
            </div>

            <div class="card p-0" style="width: 305px; height: 375px; margin-left: 25px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
                <img src={{asset("./images/cards/Flutter.png")}} class="card-img-top" style="width: auto; height: 200px;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Mobile programming with Flutter</h5>
                    <div class="row align-items-start">
                        <div class="col-1">
                            <img src={{asset("./images/book.png")}} style="width: 13px; height: 16px;">
                        </div>
                        <div class="col">
                            <p>18 learning topics</p>
                        </div>
                    </div>
                    <div style="margin-top: auto;">
                        <a href="/flutter/start" class="btn btn-primary">Start Learning</a>
                    </div>
                </div>
            </div>

            <div class="card p-0" style="width: 305px; height:375px; margin-left: 25px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
                <img src={{asset("./images/cards/Node.js.png")}} class="card-img-top" style="width: auto; height: 200px;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Web application with Node.JS</h5>
                    <div class="row align-items-start">
                        <div class="col-1">
                            <img src={{asset("./images/book.png ")}} style="width: 13px; height: 16px;">
                        </div>
                        <div class="col">
                            <p>18 learning topics</p>
                        </div>
                    </div>
                    <div style="margin-top: auto;">
                        <a href="/nodejs" class="btn btn-primary">Start Learning</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- ---------------------------------------------------------------------------------------------------- -->

        <!-- CARD 2 -->
        <!-- <div class="row" style="margin-top: 45px; display: flex; justify-content: center; align-items: center;"> -->
        <div class="row" style="margin-top: 45px;">
            <div class="card p-0" style="width: 305px; height: 375px; margin-left: 25px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
                <img src="{{asset("./images/cards/Python.png")}}" class="card-img-top" style="width: auto; height: 200px;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Python programming</h5>
                    <div class="row align-items-start">
                        <div class="col-1">
                            <img src="{{asset("./images/book.png ")}}" style="width: 13px; height: 16px;">
                        </div>
                        <div class="col">
                            <p>18 learning topics</p>
                        </div>
                    </div>
                    <div style="margin-top: auto;">
                        <a href="{{ route('learning_student') }}" class="btn btn-primary">Start Learning</a>
                    </div>
                </div>
            </div>

            <div class="card p-0" style="width: 305px; height: 375px; margin-left: 25px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
                <img src="{{asset("./images/cards/MySQL.png")}}" class="card-img-top" style="width: auto; height: 200px;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">SQL Querying with MySQL</h5>
                    <div class="row align-items-start">
                        <div class="col-1">
                            <img src="{{asset("./images/book.png ")}}" style="width: 13px; height: 16px;">
                        </div>
                        <div class="col">
                            <p>18 learning topics</p>
                        </div>
                    </div>
                    <div style="margin-top: auto;">
                        <a href="{{ route('learning_student') }}" class="btn btn-primary">Start Learning</a>
                    </div>
                </div>
            </div>

            <div class="card p-0" style="width: 305px; height: 375px; margin-left: 25px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
                <img src="{{asset("./images/cards/PostgreSQL.png")}}" class="card-img-top" style="width: auto; height: 200px;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">SQL Querying with PostgreSQL</h5>
                    <div class="row align-items-start">
                        <div class="col-1">
                            <img src="{{asset("./images/book.png ")}}" style="width: 13px; height: 16px;">
                        </div>
                        <div class="col">
                            <p>18 learning topics</p>
                        </div>
                    </div>
                    <div style="margin-top: auto;">
                        <a href="{{ route('learning_student') }}" class="btn btn-primary">Start Learning</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- ---------------------------------------------------------------------------------------------------- -->

        <!-- CARD 3 -->
        <!-- <div class="row" style="margin-top: 45px; display: flex; justify-content: center; align-items: center;"> -->
        <div class="row" style="margin-top: 45px;">
            <div class="card p-0" style="width: 305px; height: 375px; margin-left: 25px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
                <img src="{{asset("./images/cards/Network.png ")}}" class="card-img-top" style="width: auto; height: 200px;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Network programming with Java</h5>
                    <div class="row align-items-start">
                        <div class="col-1">
                            <img src="{{asset("./images/book.png ")}}" style="width: 13px; height: 16px;">
                        </div>
                        <div class="col">
                            <p>18 learning topics</p>
                        </div>
                    </div>
                    <div style="margin-top: auto;">
                        <a href="{{ route('learning_student') }}" class="btn btn-primary">Start Learning</a>
                    </div>
                </div>
            </div>

            <div class="card p-0" style="width: 305px; height: 375px; margin-left: 25px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
                <img src="{{asset("./images/cards/Unity.png")}}" class="card-img-top" style="width: auto; height: 200px;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Game programming with Unity</h5>
                    <div class="row align-items-start">
                        <div class="col-1">
                            <img src="{{asset("./images/book.png ")}}" style="width: 13px; height: 16px;">
                        </div>
                        <div class="col">
                            <p>18 learning topics</p>
                        </div>
                    </div>
                    <div style="margin-top: auto;">
                        <a href="{{ route('learning_student') }}" class="btn btn-primary">Start Learning</a>
                    </div>
                </div>
            </div>

            <div class="card p-0" style="width: 305px; height: 375px; margin-left: 25px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
                <img src="{{asset("./images/cards/DML.png")}}" class="card-img-top" style="width: auto; height: 200px;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Select Query with MySQL</h5>
                    <div class="row align-items-start">
                        <div class="col-1">
                            <img src="{{asset("./images/book.png")}}" style="width: 13px; height: 16px;">
                        </div>
                        <div class="col">
                            <p>5 learning topics</p>
                        </div>
                    </div>
                    <div style="margin-top: auto;">
                        @if($role == 'teacher')
                            <a href="{{ route('select_teacher') }}" class="btn btn-primary">View Details</a>
                        @else
                            <a href="{{ route('select_welcome') }}" class="btn btn-primary">Start Learning</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
            <!-- <div class="card p-0" style="width: 305px; height: 375px; margin-left: 25px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
                <img src="{{asset("./images/cards/DB.png ")}}" class="card-img-top" style="width: auto; height: 200px;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Database Programming with PHP</h5>
                    <div class="row align-items-start">
                        <div class="col-1">
                            <img src="{{asset("./images/book.png ")}}" style="width: 13px; height: 16px;">
                        </div>
                        <div class="col">
                            <p>18 learning topics</p>
                        </div>
                    </div>
                    <div style="margin-top: auto;">
                        <a href="{{ route('welcome') }}" class="btn btn-primary">Start Learning</a>
                    </div>
                </div>
            </div> -->
        </div>

    </div>

    <!-- FOOTER -->
    <div class="container text-align" style="margin-top: 100px; background-color: #FAFAFA; padding-right: 50px; padding-left: 50px; height: 300px;">
        <div class="row">
            <div class="col">
                <div class="container" style="margin-top: 40px;">
                    <img src="{{asset("./images/logo.png ")}}" alt="rocket" style="height: 60px;">
                    <p style="font-size: 16px;  color: #636363;">Intelligent Computer Assisted<br>Programming Learning Platform.</p>
                </div>
                <div class="container">
                    <i class="fab fa-instagram fa-lg" style="padding-right: 2px; color: #636363;"></i>
                    <i class="fab fa-github fa-lg" style="padding-right: 2px; color: #636363;"></i>
                    <i class="fab fa-linkedin fa-lg" style="padding-right: 2px; color: #636363;"></i>
                    <i class="fab fa-youtube fa-lg" style="padding-right: 2px; color: #636363;"></i>
                </div>
            </div>

            <div class="col">
                <div class="container">
                    <p style="font-size: 22px; font-weight: 600; color: #34364A; margin-top: 40px; margin-left: 55px;">Company</p>
                    <p style="font-size: 16px;  color: #636363; margin-left: 55px;">Privacy Policy</p>
                </div>
            </div>

            <div class="col">
                <div class="container">

                    <p style="font-size: 22px; font-weight: 600; color: #34364A; margin-top: 40px;">Contact Info</p>
                    <div class="row align-items-start">
                        <div class="col-1">
                            <i class="fas fa-map-marker-alt fa-lg" style="color: #636363; margin-top: 5px;"></i>
                        </div>
                        <div class="col">
                            <p style="font-size: 16px;  color: #636363;">Jl. Candi Mendut, RT.02/RW.08, Mojolangu, Kec. Lowokwaru, Kota Malang, Jawa Timur 65142</p>
                        </div>
                    </div>

                    <div class="row align-items-start">
                        <div class="col-1">
                            <i class="fas fa-envelope" style="color: #636363; margin-top: 5px;"></i>
                        </div>
                        <div class="col">
                            <p style="font-size: 16px;  color: #636363;">qulis＠polinema.ac.id (Email)</p>
                        </div>
                    </div>

                </div>
            </div>

            <div class="divider"></div>
            <p style="font-size: 16px; color: #636363; text-align: center; margin-top: 16px; margin-bottom: 16px;">© 2023 iCLOP. All rights reserved</p>
        </div>
    </div>
    </div>
    @endauth

</body>
    <script src="script.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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

</html>

