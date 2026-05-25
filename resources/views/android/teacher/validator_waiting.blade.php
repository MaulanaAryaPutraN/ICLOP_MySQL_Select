@extends('android/teacher/home')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="">
            <div class="card-header">
                <h3 class="card-title">Android <i><b>2023</b></i> / Permintaan Validasi</h3>
            </div>

            <div class="card-body">
                

                <!-- Table -->
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div id="accordion">

                            @foreach ( $dt_need_validator AS $i => $isi ) 
                            <div class="card pulse">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        <a class="d-block w-100" data-toggle="collapse" href="#collapse-{{ $i }}" style="color: #000">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <h2>{{ $i + 1 }}</h2>
                                                </div>
                                                <div class="col-md-4">
                                                    <small>Materi</small><br>
                                                    <b>{{ $isi['title'] }}</b>
                                                </div>
                                                <div class="col-md-6">
                                                    <small>Jumlah Permintaan Validasi</small><br>
                                                    <b class="text-danger">{{ $isi['jumlah'] }} Mahasiswa</b>
                                                </div>
                                            </div>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse-{{ $i }}" class="collapse" data-parent="#accordion">
                                    <div class="card-body">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nama</th>
                                                    <th>Task</th>
                                                    <th>Validasi</th>
                                                    <th>Tanggal</th>
                                                    <th>Opsi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ( $isi['all_mhs'] AS $index => $mhs )
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $mhs->user->name }}</td>
                                                    <td>{{ $mhs->task->task_name }}</td>
                                                    <td>{{ $mhs->validator }}</td>
                                                    <td>{{ date('d F Y', strtotime($mhs->created_at)) }}</td>
                                                    <td>
                                                        <a href="{{ url('teacher/android23/waiting/preview/'. $mhs->id) }}" class="btn btn-sm btn-danger text-sm">
                                                            <i class="fas fa-edit"></i> Periksa Pekerjaan
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection