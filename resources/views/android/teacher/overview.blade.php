@extends('android/teacher/home')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Android <i><b>2023</b></i></h3>
            </div>

            <div class="card-body">
                
                <div class="row" style="margin-bottom: 30px">
                    <div class="col-md-4" style="border-right: 2px solid #e0e0e0">
                        <b>Dosen Pengajar Oleh</b>
                        <h3>{{ Auth::user()->name }}</h3>
                    </div>
                    <div class="col-md-4">
                        <b>Total mahasiswa yang mengikuti kelas</b>
                        <h3>-</h3>
                    </div>
                    <!-- <div class="col-md-4">
                        <b>Pesan : <span class="text-danger">10 mahasiswa menunggu</span></b><br>
                        <a href="" class="btn btn-sm btn-default pulse">
                            <i class="far fa-bell"></i> Menuju Validasi Mahasiswa
                        </a>
                    </div> -->
                </div>




                <!-- Main content -->
                <div class="row" style="margin-top: 30px">
                    <div class="col-md-3">
                        
                        <h3>Task</h3>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Info</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ( $task AS $index => $isi )
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $isi->task_name }}<br><small>{{ $isi->caption }}</small></td>
                                    <td>
                                        @if ( $isi->tipe == "material" )
                                        <a href="{{ asset('android23/document/'. $topic->folder_path.'/'. $isi->material) }}" target="_blank" class="btn btn-default"><ion-icon name="newspaper-outline"></ion-icon></a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                    <div class="col-md-9">
                        <h3>Enroll</h3>
                        <table class="table text-sm" border="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama<br><small>Klik nama untuk mendetail</small></th>
                                    <th>Validation</th>
                                    <th>Skor</th>
                                    <th>Status</th>
                                    <th>Tanggal Mulai</th>
                                    <th width="25%">Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ( $enrollment AS $index => $isi )
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <a href="{{ url('teacher/android23/overview-student/'. $isi->android_topic_id.'/'. $isi->user_id) }}" class="text-bold">
                                            {{ $isi->user->name }}
                                        </a>
                                    </td>
                                    <td class="text-bold">{{ $isi->total_request.'/'.$isi->total_validate }}</td>
                                    <td>{{ number_format($isi->NA, 2) }}</td>
                                    <td>{{ $isi->status }}</td>
                                    <td>{{ $isi->created_at }}</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $isi->progress }}%; font-size: 12px" aria-valuenow="{{ $isi->progress }}" aria-valuemin="0" aria-valuemax="100">{{ number_format($isi->progress, 2) }}%</div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>

        </div>
    </div>
</div>

@endsection
