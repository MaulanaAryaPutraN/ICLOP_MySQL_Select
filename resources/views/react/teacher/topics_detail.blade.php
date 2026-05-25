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
                        <div class="col-md-9">
                            <p style="margin: 0px; font-size:13px"><b>Learning Topik yang diubah</b></p>
                            <h2>{{ $detail }}</h2>
                        </div>
                        <div class="col-md-3">
                            <a class="btn btn-primary btn-sm pulse" data-toggle="modal" data-target="#exampleModal" style="color:#fff">
                                <i class="fas fa-key" style="margin-right: 5px;color:#fff"></i> <!-- Ikon kunci -->
                                Tambah Materi
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            
                        </div>


                        <div class="col-md-12">
                            @if (session('message'))
                                <div class="alert alert-success">
                                    {{ session('message') }}
                                </div>
                            @endif 
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <table class="table" style="width: 100%; font-size: 14px" id="myTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul Materi</th>
                                        <th>Status</th>
                                        <th class="text-center">Caption</th>
                                        <th>File</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 0;
                                    @endphp
                                    @foreach($results as $topic)
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
                                             
                                        </td>
                                        <td></td>
                                        <td>
                                            <a style="font-size: 10px" href="" class="btn btn-sm btn-primary">
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
    
    <div class="modal fade" id="exampleModal" 
                                    tabindex="-1" 
                                    role="dialog" 
                                    aria-labelledby="exampleModalLabel" 
                                    aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width: 80%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Materi Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form method="POST" action="{{ url('php/teacher/topics/simpan') }}" enctype="multipart/form-data">
                 @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            
                                <div class="form-group" style="margin-bottom: 20px;">
                                    <input type="hidden" name="id" class='form-control' value='{{ $id }}' placeholder="Tittle" />
                                    <input type="text" name="title" class='form-control' placeholder="Tittle" />
                                </div>
                                <div class="form-group" style="margin-bottom: 20px;">
                                    <input type="text" name="caption" class='form-control' placeholder="Caption" />
                                </div>
                                <div class="form-group">
                                    <textarea id="myeditorinstance" name="editor" placeholder="Keterangan Materi"></textarea>
                                    
                                    <!-- <textarea id="myeditorinstance" name="editor"></textarea> -->    
                                </div>
                                <div class="form-group" style="margin-bottom: 20px;">
                                    <label>Upload Materi</label>
                                    <input type="file" name="materials" class='form-control' />
                                </div>
                            
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" style="margin-left: 10px; width: 160px;" >
                            <i class="fas fa-key" style="margin-right: 5px;"></i>Simpan Materi
                        </button>
                    </div>
                </div>
            </form>

            
                </div>
            </div>

</section>

@endsection
