@extends('student/home')
@section('content')


<style>
    .custom-control.overflow-checkbox .overflow-control-input {
        display: none;
    }

    .custom-control.overflow-checkbox .overflow-control-input:checked~.overflow-control-indicator::after {
        -webkit-transform: rotateZ(45deg) scale(1);
        -ms-transform: rotate(45deg) scale(1);
        transform: rotateZ(45deg) scale(1);
        top: -6px;
        left: 5px;
    }

    .custom-control.overflow-checkbox .overflow-control-input:checked~.overflow-control-indicator::before {
        opacity: 1;
    }

    .custom-control.overflow-checkbox .overflow-control-input:disabled~.overflow-control-indicator {
        opacity: .5;
        border: 2px solid #ccc;
    }

    .custom-control.overflow-checkbox .overflow-control-input:disabled~.overflow-control-indicator:after {
        border-bottom: 4px solid #ccc;
        border-right: 4px solid #ccc;
    }

    .custom-control.overflow-checkbox .overflow-control-indicator {
        border-radius: 3px;
        display: inline-block;
        position: absolute;
        top: 4px;
        left: 0;
        width: 16px;
        height: 16px;
        border: 2px solid #00909e;
    }

    .custom-control.overflow-checkbox .overflow-control-indicator::after {
        content: '';
        display: block;
        position: absolute;
        width: 16px;
        height: 16px;
        -webkit-transition: .3s;
        -o-transition: .3s;
        transition: .3s;
        -webkit-transform: rotateZ(90deg) scale(0);
        -ms-transform: rotate(90deg) scale(0);
        transform: rotateZ(90deg) scale(0);
        width: 10px;
        border-bottom: 4px solid #00909e;
        border-right: 4px solid #00909e;
        border-radius: 3px;
        top: -2px;
        left: 2px;
    }

    .custom-control.overflow-checkbox .overflow-control-indicator::before {
        content: '';
        display: block;
        position: absolute;
        width: 16px;
        height: 16px;
        -webkit-transition: .3s;
        -o-transition: .3s;
        transition: .3s;
        width: 10px;
        border-right: 7px solid #fff;
        border-radius: 3px;
        -webkit-transform: rotateZ(45deg) scale(1);
        -ms-transform: rotate(45deg) scale(1);
        transform: rotateZ(45deg) scale(1);
        top: -4px;
        left: 5px;
        opacity: 0;
    }


    .cc-selector input{
        margin:0;padding:0;
        -webkit-appearance:none;
        -moz-appearance:none;
                appearance:none;
    }

    .cc-selector-2 input{
        position:absolute;
        z-index:999;
    }

    .github{background-image:url(https://cdn-icons-png.flaticon.com/512/25/25231.png);}
    .zip{background-image:url(https://cdn-icons-png.flaticon.com/128/5721/5721939.png);}

    .cc-selector-2 input:active +.drinkcard-cc, .cc-selector input:active +.drinkcard-cc{opacity: .9;}
    .cc-selector-2 input:checked +.drinkcard-cc, .cc-selector input:checked +.drinkcard-cc{
        -webkit-filter: none;
        -moz-filter: none;
                filter: none;
    }
    .drinkcard-cc{
        cursor:pointer;
        background-size:contain;
        background-repeat:no-repeat;
        display:inline-block;
        width:100px;height:70px;
        -webkit-transition: all 100ms ease-in;
        -moz-transition: all 100ms ease-in;
                transition: all 100ms ease-in;
        -webkit-filter: brightness(1.8) grayscale(1) opacity(.7);
        -moz-filter: brightness(1.8) grayscale(1) opacity(.7);
                filter: brightness(1.8) grayscale(1) opacity(.7);
    }
    .drinkcard-cc:hover{
        -webkit-filter: brightness(1.2) grayscale(.5) opacity(.9);
        -moz-filter: brightness(1.2) grayscale(.5) opacity(.9);
                filter: brightness(1.2) grayscale(.5) opacity(.9);
    }

    /* Extras */
    .event:visited{color:#888}
    .event{color:#444;text-decoration:none;}
    .cc-selector-2 input{ margin: 5px 0 0 12px; }
    .cc-selector-2 label{ margin-left: 7px; }
    span.cc{ color:#6d84b4 }
</style>


<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ $topic->title }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard v1</li>
                </ol>
            </div>
        </div>
    </div>
</div>


<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-3">
                <div class="" style="height:600px;overflow-y: scroll;">
                    <div class="list-group">


                        @foreach ( $all_task AS $isi )
                        <a href="{{ url('/android23/task/'. $topic_id.'?id='. $isi->id) }}" class="list-group-item list-group-item-action">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="align-middle text-center text-success" style="margin-top: 10px">
                                        
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

                    </div>                      
                </div>
            </div>


            <div class="col-md-9">

                <div class="card card-body">
                    <h3>Submission Overview</h3>
                    <p>Daftar materi yang telah dikerjakan</p>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Task</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Project Configuration</td>
                                <td>{{ date('d M Y H.i') }}</td>
                                <td>
                                    <label class="text-danger"><i>Waiting</i></label>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div style="background-color: #f5f5f5; padding: 7px">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <small>Grade</small>
                                <h1>A</h1>
                            </div>
                            <div class="col-md-9">
                                <small><i class="fas fa-key text-muted"></i>&emsp;Enrolled : <b>{{ date('d F Y H.i A') }}</b></small><br>
                                <small><i class="fas fa-puzzle-piece text-muted"></i>&emsp;Total Task : <b>10</b></small><br>
                                <small><i class="fas fa-paste text-muted"></i>&emsp;Score : <b>10 point</b></small>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</section>


@endsection