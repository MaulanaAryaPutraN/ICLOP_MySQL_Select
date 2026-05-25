@extends('android.admin.admin')
@section('content')


<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Topik Android</h1>
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
                            <h5 style="margin: 0px">Daftar Topik Android 23</h5>
                            <p>Menampilkan materi android keseluruhan</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <hr>
                            <a href="javascript:;" data-toggle="modal" data-target="#modal-xl" class="btn btn-app pulse">
                                <i class="fas fa-plus"></i> Tambah Topik
                            </a>
                            <a class="btn btn-app">
                                <i class="fas fa-spinner"></i> Truncate
                            </a>
                            <a class="btn btn-app">
                                <span class="badge bg-warning">3</span>
                                <i class="fas fa-file-pdf"></i> Unduh PDF
                            </a>
                            <a class="btn btn-app">
                                <span class="badge bg-warning">3</span>
                                <i class="fas fa-check"></i> Publikasi
                            </a>
                        </div>




                        <div class="modal fade" id="modal-xl">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">

                                    <form action="{{ url('android23/topic/add') }}" method="post" enctype="multipart/form-data">

                                    @csrf
                                    <div class="modal-body">
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
                                            <textarea name="description" class="form-control" placeholder="Masukkan deskripsi materi . . ."></textarea>
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

                        <div class="col-md-9">
                            <table class="table" style="width: 100%; font-size: 14px">
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
                                    
                                    @forelse ( $dt_keseluruhan AS $index => $isi)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $isi->title }}</td>
                                        <td>
                                            @php 

                                            $label = "badge-secondary";
                                            if ( $isi->status == "publish" ) {

                                                $label = "badge-primary";
                                            }
                                            @endphp 

                                            <span class="badge {{ $label }}">{{ $isi->status }}</span>
                                        </td>
                                        <td class="text-center">
                                            @php 

                                            if ( $isi->total > 0 ) {

                                                echo $isi->total." task";
                                            } else {

                                                echo "Kosong";
                                            }

                                            @endphp 
                                        </td>
                                        <td>{{ date('d M Y', $isi->updated_at) }}</td>
                                        <td>
                                            <a style="font-size: 10px" href="{{ url('android23/topic/learning/'. $isi->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-plus"></i> Learning Task
                                            </a>
                                            <a style="font-size: 10px" href="javascript:;" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modal-update-{{ $index }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a style="font-size: 10px" onclick="return confirm('Apakah anda ingin menghapus topik ini ?')" href="{{ url('android23/topic/delete/'. $isi->id) }}" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </a>




                                            <div class="modal fade" id="modal-update-{{ $index }}">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                    
                                                        <form action="{{ url('android23/topic/update/'. $isi->id) }}" method="post" enctype="multipart/form-data">
                    
                                                        @csrf
                                                        <div class="modal-body">
                                                            <h4 style="margin: 0px">Update Topik Android</h4>
                                                            <p>Isi form dibawah ini untuk menambahkan topik android</p>
                    
                                                            <div class="form-group">
                                                                <label for="">Judul Topik</label>
                                                                <input type="text" name="title" class="form-control" value="{{ $isi->title }}" placeholder="Masukkan judul topik . . .">
                                                                <small>Berisi judul topik materi android</small>
                                                            </div>
                                                            
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="">Folder Path</label>
                                                                        <input type="text" name="folder_path" value="{{ $isi->folder_path }}" class="form-control" placeholder="Direktori file . . .">
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
                                                                    <option value="draft" @php if ( $isi->status == "draft" ) { echo 'selected="selected"'; } @endphp>Draft</option>
                                                                    <option value="publish" @php if ( $isi->status == "publish" ) { echo 'selected="selected"'; } @endphp>Publikasi</option>
                                                                </select>
                                                                <small>Status materi android</small>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label for="">Deskripsi</label>
                                                                <textarea name="description" class="form-control" placeholder="Masukkan deskripsi materi . . .">{{ $isi->description }}</textarea>
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
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <span>Maaf</span><br>
                                            <small>Anda belum menambahkan topik</small>
                                        </td>
                                    </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


@endsection