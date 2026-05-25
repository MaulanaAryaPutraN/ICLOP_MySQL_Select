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
                        <h3>{{ $total_mhs }} mahasiswa</h3>
                    </div>

                    @if ( $notifikasi['total'] > 0 )
                    <div class="col-md-4">
                        <b>Pesan : <span class="text-danger">{{ $notifikasi['total'] }} mahasiswa menunggu</span></b><br>
                        <a href="{{ url('teacher/android23/waiting') }}" class="btn btn-sm btn-default pulse">
                            <i class="far fa-bell"></i> Menuju Validasi Mahasiswa
                        </a>

                        <div class="modal fade" id="modal-notifikasi">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <h3 style="margin: 0px">Validasi</h3>
                                        <small>Total mahasiswa yang membutuhkan validasi yaitu {{ $notifikasi['total'] }} mhs</small>

                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Topik</th>
                                                    <th>Permintaan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ( $notifikasi['notify'] AS $index => $isi ) 

                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        <a href="">
                                                            {{ $isi->topic->title }}<br>
                                                            <small>(Klik untuk melihat)</small>
                                                        </a>
                                                    </td>
                                                    <td><span class="badge badge-danger">{{ $isi->waiting }} mhs</span></td>
                                                </tr>

                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    @endif 

                </div>




                <!-- Main content -->
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Topik</th>
                            <th>Task</th>
                            <th>Enroll</th>
                            <th>Pembaruan</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $dt_keseluruhan AS $index => $isi )
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $isi->title }}</td>
                            <td>{{ $isi->total }} task</td>
                            <td>{{ $isi->enroll }} mhs</td>
                            <td>{{ date('d M Y H.i', $isi->updated_at) }}</td>
                            <td>
                                <a href="{{ url('teacher/android23/overview/'. $isi->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-book"></i> detail</a>
                            </td>
                        </tr>

                        @endforeach
                    </tbody>
                </table>
                
            </div>

        </div>
    </div>
</div>

@endsection
