@extends('php/teacher/home')
@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                
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
            
            <div class="col-md-12">
                <div class="card card-body">
                    
                    <div class="row">
                        <div class="col-md-12">
                            <h5 style="margin: 0px">Daftar Topik Basic PHP</h5>
                            <p>Menampilkan materi Basic PHP keseluruhan</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <a href="javascript:;" data-toggle="modal" data-target="#modal-xl" class="btn btn-app pulse">
                                <i class="fas fa-plus"></i> Tambah Topik
                            </a>
                        </div>


                        <div class="col-md-12">
                            <table class="table" style="width: 100%; font-size: 14px" id="myTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul Topik</th>
                                        <th>Status</th>
                                        <th class="text-center">Jumlah Task</th>
                                        <th>Pembaruan</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 0;
                                    @endphp
                                    @foreach($topics as $topic)
                                    @php
                                        $no++;
                                    @endphp
                                    <tr>
                                        <td>@php echo $no; @endphp</td>
                                        <td>{{ $topic->title }}</td>
                                        <td>
                                            <span class="badge badge-secondary">{{ $topic->status }}</span>
                                        </td>
                                        <td class="text-center">
                                            7 task 
                                        </td>
                                        <td>07 Aug 2023</td>
                                        <td>
                                            <a style="font-size: 10px" href="{{ url('php/teacher/topics/add/'. $topic->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-plus"></i> Learning Task
                                            </a>
                                            
                                            <a style="font-size: 10px" href="javascript:;" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modal-update-0">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a style="font-size: 10px" onclick="return confirm('Apakah anda ingin menghapus topik ini ?')" href="http://127.0.0.1:8000/android23/topic/delete/7" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </a>

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
    
    <div class="modal fade" id="modal-xl">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <form action="http://127.0.0.1:8000/android23/topic/add" method="post" enctype="multipart/form-data">

                <input type="hidden" name="_token" value="4XYtt4wvnvqqPikgmbiGtTvYLEWbKM9w6bw1B4J8" autocomplete="off">                                    <div class="modal-body">
                    <h4 style="margin: 0px">Tambah Topik Android</h4>
                    <p>Isi form dibawah ini untuk menambahkan topik android</p>

                    <div class="form-group">
                        <label for="">Judul Topik</label>
                        <input type="text" name="title" class="form-control" placeholder="Masukkan judul topik . . .">
                        <small>Berisi judul topik materi android</small>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Folder Path</label>
                                <input type="text" name="folder_path" class="form-control" placeholder="Direktori file . . .">
                                <small>Berisi direktori topik materi android</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Picture (Opsional)</label>
                                <input type="file" name="picturePath">
                                <small>Berisi foto materi android</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="">Status Publikasi</label>
                        <select name="status" class="form-control" id="">
                            <option value="draft">Draft</option>
                            <option value="publish">Publikasi</option>
                        </select>
                        <small>Status materi android</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="">Deskripsi</label>
                        <textarea name="description" class="form-control" placeholder="Masukkan deskripsi materi . . ." data-gramm="false" wt-ignore-input="true"></textarea>
                        <small>Deskripsi materi android</small>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Tambahkan</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- modal edit -->
    <div class="modal fade" id="modal-update-0">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <form action="http://127.0.0.1:8000/android23/topic/update/7" method="post" enctype="multipart/form-data">

                <input type="hidden" name="_token" value="4XYtt4wvnvqqPikgmbiGtTvYLEWbKM9w6bw1B4J8" autocomplete="off">                                                        <div class="modal-body">
                    <h4 style="margin: 0px">Update Topik Android</h4>
                    <p>Isi form dibawah ini untuk menambahkan topik android</p>

                    <div class="form-group">
                        <label for="">Judul Topik</label>
                        <input type="text" name="title" class="form-control" value="A1:Java - Basic UI Java Edition" placeholder="Masukkan judul topik . . .">
                        <small>Berisi judul topik materi android</small>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Folder Path</label>
                                <input type="text" name="folder_path" value="A1_BASIC_UI" class="form-control" placeholder="Direktori file . . .">
                                <small>Berisi direktori topik materi android</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Picture (Opsional)</label>
                                <input type="file" name="picturePath">
                                <small>Berisi foto materi android</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="">Status Publikasi</label>
                        <select name="status" class="form-control" id="">
                            <option value="draft" selected="selected">Draft</option>
                            <option value="publish">Publikasi</option>
                        </select>
                        <small>Status materi android</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="">Deskripsi</label>
                        <textarea name="description" class="form-control" placeholder="Masukkan deskripsi materi . . ." data-gramm="false" wt-ignore-input="true"></textarea>
                        <small>Deskripsi materi android</small>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Tambahkan</button>
                </div>
                </form>
            </div>
        </div>
    </div>

</section>

@endsection
