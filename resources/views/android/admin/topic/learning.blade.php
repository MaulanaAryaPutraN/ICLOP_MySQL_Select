@extends('android.admin.admin')
@section('content')


<script src="{{asset('lte/plugins/jquery/jquery.min.js')}}"></script>
<script src="https://bootstrap-tagsinput.github.io/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
<script src="https://unpkg.com/@yaireo/tagify"></script>
<script src="https://unpkg.com/@yaireo/tagify@3.1.0/dist/tagify.polyfills.min.js"></script>


<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Learning Topik Android</h1>
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
        <div class="row justify-content-center">

            <div class="col-md-12">
                <div class="card card-body">


                    <div class="row">
                        <div class="col-md-8">
                            <small class="text-bold">Learning Topik yang diubah</small>
                            <h4>{{ $topic->title }}</h4>
                        </div>
                        <div class="col-md-3 text-right">
                            {{-- <button class="btn btn-primary btn-sm pulse" data-toggle="modal" data-target="#modal-xl"><i class="fas fa-plus"></i> Tambah Materi</button><br> --}}
                            <button class="btn btn-primary btn-sm pulse" data-toggle="modal" data-target="#modal-option"><i class="fas fa-plus"></i> Tambah Materi</button><br>
                            <small>Klik untuk menambahkan materi</small>
                        </div>
                    </div>


                    <div class="modal fade" id="modal-option">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">

                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="text-center">
                                                @php echo svg_task() @endphp
                                                <h3>Tambah Materi</h3>
                                                <p>Materi pemrogaman, topik yang akan dipelajari, serta lampiran materi dalam bentuk PDF</p>

                                                <button class="btn btn-primary btn-sm pulse" id="on-modal-xl"><i class="fas fa-plus"></i> Tambah Materi</button>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-center">
                                                @php echo svg_materi() @endphp
                                                <h3>Tambah Submission Area</h3>
                                                <p>Area untuk menambahkan tugas dari setiap learning yang telah dipelajari</p>

                                                <button class="btn btn-warning btn-sm pulse" id="on-modal-submission"><i class="fas fa-plus"></i> Tambah Submission</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modal-xl">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">

                                <form action="{{ url('android23/topic/learning/add/'. $topic->id.'/material' ) }}" method="post" enctype="multipart/form-data">

                                @csrf
                                <div class="modal-body">
                                    <h4 style="margin: 0px">Tambah Learning Topik Android</h4>
                                    <p>Isi form dibawah ini untuk menambahkan topik android</p>

                                    <div class="form-group">
                                        <label for="">Title</label>
                                        <input type="text" name="title" class="form-control" placeholder="Masukkan judul learning . . ." required="">
                                        <small>Berisi judul learning topik materi android</small>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="">Caption (Opsional)</label>
                                        <input type="text" name="caption" class="form-control" placeholder="Masukkan caption learning . . .">
                                        <small>Berisi caption learning topik materi android</small>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Urutan Materi</label>
                                                <input type="number" name="task_no" class="form-control" placeholder="Urutan materi . . .">
                                                <small>Urutan materi learning topik android</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">PDF</label>
                                                <input type="file" name="material" required="">
                                                <small>Berisi file PDF materi android</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        
                                        <input name="tags" type="hidden" value='checkActivityName()' class="form-control" id="form-a">
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


                    <div class="modal fade" id="modal-submission">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">

                                <form action="{{ url('android23/topic/learning/add/'. $topic->id.'/submission') }}" method="post" enctype="multipart/form-data">

                                @csrf
                                <div class="modal-body">
                                    <h4 style="margin: 0px">Tambah Submission Topik Android</h4>
                                    <p>Isi form dibawah ini untuk menambahkan topik android</p>

                                    <div class="form-group">
                                        <label for="">Title</label>
                                        <input type="text" name="title" class="form-control" placeholder="Masukkan judul learning . . ." required="">
                                        <small>Berisi judul learning topik materi android</small>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="">Caption (Opsional)</label>
                                        <input type="text" name="caption" class="form-control" placeholder="Masukkan caption learning . . .">
                                        <small>Berisi caption learning topik materi android</small>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Urutan Materi</label>
                                                <input type="number" name="task_no" class="form-control" placeholder="Urutan materi . . .">
                                                <small>Urutan materi learning topik android</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Test Case</label>
                                        <input name="tags" value='checkActivityName()' class="form-control" id="form-b">
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

                    <hr>


                    <table class="table" id="datatable">
                        <thead>
                            <tr>
                                <th>Task.No</th>
                                <th>Info</th>
                                <th>Name</th>
                                <th>Caption</th>
                                <th>File</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($task as $index => $isi)

                            <tr>
                                <td>Urutan - {{ $isi->task_no }}<br><small>{{ $isi->tipe }}</small></td>
                                <td><b class="text-success">
                                    @if( $isi->tipe == "submission" )
                                    {{ $isi->testcase->count() }} Testcase
                                    @endif
                                </b></td>
                                <td>{{ $isi->task_name }}</td>
                                <td>{{ $isi->caption }}</td>
                                <td>
                                    @php 

                                        $direktori = "android23/document/$topic->folder_path/$isi->material";
                                    @endphp 
                                    <a href="{{ url($direktori) }}">{{ $isi->material }}</a>
                                </td>
                                <td>
                                    <a style="font-size: 10px" href="javascript:;" class="btn btn-sm btn-secondary" onclick="doSettingTestcase('{{ $index }}')">
                                        <i class="fas fa-edit"></i>
                                        Testcase
                                    </a>
                                    <a style="font-size: 10px" href="javascript:;" class="btn btn-sm btn-warning" onclick="doUpdate('{{ $index }}')">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a style="font-size: 10px" onclick="return confirm('Apakah anda ingin menghapus learning topik ini ?')" href="{{ url('android23/topic/learning/delete/'. $topic->id.'/'.$isi->id) }}" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </a>

                                    <div class="modal fade" id="modal-update-{{ $index }}">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                
                                                <form action="{{ url('android23/topic/learning/update/'. $topic->id.'/'.$isi->id) }}" method="post" enctype="multipart/form-data">
                
                                                @csrf
                                                <div class="modal-body">
                                                    <h4 style="margin: 0px">Sunting Learning Topik Android</h4>
                                                    <p>Isi form dibawah ini untuk menambahkan topik android</p>
                
                                                    <div class="form-group">
                                                        <label for="">Title</label>
                                                        <input type="text" name="title" class="form-control" value="{{ $isi->task_name }}" placeholder="Masukkan judul learning . . ." required="">
                                                        <small>Berisi judul learning topik materi android</small>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="">Caption (Opsional)</label>
                                                        <input type="text" name="caption" value="{{ $isi->caption }}" class="form-control" placeholder="Masukkan caption learning . . .">
                                                        <small>Berisi caption learning topik materi android</small>
                                                    </div>
                                                    

                                                    @if ( $isi->tipe == "submission" )

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="">Urutan Materi</label>
                                                                <input type="number" name="task_no" value="{{ $isi->task_no }}" class="form-control" placeholder="Urutan materi . . .">
                                                                <small>Urutan materi learning topik android</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @else
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="">Urutan Materi</label>
                                                                <input type="number" name="task_no" value="{{ $isi->task_no }}" class="form-control" placeholder="Urutan materi . . .">
                                                                <small>Urutan materi learning topik android</small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="">PDF</label>
                                                                <input type="file" name="material">
                                                                <small>Berisi file PDF materi android</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif 
                                                    
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Tambahkan</button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal -->
                                    <div class="modal fade" id="display-testcase-{{ $index }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">

                                        @if ( $isi->testcase->count() > 0 )
                                        <form action="{{ url('android23/topic/update-testcase') }}" method="POST">

                                        @else
                                        <form action="{{ url('android23/topic/add-testcase/'. $isi->android_topic_id.'/'. $isi->id) }}" method="POST">
                                        @endif
                                          <div class="modal-body">
                                            <h4 style="margin: 0px">Daftar Testcase</h4>
                                            <p>Pengaturan pembobotan per-testcase</p>

                                            <hr>
                                            @csrf

                                            <input type="hidden" name="task_id" value="{{ $isi->id }}">
                                            <input type="hidden" name="topic_id" value="{{ $isi->android_topic_id }}">

                                            @if ( $isi->testcase->count() > 0 )
                                            @foreach ( $isi->testcase AS $det_tc )
                                            <div class="row form-group">
                                                <div class="col-md-2">
                                                    <a onclick="return confirm('Apakah anda ingin menghapus testcase {{ $det_tc->case }}')" href="{{ url('android23/topic/remove-testcase/'. $isi->android_topic_id.'/'. $det_tc->id) }}" class="btn btn-sm btn-default" style="font-size: 12px; color: red;"><i class="fas fa-times"></i></a>
                                                </div>
                                                <div class="col-md-7">
                                                    <small>Materi Testcase</small><br>
                                                    <b>{{ $det_tc->case }}</b>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="hidden" name="case[]" value="{{ $det_tc->case }}">
                                                    <input type="text" name="score[]" class="form-control" value="{{ $det_tc->score }}" required="">
                                                </div>
                                            </div>
                                            @endforeach
                                            @else 

                                            <div class="form-group">
                                                
                                                <label for="">Test Case</label>
                                                <input name="tags" value='checkActivityName()' class="form-control" id="form-{{ $index}}">
                                                
                                            </div>
                                            @endif
                                            
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <a onclick="return confirm('Apakah anda ingin mereset testcase dari task {{ $isi->task_name }}')" href="{{ url('android23/topic/reset/'. $isi->android_topic_id.'/'. $isi->id) }}" class="btn btn-secondary">Reset</a>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
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
                                    <b>Kosong</b>
                                    <small>Anda belum menambahkan learning topik</small>
                                </td>
                            </tr>
                                
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</section>


@php 

    function svg_materi() {

        return '<svg style="width: 200px; height: 200px" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 794.23533 458.82848" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M569.645,655.81836a6.78564,6.78564,0,0,1-3.34668-.88574L372.80225,545.53711a6.828,6.828,0,0,1-2.58008-9.293L522.625,266.67822a6.81755,6.81755,0,0,1,9.29248-2.58008L725.41309,373.49365a6.82785,6.82785,0,0,1,2.58105,9.29248L575.59082,652.35156a6.776,6.776,0,0,1-4.11182,3.21582A6.85621,6.85621,0,0,1,569.645,655.81836Z" transform="translate(-202.88234 -220.58576)" fill="#e6e6e6"/><rect x="618.29869" y="218.2757" width="1.57992" height="234.91839" transform="translate(-180.74045 488.83783) rotate(-60.51777)" fill="#fff"/><rect x="604.30254" y="243.03172" width="1.57992" height="234.91839" transform="translate(-209.39867 489.22631) rotate(-60.51777)" fill="#fff"/><rect x="590.30639" y="267.78775" width="1.57992" height="234.91839" transform="translate(-238.05689 489.6148) rotate(-60.51777)" fill="#fff"/><rect x="576.31024" y="292.54377" width="1.57992" height="234.91839" transform="translate(-266.71511 490.00329) rotate(-60.51777)" fill="#fff"/><rect x="562.31408" y="317.29979" width="1.57992" height="234.91839" transform="translate(-295.37333 490.39178) rotate(-60.51777)" fill="#fff"/><rect x="548.31793" y="342.05581" width="1.57992" height="234.91839" transform="translate(-324.03155 490.78027) rotate(-60.51777)" fill="#fff"/><rect x="534.32178" y="366.81184" width="1.57992" height="234.91839" transform="translate(-352.68977 491.16876) rotate(-60.51777)" fill="#fff"/><rect x="520.32563" y="391.56786" width="1.57992" height="234.91839" transform="translate(-381.34799 491.55725) rotate(-60.51777)" fill="#fff"/><rect x="506.32948" y="416.32388" width="1.57992" height="234.91839" transform="translate(-410.00621 491.94574) rotate(-60.51777)" fill="#fff"/><rect x="492.33333" y="441.07991" width="1.57992" height="234.91839" transform="translate(-438.66442 492.33423) rotate(-60.51777)" fill="#fff"/><rect x="478.33717" y="465.83593" width="1.57992" height="234.91839" transform="translate(-467.32264 492.72272) rotate(-60.51777)" fill="#fff"/><path d="M551.645,614.81836a6.78564,6.78564,0,0,1-3.34668-.88574L354.80225,504.53711a6.828,6.828,0,0,1-2.58008-9.293L504.625,225.67822a6.81755,6.81755,0,0,1,9.29248-2.58008L707.41309,332.49365a6.82785,6.82785,0,0,1,2.58105,9.29248L557.59082,611.35156a6.776,6.776,0,0,1-4.11182,3.21582A6.85621,6.85621,0,0,1,551.645,614.81836Z" transform="translate(-202.88234 -220.58576)" fill="#f2f2f2"/><rect x="600.29869" y="177.2757" width="1.57992" height="234.91839" transform="translate(-154.19084 452.34697) rotate(-60.51777)" fill="#fff"/><rect x="586.30254" y="202.03172" width="1.57992" height="234.91839" transform="translate(-182.84906 452.73546) rotate(-60.51777)" fill="#fff"/><rect x="572.30639" y="226.78775" width="1.57992" height="234.91839" transform="translate(-211.50728 453.12395) rotate(-60.51777)" fill="#fff"/><rect x="558.31024" y="251.54377" width="1.57992" height="234.91839" transform="translate(-240.1655 453.51244) rotate(-60.51777)" fill="#fff"/><rect x="544.31408" y="276.29979" width="1.57992" height="234.91839" transform="translate(-268.82372 453.90093) rotate(-60.51777)" fill="#fff"/><rect x="530.31793" y="301.05581" width="1.57992" height="234.91839" transform="translate(-297.48194 454.28942) rotate(-60.51777)" fill="#fff"/><rect x="516.32178" y="325.81184" width="1.57992" height="234.91839" transform="translate(-326.14016 454.67791) rotate(-60.51777)" fill="#fff"/><rect x="502.32563" y="350.56786" width="1.57992" height="234.91839" transform="translate(-354.79838 455.0664) rotate(-60.51777)" fill="#fff"/><rect x="488.32948" y="375.32388" width="1.57992" height="234.91839" transform="translate(-383.4566 455.45489) rotate(-60.51777)" fill="#fff"/><rect x="474.33333" y="400.07991" width="1.57992" height="234.91839" transform="translate(-412.11482 455.84338) rotate(-60.51777)" fill="#fff"/><rect x="460.33717" y="424.83593" width="1.57992" height="234.91839" transform="translate(-440.77304 456.23187) rotate(-60.51777)" fill="#fff"/><path d="M532.36285,220.58576h-309.237v430h309.237a4.18841,4.18841,0,0,0,4.18832-4.18831V224.77407A4.18841,4.18841,0,0,0,532.36285,220.58576ZM240.57714,638.71888a7.67858,7.67858,0,1,1,7.67857-7.67857A7.67854,7.67854,0,0,1,240.57714,638.71888Zm0-39.09091a7.67857,7.67857,0,1,1,7.67857-7.67857A7.67854,7.67854,0,0,1,240.57714,599.628Zm0-39.09091a7.67857,7.67857,0,1,1,7.67857-7.67857A7.67854,7.67854,0,0,1,240.57714,560.53706Zm0-39.09091a7.67857,7.67857,0,1,1,7.67857-7.67857A7.67854,7.67854,0,0,1,240.57714,521.44615Zm0-39.09091a7.67857,7.67857,0,1,1,7.67857-7.67857A7.67854,7.67854,0,0,1,240.57714,482.35524Zm0-39.09091a7.67857,7.67857,0,1,1,7.67857-7.67857A7.67854,7.67854,0,0,1,240.57714,443.26433Zm0-39.09091a7.67857,7.67857,0,1,1,7.67857-7.67857A7.67854,7.67854,0,0,1,240.57714,404.17342Zm0-39.09091a7.67857,7.67857,0,1,1,7.67857-7.67857A7.67854,7.67854,0,0,1,240.57714,365.08251Zm0-39.09091a7.67857,7.67857,0,1,1,7.67857-7.67857A7.67854,7.67854,0,0,1,240.57714,325.9916Zm0-39.0909a7.67858,7.67858,0,1,1,7.67857-7.67858A7.67855,7.67855,0,0,1,240.57714,286.9007Zm0-39.09091a7.67858,7.67858,0,1,1,7.67857-7.67858A7.67855,7.67855,0,0,1,240.57714,247.80979Z" transform="translate(-202.88234 -220.58576)" fill="#3f3d56"/><path d="M223.12584,244.31953c-2.072,0-20.2435-.09783-20.2435-3.49026s18.1715-3.49026,20.2435-3.49026v1.3961c-10.70281,0-17.64268,1.19807-18.76031,2.09416,1.11763.89608,8.0575,2.09415,18.76031,2.09415,10.67895,0,17.61167-1.193,18.75265-2.088-.543-.38345-2.76111-1.18716-8.71065-1.7015l.12015-1.39065c10.08136.8712,10.08136,2.48033,10.08136,3.086C243.36935,244.2217,225.19784,244.31953,223.12584,244.31953Z" transform="translate(-202.88234 -220.58576)" fill="#2f2e41"/><path d="M223.12584,282.71238c-2.072,0-20.2435-.09782-20.2435-3.49026s18.1715-3.49026,20.2435-3.49026V277.128c-10.70281,0-17.64268,1.19807-18.76031,2.09415,1.11763.89609,8.0575,2.09416,18.76031,2.09416,10.67895,0,17.61167-1.193,18.75265-2.088-.543-.38345-2.76111-1.18717-8.71065-1.7015l.12015-1.39065c10.08136.8712,10.08136,2.48033,10.08136,3.086C243.36935,282.61456,225.19784,282.71238,223.12584,282.71238Z" transform="translate(-202.88234 -220.58576)" fill="#2f2e41"/><path d="M223.12584,321.80329c-2.072,0-20.2435-.09782-20.2435-3.49026s18.1715-3.49026,20.2435-3.49026v1.39611c-10.70281,0-17.64268,1.19807-18.76031,2.09415,1.11763.89609,8.0575,2.09416,18.76031,2.09416,10.67895,0,17.61167-1.193,18.75265-2.088-.543-.38345-2.76111-1.18717-8.71065-1.7015l.12015-1.39065c10.08136.8712,10.08136,2.48033,10.08136,3.086C243.36935,321.70547,225.19784,321.80329,223.12584,321.80329Z" transform="translate(-202.88234 -220.58576)" fill="#2f2e41"/><path d="M223.12584,360.8942c-2.072,0-20.2435-.09782-20.2435-3.49026s18.1715-3.49026,20.2435-3.49026v1.39611c-10.70281,0-17.64268,1.19807-18.76031,2.09415,1.11763.89609,8.0575,2.09416,18.76031,2.09416,10.67895,0,17.61167-1.193,18.75265-2.088-.543-.38345-2.76111-1.18717-8.71065-1.7015l.12015-1.39065c10.08136.8712,10.08136,2.48033,10.08136,3.086C243.36935,360.79638,225.19784,360.8942,223.12584,360.8942Z" transform="translate(-202.88234 -220.58576)" fill="#2f2e41"/><path d="M223.12584,399.98511c-2.072,0-20.2435-.09782-20.2435-3.49026s18.1715-3.49026,20.2435-3.49026v1.39611c-10.70281,0-17.64268,1.19807-18.76031,2.09415,1.11763.89608,8.0575,2.09416,18.76031,2.09416,10.67895,0,17.61167-1.193,18.75265-2.088-.543-.38345-2.76111-1.18717-8.71065-1.70151l.12015-1.39065c10.08136.87121,10.08136,2.48034,10.08136,3.086C243.36935,399.88729,225.19784,399.98511,223.12584,399.98511Z" transform="translate(-202.88234 -220.58576)" fill="#2f2e41"/><path d="M223.12584,439.076c-2.072,0-20.2435-.09782-20.2435-3.49026s18.1715-3.49026,20.2435-3.49026v1.3961c-10.70281,0-17.64268,1.19808-18.76031,2.09416,1.11763.89608,8.0575,2.09416,18.76031,2.09416,10.67895,0,17.61167-1.193,18.75265-2.088-.543-.38346-2.76111-1.18717-8.71065-1.70151l.12015-1.39065c10.08136.87121,10.08136,2.48034,10.08136,3.086C243.36935,438.9782,225.19784,439.076,223.12584,439.076Z" transform="translate(-202.88234 -220.58576)" fill="#2f2e41"/><path d="M223.12584,478.16693c-2.072,0-20.2435-.09782-20.2435-3.49026s18.1715-3.49026,20.2435-3.49026v1.3961c-10.70281,0-17.64268,1.19808-18.76031,2.09416,1.11763.89608,8.0575,2.09416,18.76031,2.09416,10.67895,0,17.61167-1.193,18.75265-2.088-.543-.38345-2.76111-1.18716-8.71065-1.7015l.12015-1.39065c10.08136.8712,10.08136,2.48034,10.08136,3.086C243.36935,478.06911,225.19784,478.16693,223.12584,478.16693Z" transform="translate(-202.88234 -220.58576)" fill="#2f2e41"/><path d="M223.12584,517.25784c-2.072,0-20.2435-.09782-20.2435-3.49026s18.1715-3.49026,20.2435-3.49026v1.3961c-10.70281,0-17.64268,1.19808-18.76031,2.09416,1.11763.89608,8.0575,2.09415,18.76031,2.09415,10.67895,0,17.61167-1.193,18.75265-2.088-.543-.38345-2.76111-1.18716-8.71065-1.7015l.12015-1.39065c10.08136.8712,10.08136,2.48034,10.08136,3.086C243.36935,517.16,225.19784,517.25784,223.12584,517.25784Z" transform="translate(-202.88234 -220.58576)" fill="#2f2e41"/><path d="M223.12584,556.34875c-2.072,0-20.2435-.09783-20.2435-3.49026s18.1715-3.49026,20.2435-3.49026v1.3961c-10.70281,0-17.64268,1.19807-18.76031,2.09416,1.11763.89608,8.0575,2.09415,18.76031,2.09415,10.67895,0,17.61167-1.193,18.75265-2.088-.543-.38345-2.76111-1.18716-8.71065-1.7015l.12015-1.39065c10.08136.8712,10.08136,2.48033,10.08136,3.086C243.36935,556.25092,225.19784,556.34875,223.12584,556.34875Z" transform="translate(-202.88234 -220.58576)" fill="#2f2e41"/><path d="M223.12584,595.43966c-2.072,0-20.2435-.09783-20.2435-3.49026s18.1715-3.49026,20.2435-3.49026v1.3961c-10.70281,0-17.64268,1.19807-18.76031,2.09416,1.11763.89608,8.0575,2.09415,18.76031,2.09415,10.67895,0,17.61167-1.193,18.75265-2.088-.543-.38345-2.76111-1.18716-8.71065-1.7015l.12015-1.39065c10.08136.8712,10.08136,2.48033,10.08136,3.086C243.36935,595.34183,225.19784,595.43966,223.12584,595.43966Z" transform="translate(-202.88234 -220.58576)" fill="#2f2e41"/><path d="M223.12584,634.53057c-2.072,0-20.2435-.09783-20.2435-3.49026s18.1715-3.49026,20.2435-3.49026v1.3961c-10.70281,0-17.64268,1.19807-18.76031,2.09416,1.11763.89608,8.0575,2.09415,18.76031,2.09415,10.67895,0,17.61167-1.193,18.75265-2.088-.543-.38345-2.76111-1.18716-8.71065-1.7015l.12015-1.39065c10.08136.8712,10.08136,2.48033,10.08136,3.086C243.36935,634.43274,225.19784,634.53057,223.12584,634.53057Z" transform="translate(-202.88234 -220.58576)" fill="#2f2e41"/><path d="M469.18915,384.977H315.61772a4.54253,4.54253,0,0,1-4.53733-4.53733V320.40719a4.54253,4.54253,0,0,1,4.53733-4.53734H469.18915a4.54248,4.54248,0,0,1,4.53734,4.53734v60.03247A4.54248,4.54248,0,0,1,469.18915,384.977Z" transform="translate(-202.88234 -220.58576)" fill="#fff"/><path d="M411.94889,336.46238H372.858a3.49026,3.49026,0,1,1,0-6.98052h39.09091a3.49026,3.49026,0,0,1,0,6.98052Z" transform="translate(-202.88234 -220.58576)" fill="#00b0ff"/><path d="M433.5885,353.91368H351.21837a3.49026,3.49026,0,0,1,0-6.98052H433.5885a3.49026,3.49026,0,0,1,0,6.98052Z" transform="translate(-202.88234 -220.58576)" fill="#00b0ff"/><path d="M433.5885,371.365H351.21837a3.49026,3.49026,0,0,1,0-6.98052H433.5885a3.49026,3.49026,0,0,1,0,6.98052Z" transform="translate(-202.88234 -220.58576)" fill="#00b0ff"/><path d="M832.51129,365.51563a11.57687,11.57687,0,0,0,9.041-18.8101c-5.7026-7.12627-18.18622-6.03465-23.18046-13.67408-4.23215-6.47369-.32395-15.36376,5.31243-20.66007,11.03421-10.36844,28.69794-12.91634,42.21255-6.08893S887.84132,328.84,886.04251,343.874c-.68515,5.72635-2.27338,12.67608,2.17072,16.35174,3.94416,3.26215,9.77363,1.41057,14.72817.12586a52.54152,52.54152,0,0,1,43.15806,8.03484c10.524,7.54244,18.19488,19.72426,18.1997,32.67192s-8.81341,25.99544-21.43238,28.89436c-12.36962,2.84163-25.11995-4.0671-33.8699-13.26061s-14.75775-20.628-22.73242-30.50159-19.009-18.57028-31.69876-18.799c-7.35254-.13251-15.402,2.45505-21.64218-1.43567" transform="translate(-202.88234 -220.58576)" fill="#2f2e41"/><circle cx="635.08387" cy="125.5244" r="24.56103" fill="#ffb8b8"/><path d="M785.72,509.04369a10.05581,10.05581,0,0,1,8.38094-12.94279l7.502-34.93849,13.01071,13.24972L805.49494,505.559a10.11027,10.11027,0,0,1-19.77494,3.4847Z" transform="translate(-202.88234 -220.58576)" fill="#ffb8b8"/><polygon points="708.994 440.209 696.945 442.469 660.62 323.16 686.404 319.823 708.994 440.209" fill="#ffb8b8"/><path d="M891.87193,660.29219H915.5158a0,0,0,0,1,0,0v14.88687a0,0,0,0,1,0,0H876.98507a0,0,0,0,1,0,0v0A14.88686,14.88686,0,0,1,891.87193,660.29219Z" transform="matrix(0.98285, -0.1844, 0.1844, 0.98285, -310.64467, -43.86345)" fill="#2f2e41"/><polygon points="630.033 446.634 617.774 446.633 615.941 336.146 642.035 336.147 630.033 446.634" fill="#ffb8b8"/><path d="M609.01639,443.13049h23.64387a0,0,0,0,1,0,0v14.88687a0,0,0,0,1,0,0H594.12953a0,0,0,0,1,0,0v0A14.88686,14.88686,0,0,1,609.01639,443.13049Z" fill="#2f2e41"/><path d="M813.18164,571.76855l1.126-53.99707c-.08594-.75976-8.94239-82.56689-12.24317-103.47021-3.35351-21.24121,13.97657-32.13281,14.15137-32.24023.77637-.31788,1.47754-.58692,2.17773-.84522h0a46.45906,46.45906,0,0,1,48.53907,9.86377,48.0104,48.0104,0,0,1,13.54394,48.688l-6.11328,21.16406c31.31836,56.62012,37.74512,80.98535,37.80664,81.22461l.01856.07324-.00391.07617c-1.12305,22.45508-94.4834,29.15137-98.457,29.42579Z" transform="translate(-202.88234 -220.58576)" fill="#00b0ff"/><polygon points="603.017 183.616 581.859 267.131 619.72 271.585 603.017 183.616" fill="#00b0ff"/><path d="M857.045,473.233a10.05574,10.05574,0,0,1,13.38045-7.66283l22.67946-27.6156,5.492,17.739L876.218,479.19821A10.11027,10.11027,0,0,1,857.045,473.233Z" transform="translate(-202.88234 -220.58576)" fill="#ffb8b8"/><path d="M886.665,473.30078,866.92383,460.5293l18.78906-19.89453-50.94531-15.50538L847.875,386.94531l16.124,2.30323,40.98731,30.36084a24.83274,24.83274,0,0,1,5.33008,34.36718l-.08887.09473Z" transform="translate(-202.88234 -220.58576)" fill="#00b0ff"/><path d="M815.8504,332.1502a80.48078,80.48078,0,0,0,34.8177,11.47239L846.998,339.2251a26.97055,26.97055,0,0,0,8.33123,1.65435c2.84365-.04429,5.82309-1.13924,7.437-3.481a8.48679,8.48679,0,0,0,.56807-7.83974,16.07434,16.07434,0,0,0-5.05681-6.323,30.10581,30.10581,0,0,0-28.02088-5.00785,17.993,17.993,0,0,0-8.36905,5.36846c-2.11524,2.60944-6.72881,4.95614-5.64579,8.13584Z" transform="translate(-202.88234 -220.58576)" fill="#2f2e41"/><path d="M995.927,679.41424H691.633a1.19068,1.19068,0,1,1,0-2.38137H995.927a1.19069,1.19069,0,0,1,0,2.38137Z" transform="translate(-202.88234 -220.58576)" fill="#3f3d56"/></svg>';
    }


    function svg_task() {

        return '<svg style="width: 200px; height: 200px" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 952 586" xmlns:xlink="http://www.w3.org/1999/xlink"><path id="a163713d-a7fc-4807-a77b-111ad75719e9-200" data-name="Path 133" d="M170.63353,697.233a158.3937,158.3937,0,0,0,7.4,43.785c.1.329.211.653.319.982h27.613c-.029-.295-.059-.624-.088-.982-1.841-21.166,8.677-148.453,21.369-170.483C226.13454,572.322,168.49254,629.979,170.63353,697.233Z" transform="translate(-124 -157)" fill="#f1f1f1"/><path id="a1d5a274-7181-45f3-aae5-db776f20014a-201" data-name="Path 134" d="M172.70558,741.018c.231.329.471.658.717.982h20.716c-.157-.28-.339-.609-.55-.982-3.422-6.176-13.551-24.642-22.953-43.785-10.1-20.572-19.374-41.924-18.593-49.652C151.80058,649.323,144.80861,702.457,172.70558,741.018Z" transform="translate(-124 -157)" fill="#f1f1f1"/><path d="M775.29126,277.577a14.42246,14.42246,0,0,0,21.04127,6.80778l39.97977,32.06957,2.47488-26.51836-38.34489-26.38825a14.50066,14.50066,0,0,0-25.151,14.02926Z" transform="translate(-124 -157)" fill="#9f616a"/><polygon points="713.205 130.446 698.283 119.903 681.358 139.458 701.046 151.126 713.205 130.446" fill="#00b0ff"/><path d="M833.93681,280.29582l50.28691,19.349L932.63291,267.947a24.62075,24.62075,0,0,1,31.79547,37.06016l-.433.48083-74.454,39.9183-81.32649-37.15291Z" transform="translate(-124 -157)" fill="#3f3d56"/><polygon points="695.76 583 171.76 583 190.76 212 272.76 212 348.374 265 714.76 265 695.76 583" fill="#e5e5e5"/><path d="M691.4891,626.86286,388.6242,502.13469a4.32609,4.32609,0,0,1-2.35009-5.64107L523.508,163.26232a4.3261,4.3261,0,0,1,5.64107-2.35009L832.014,285.6404a4.32609,4.32609,0,0,1,2.35009,5.64107L697.13017,624.51277A4.32609,4.32609,0,0,1,691.4891,626.86286Z" transform="translate(-124 -157)" fill="#fff"/><path d="M691.4891,626.86286,388.6242,502.13469a4.32609,4.32609,0,0,1-2.35009-5.64107L523.508,163.26232a4.3261,4.3261,0,0,1,5.64107-2.35009L832.014,285.6404a4.32609,4.32609,0,0,1,2.35009,5.64107L697.13017,624.51277A4.32609,4.32609,0,0,1,691.4891,626.86286ZM528.49088,162.51046a2.59553,2.59553,0,0,0-3.38465,1.41006L387.87234,497.15182a2.59552,2.59552,0,0,0,1.41005,3.38464l302.8649,124.72817a2.59553,2.59553,0,0,0,3.38465-1.41L832.76583,290.62327a2.59552,2.59552,0,0,0-1.41005-3.38464Z" transform="translate(-124 -157)" fill="#3f3d56"/><path d="M614.03119,325.86654,538.9143,294.93132a4.32609,4.32609,0,0,1-2.35009-5.64107l30.93522-75.11689a4.3261,4.3261,0,0,1,5.64107-2.35009l75.11689,30.93522a4.3261,4.3261,0,0,1,2.35009,5.64107l-30.93522,75.11689A4.3261,4.3261,0,0,1,614.03119,325.86654ZM572.4823,213.4215a2.59553,2.59553,0,0,0-3.38464,1.41006l-30.93522,75.11689a2.59553,2.59553,0,0,0,1.41006,3.38464l75.11688,30.93522a2.59553,2.59553,0,0,0,3.38465-1.41006l30.93521-75.11689a2.59551,2.59551,0,0,0-1.41-3.38464Z" transform="translate(-124 -157)" fill="#f2f2f2"/><path d="M588.67043,326.17072,513.55354,295.2355a3.89342,3.89342,0,0,1-2.11508-5.077l30.93522-75.11689a3.89343,3.89343,0,0,1,5.077-2.11508l75.11688,30.93522a3.89341,3.89341,0,0,1,2.11508,5.077l-30.93522,75.11689A3.89341,3.89341,0,0,1,588.67043,326.17072Z" transform="translate(-124 -157)" fill="#00b0ff"/><path d="M733.35509,428.74966,498.415,331.99483a3.889,3.889,0,1,1,2.96189-7.192L736.317,421.55762a3.889,3.889,0,0,1-2.96188,7.192Z" transform="translate(-124 -157)" fill="#ccc"/><path d="M666.41582,430.15629,488.213,356.76742a3.889,3.889,0,1,1,2.96188-7.192l178.20284,73.38887a3.889,3.889,0,1,1-2.96189,7.192Z" transform="translate(-124 -157)" fill="#ccc"/><path d="M770.07315,337.32136,666.18809,294.53861a3.889,3.889,0,1,1,2.96189-7.192l103.885,42.78275a3.889,3.889,0,0,1-2.96188,7.192Z" transform="translate(-124 -157)" fill="#ccc"/><path d="M733.94491,351.41683,655.98605,319.3112a3.889,3.889,0,1,1,2.96189-7.192l77.95886,32.10562a3.889,3.889,0,0,1-2.96189,7.192Z" transform="translate(-124 -157)" fill="#ccc"/><path d="M712.951,478.29485,478.011,381.54a3.889,3.889,0,1,1,2.96188-7.192L715.91289,471.1028a3.889,3.889,0,0,1-2.96188,7.192Z" transform="translate(-124 -157)" fill="#ccc"/><path d="M646.01174,479.70147,467.80891,406.3126a3.889,3.889,0,1,1,2.96188-7.192l178.20284,73.38887a3.889,3.889,0,1,1-2.96189,7.192Z" transform="translate(-124 -157)" fill="#ccc"/><path d="M692.54693,527.84,457.60687,431.0852a3.889,3.889,0,0,1,2.96188-7.192L695.50881,520.648a3.889,3.889,0,0,1-2.96188,7.192Z" transform="translate(-124 -157)" fill="#ccc"/><path d="M682.34489,552.61262,447.40483,455.85779a3.889,3.889,0,0,1,2.96188-7.192l234.94006,96.75484a3.889,3.889,0,0,1-2.96188,7.192Z" transform="translate(-124 -157)" fill="#ccc"/><path d="M567.73018,550.77248a84.70308,84.70308,0,0,0,14.09436-.43c4.21238-.49243,8.60066-1.17049,12.29309-3.38536a11.6831,11.6831,0,0,0,5.8212-8.83535,8.2218,8.2218,0,0,0-4.975-8.33661,9.80892,9.80892,0,0,0-9.95124,1.3943,12.959,12.959,0,0,0-4.44981,10.35166c.194,8.00444,6.52716,15.90938,13.55947,19.23894,7.92082,3.75025,18.73754.56318,20.76617-8.67189.42194-1.92082-2.19428-2.58835-3.288-1.35411a8.72086,8.72086,0,0,0,12.17216,12.44835l-2.94431-1.21255a20.9902,20.9902,0,0,0,14.43752,15.24852,19.46991,19.46991,0,0,0,5.37174.81856c2.2119.00621,4.48428,1.11646,6.59962,1.80561l15.24432,4.96639c2.24232.73052,3.665-2.67332,1.40427-3.40985l-13.80618-4.49787c-2.30073-.74955-4.59586-1.51967-6.90308-2.24893-1.64936-.52133-3.45545-.23905-5.176-.54579a17.28811,17.28811,0,0,1-13.5191-12.64511,1.86584,1.86584,0,0,0-2.9443-1.21254,5.04821,5.04821,0,0,1-7.02188-7.25421l-3.288-1.35411c-1.30866,5.95754-8.576,8.19323-13.81358,6.47055-5.85748-1.92656-10.892-7.53271-12.639-13.4049a10.8108,10.8108,0,0,1,.7867-8.60233,6.443,6.443,0,0,1,6.75034-3.30637,4.62264,4.62264,0,0,1,3.95027,5.07015,8.26891,8.26891,0,0,1-4.60472,6.264c-3.29662,1.7695-7.24432,2.21012-10.90575,2.61181a78.79791,78.79791,0,0,1-12.57356.3577c-2.35393-.11867-2.82641,3.54144-.44763,3.66136Z" transform="translate(-124 -157)" fill="#00b0ff"/><polygon points="695.76 290 695.76 583 171.76 583 171.76 237 253.76 237 329.37 290 695.76 290" fill="#fff"/><rect x="203.75984" y="528" width="214" height="17" fill="#00b0ff"/><rect x="203.75984" y="495" width="107" height="17" fill="#00b0ff"/><path d="M820.75984,741h-526V393h83.31555l75.61414,53H820.75984Zm-524-2h522V448H453.05843l-75.61414-53H296.75984Z" transform="translate(-124 -157)" fill="#3f3d56"/><polygon points="909.144 548.636 891.414 554.972 858.535 489.598 884.704 480.246 909.144 548.636" fill="#9f616a"/><path d="M986.63848,741.638l-.25872-.72291a23.659,23.659,0,0,1,14.30045-30.20571l34.91779-12.47929,8.21153,22.97656Z" transform="translate(-124 -157)" fill="#2f2e41"/><polygon points="793.848 566.172 775.019 566.172 766.062 493.546 793.851 493.547 793.848 566.172" fill="#9f616a"/><path d="M922.64961,741.42424l-60.71214-.00225v-.76791A23.63085,23.63085,0,0,1,885.5687,717.0236h.0015l37.08053.0015Z" transform="translate(-124 -157)" fill="#2f2e41"/><path d="M960.75907,259.18645l-8.18861-16.18053s-30.50532,7.64282-33.27853,25.32289Z" transform="translate(-124 -157)" fill="#00b0ff"/><polygon points="870.547 287.198 848.278 411.467 907.407 516.036 872.083 529.858 812.186 428.494 804.507 400.849 799.899 545.216 768.042 544.249 753.435 409.445 776.862 284.127 870.547 287.198" fill="#2f2e41"/><path d="M989.9385,388.055l-.77018-72.87145c0-30.88384-20.32611-53.64707-23.14362-55.75276-1.32569-4.231-3.70431-7.15557-7.0678-8.68606-6.77553-3.07086-15.06191.61412-15.41273.77989L918.685,260.84434l-.11662.34118c-.26371.77988-25.3466,83.08812-31.64469,103.74554-.8968,2.96364-1.41327,4.6599-1.41327,4.6599s.09758,4.77688.27275,12.20538c.556,25.54152,1.87167,82.43492,2.39814,84.82336,0,0,45.91642,6.96052,64.09763,6.96052,32.70714,0,53.48166-6.25865,53.94958-6.36587l.75066-.156Z" transform="translate(-124 -157)" fill="#3f3d56"/><circle cx="927.1367" cy="204.30312" r="33.60816" transform="translate(-108.38042 312.74381) rotate(-28.66301)" fill="#9f616a"/><path d="M930.86135,372.63255l-45.07817,9.16379c-.17517-7.4285-.27275-12.20538-.27275-12.20538s.51647-1.69626,1.41327-4.6599Z" transform="translate(-124 -157)" opacity="0.2"/><path d="M797.36935,363.02694a14.4225,14.4225,0,0,0,21.81522-3.63l50.24885,10.09416-9.99619-24.68652-46.18464-5.80088a14.50066,14.50066,0,0,0-15.88324,24.02326Z" transform="translate(-124 -157)" fill="#9f616a"/><polygon points="732.887 186.321 714.788 183.82 708.751 208.968 731.599 210.276 732.887 186.321" fill="#00b0ff"/><path d="M850.69724,338.47437l53.55238-5.94132,28.41215-50.40785a24.62075,24.62075,0,0,1,45.27595,18.28923l-.16338.62608-47.76016,69.68386-89.30252,4.40419Z" transform="translate(-124 -157)" fill="#3f3d56"/><path d="M955.76343,233.48271l-27.895,1.00127c-1.70035.061-6.31513-18.28939-6.91921-22.09617a10.3896,10.3896,0,0,0-10.838-8.40483c-2.08811.19724-7.35359-3.70388-12.78705-8.32323-10.31547-8.76987-9.77883-25.241,1.55269-32.65157q.46458-.30381.91188-.55481c7.14855-4.00112,15.511-5.34475,23.70251-5.44721,7.42579-.09288,15.06209.84177,21.60406,4.35653,11.72836,6.30122,17.97016,20.07116,18.51843,33.37376s-3.71631,26.31011-8.55563,38.71336" transform="translate(-124 -157)" fill="#2f2e41"/><path d="M1075,743H125a1,1,0,0,1,0-2h950a1,1,0,0,1,0,2Z" transform="translate(-124 -157)" fill="#cbcbcb"/></svg>';
    }

@endphp 

@endsection