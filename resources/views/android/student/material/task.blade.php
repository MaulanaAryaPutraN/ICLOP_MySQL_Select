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
        <a class="navbar-brand" href="{{ url('android23/material') }}">
            <img src="{{ asset('images/left-arrow.png') }}" style="height: 24px; margin-right: 10px;">
            {{ $topic->title }}
        </a>
    </nav>


    <div class="container-fluid">
    <div class="row">
            
            <div class="col-md-9">
                {{-- Apabila yang dilampirkan hanya materi --}}
                @if ( empty( $request->type ) )
                    @if ( $task->tipe == "material" )
                    <div class="row">
                        <div class="col-md-9">
                            <h3>{{ $task->task_name }}</h3>
                            <p>{{ $task->caption }}</p>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ url($urlku) }}" class="btn btn-primary btn-block">Lanjutkan Materi &emsp;<i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>

                    <div class="card card-body">
                        <object
                                data="{{ asset('android23/document/'. $topic->folder_path.'/'. $task->material) }}"
                                type="application/pdf"
                                style="width: 100%; height: 400px"
                            >
                            @php
                                echo "{{ asset('android23/document/'. $topic->folder_path.'/'. $task->material) }}";
                            @endphp
                                <iframe
                                src="{{ asset('android23/document/'. $topic->folder_path.'/'. $task->material) }}"
                                style="width: 100%; height: 400px"
                                >
                                <p>This browser does not support PDF!</p>
                                </iframe>
                            </object>
                    </div>

                    @elseif ( $task->tipe == "submission" )

                    <div class="row">
                        <div class="col-md-9">
                            <h3>{{ $task->task_name }}</h3>
                            <p>{{ $task->caption }}</p>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ url($urlku) }}" class="btn btn-primary btn-block">Lanjutkan Materi &emsp;<i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
                    <div class="card card-body">
                        
                        <div class="row">
                            <div class="col-md-4">

                                @if ($submit_information)

                                    <img src="{{ asset('android23/submission/'. $submit_information->upload) }}" alt="" srcset="" style="width: 100%; border-radius: 10px" alt="img">
                                @else 
                                    <img src="https://cdn.dribbble.com/users/34020/screenshots/3993396/otp_icon_upload.gif" alt="" srcset="" style="width: 100%">
                                @endif 
                                <h4>Penilaian Task</h4>
                                <small>Isi form pada area disamping untuk mengumpulkan tugas <b>{{ $task->task_name }}</b></small>
                            </div>
                            <div class="col-md-8">
                                <form action="{{ route('submission') }}" method="POST" enctype="multipart/form-data">

                                    <input type="hidden" name="android_topic_id" value="{{ $topic_id }}">
                                    <input type="hidden" name="android_task_id" value="{{ $id }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6" style="border-right: 1px solid #e0e0e0">
                                            <?php

                                            // echo json_encode($task);

                                            // echo json_encode($submit_testcase);
                                            // $testcase = explode(",", $task->testcase);
                                            foreach ( $testcase AS $index => $isi ) :

                                            // checked status
                                            $checked = "";
                                            if ( $submit_information ) {

                                                foreach ( $submit_testcase AS $isi_st ) {

                                                    if ( $isi_st->android_testcase_id == $isi->id ){

                                                        if ( $isi_st->status == "passed" ){

                                                            $checked = 'checked=""';
                                                        }
                                                        break;
                                                    }

                                                    // echo $isi_st;
                                                }
                                            }
                                            
                                            ?>

                                            <label class="custom-control overflow-checkbox">
                                                <input type="checkbox" name="task[]" class="overflow-control-input" value="{{ $isi->id }}" {{ $checked }}>
                                                <span class="overflow-control-indicator"></span>
                                                <span class="overflow-control-description">{{ $isi->case }}</span>
                                            </label>

                                            <?php endforeach; ?>
                                        </div>
                                        <div class="col-md-6">
                                            @if ( $submit_information )  
                                            
                                            <div class="form-group">
                                                <label for="">Durasi (Menit)</label><br>
                                                <small>Waktu durasi pengerjaan</small>
                                                <h4 style="margin: 0px">{{ $submit_information->duration }} menit</h4>
                                            </div>


                                            <div class="form-group">
                                                <small>Komentar</small>
                                                <h5 style="margin: 0px">{{ $submit_information->comment }}</h5>
                                            </div>

                                                                                       
                                            @else 

                                            
                                            <div class="form-group">
                                                <label for="">Evidence</label>
                                                <input type="file" name="userfile" required=""/><br>
                                                <small>Masukkan screenshot pengerjaan <code>.jpg | .png | .jpeg</code></small>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Comment</label>
                                                <textarea class="form-control" name="comment" placeholder="..."></textarea>
                                                <small>Masukkan durasi pengerjaan</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Duration</label>
                                                <input type="number" name="duration" class="form-control" placeholder=". . ." required="" />
                                                <small>Masukkan durasi pengerjaan</small>
                                            </div>
                                            <div class="form-group">
                                                <button class="btn btn-block btn-sm btn-success">Simpan Penilaian</button>
                                            </div>

                                            @endif


                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>

                    </div>
                    @endif
                @elseif ( $request->type == "final" )


                    @if ( $submit_submission->count() == 0 )
                        @if ( $errors->any() )
                        <div class="alert alert-danger">
                            Pemberitahuan
                            <ul class="text-sm">
                                @foreach ( $errors->all() AS $isi )
                                <li>{{ $isi }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="card card-body">

                            <div class="row">
                                <div class="col-md-4">
                                    <h4 style="margin: 0px">Jenis Upload File</h4>
                                    <small>Pilih jenis file yang akan diunggah</small>

                                    <hr>

                                    <div class="row" style="margin-bottom: 30px">
                                        <div class="col-md-4">
                                            <div class="cc-selector-2">
                                                <input class="radio-button-picker" checked="checked" id="github" type="radio" name="tipe-upload" value="github" />
                                                <label class="drinkcard-cc github" for="github"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-8" style="margin-top: 5px">
                                            <small>Pilih repository</small>
                                            <h2>Github</h2>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="cc-selector-2">
                                                <input class="radio-button-picker" id="zip" type="radio" name="tipe-upload" value="zip" />
                                                <label class="drinkcard-cc zip"for="zip"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-8" style="margin-top: 5px">
                                            <small>Pilih file dokumen</small>
                                            <h2>Zip</h2>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">

                                    <div class="row align-items-center h-100">
                                        <div id="upload-github" class="">

                                            <form action="{{ route('final-submission', $topic_id) }}" method="post">


                                            <input type="hidden" name="task_id" value="{{ $request->id }}">

                                            @csrf 
                                            <input type="hidden" name="type" value="github" />
                                            <h3>Pengumpulan Tugas - Github</h3>

                                            <div class="form-group">
                                                <label for="">Link Repository</label>
                                                <input type="text" name="link" class="form-control" placeholder="Masukkan link repository proyek . . ." />
                                                <small>Berisi link repository</small>
                                            </div>

                                            <div class="form-group text-right">
                                                <button class="btn btn-sm btn-primary">Kirim Submission</button>
                                            </div>
                                            </form>



                                            <div style="background: #f5f5f5; padding: 16px; border-radius: 5px; font-size: 10px">
                                                <b>Pemberitahuan</b><br>
                                                Apabila tidak memiliki akun github, anda dapat mengunjungi website <a href="https://github.com/">https://github.com/</a> <br>untuk 
                                                melakukan pendaftaran dan melakukan upload proyek.
                                            </div>
                                        </div>


                                        <div id="upload-zip" class="" style="display: none">

                                            <form action="{{ route('final-submission', $topic_id) }}" method="post" enctype="multipart/form-data">


                                            <input type="hidden" name="task_id" value="{{ $request->id }}">


                                            @csrf 
                                            <input type="hidden" name="type" value="zip" />

                                            <h3>Pengumpulan Tugas - Zip</h3>

                                            <div class="form-group">
                                                <label for="">File</label><br>
                                                <input type="file" name="userfile" /><br>
                                                <small>Berisi file dengan ekstensi .zip</small>
                                            </div>

                                            <div class="form-group text-right">
                                                <button class="btn btn-sm btn-primary">Kirim Submission</button>
                                            </div>
                                            </form>



                                            <div style="background: #f5f5f5; padding: 16px; border-radius: 5px; font-size: 10px">
                                                <b>Pemberitahuan</b><br>
                                                Apabila tidak memiliki akun github, anda dapat mengunjungi website <a href="https://github.com/">https://github.com/</a> <br>untuk 
                                                melakukan pendaftaran dan melakukan upload proyek.
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                        </div>

                    @else

                        <!-- Overview upload -->
                        <div class="card card-body">
                        <h3>Submission Overview</h3>
                        <p>Daftar materi yang telah dikerjakan</p>

                        <table class="table table-hover table-bordered" border="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Task</th>
                                    <th>Test Case</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ( $submits AS $index => $isi )
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ $isi['info']->task_name }}<br>
                                        <small>Pengumpulan pada <b>{{ date('d M Y H.i', strtotime($isi['info']->created_at)) }}</b></small>

                                        <hr>
                                        <small><b>Persentage</b></small>
                                        <h4>{{ number_format($isi['persentage'], 2) }}</h4>
                                    </td>
                                    <td>
                                        @foreach ( $isi['testcase'] AS $index_tc => $tc )
                                        <div class="row text-sm">
                                            <div class="col-md-6">{{ explode(':', $tc->case)[0] }}</div>
                                            <div class="col-md-3">
                                                @php 

                                                    $color = "";
                                                    $icon = "";
                                                    if ( $tc->status_validate == "failed" ) {

                                                        $color = "danger";
                                                        $icon = '<i class="fas fa-times"></i>';
                                                    } else if ( $tc->status_validate == "passed" ) {

                                                        $color = "success";
                                                        $icon = '<i class="fas fa-check"></i>';
                                                    }   
                                                
                                                @endphp 

                                                <b class="text-{{ $color }}">@php echo $icon.' '.$tc->status_validate.' : '.$tc->task->task_name  @endphp</b>
                                            </div>
                                            <div class="col-md-3"></div>
                                        </div>
                                        @endforeach

                                        <div style="border-top: 1px solid #e0e0e0">
                                            <small>Estimasi waktu pengerjaan {{ $isi['info']->duration }} menit</small>
                                        </div>
                                    </td>
                                </tr>                                


                                @endforeach
                            </tbody>
                        </table>

                        <div style="background-color: #f5f5f5; padding: 7px">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <small>Autograding</small>
                                    <h1>{{ number_format($NA, 2) }}</h1>
                                </div>
                                <div class="col-md-9">
                                    <small><i class="fas fa-key text-muted"></i>&emsp;Enrolled : <b>{{ date('d F Y H.i', strtotime($enrollment->created_at)) }}</b></small><br>
                                    <small><i class="fas fa-clock text-muted"></i>&emsp;Total Estimate : <b>{{ $estimate }} m</b></small>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endif

                @endif
            </div>



            <div class="col-md-3">
                <div class="" style="height:600px;overflow-y: scroll;">
                    <div class="list-group">

                        @foreach ( $all_task AS $isi )
                        <a href="{{ url('/android23/task/'. $topic_id.'?id='. $isi->id) }}" class="list-group-item list-group-item-action">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="align-middle text-center text-success" style="margin-top: 10px">
                                        @if ( $isi->status_akses )
                                            <i class="fa fa-check"></i>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div><b class="">{{ $isi->task_name }}</b></div>
                                    <small>{{ $isi->caption }}</small>
                                </div>
                            </div>
                        </a>
                        @endforeach 

                        {{-- Submission --}}
                        <a href="{{ url('android23/task/'. $topic_id.'?id='.$isi->id.'&type=final' ) }}" class="list-group-item list-group-item-action">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="align-middle text-center text-success" style="margin-top: 10px">
                                        
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div><b class="">Final Project</b></div>
                                    <small>Mengumpulkan submission akhir</small>
                                </div>
                            </div>
                        </a>
                        
                        @for ( $i = 0; $i < 15; $i++ )
                        {{-- <a href="#" class="list-group-item list-group-item-action">
                            list-group-item-secondary
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="align-middle text-center text-success" style="margin-top: 10px">
                                        
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div><b class="">Komponen Text View</b></div>
                                    <small>Komponen Teks Android</small>
                                </div>
                            </div>
                        </a> --}}
                        @endfor


                    </div>                      
                </div>
            </div>
    </div>
    </div>


    
    
</body>

</html>
