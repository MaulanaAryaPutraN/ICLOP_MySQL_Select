@extends('android/teacher/home')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Overview</h3>
            </div>
            <div class="card-body">
            	<div class="row">
            		<div class="col-md-3">
            			<small>Nama Mahasiswa</small>
            			<h4 style="margin: 0px">{{ $user->name }}</h4>
            		</div>
            		<div class="col-md-3">
            			<small>Enroll Pada</small>
            			<h4 style="margin: 0px">{{ date('d M Y H.i', strtotime($enrollment->created_at)) }}</h4>
            		</div>
            		<div class="col-md-2">
            			<small>Status Materi</small>
            			<h4 style="margin: 0px">{{ $enrollment->status }}</h4>
            		</div>
            		<div class="col-md-2">
            			<small>Autograding</small>
            			<h4 style="margin: 0px">{{ number_format($NA, 2) }}</h4>
            		</div>
            	</div>

            	@if ( $submission->count() > 0 )


            	@php 
            	$info = $submission->first();
            	@endphp
            	<hr>
            	<div class="row">
            		<div class="col-md-1 text-center">
            			<i class="fas fa-book-open" style="font-size: 30px; margin-top: 10px"></i>
            		</div>
            		<div class="col-md-6">
            			<small>Pengumpulan Final Submission</small>

            			@php 

            				if ( $info->tipe == "zip" ) {

            					$url = asset('android23/final-submission/'. $info->userfile);
            				} else {

            					$url = $info->userfile;
            				}

            			@endphp
            			<h5><a href="{{ $url }}" target="_blank">{{ $url }}</a></h5>
            		</div>
            		<div class="col-md-5">
            			<small>Konfirmasi Penyelesaian Kelas <b>"Pastikan telah memeriksa pengumpulan mahasiswa"</b></small>

            			<?php

            				$color = "btn-warning";
            				if ( $enrollment->status == "complete" ) {

            					$color = "btn-success disabled";
            				}
            			?>
            			<a href="{{ url('teacher/android23/overview-student-confirm/'. $enrollment->android_topic_id.'/'.$enrollment->user_id.'/'. $enrollment->id) }}" onclick="return confirm('Apakah anda ingin mengkonfirmasi mahasiswa ini telah menyelesaikan hingga tuntas ?')" class="btn <?php echo $color ?> btn-sm">Konfirmasi Pengerjaan</a>
            		</div>
            	</div>
            	@endif
            </div>
        </div>
    </div>
</div>



<div class="row justify-content-center">
	<div class="col-md-11">
		<div class="card">
			<div class="card card-body">
				<table class="table table-bordered table-hover" border="0">
					<thead>
						<tr>
							<th>#</th>
							<th>Task Name</th>
							<th>Status</th>
							<th>Exec Time</th>
							<th>Duration</th>
							<th>Percentage</th>
							<th>Test Case</th>
							<th>Result</th>
							
						</tr>
					</thead>
					<tbody>
						@foreach ( $dt_keseluruhan AS $index => $isi )

						@php 
							$jumlah = count( $isi->testcase );

							$passed = 0;
							foreach ( $isi->testcase AS $det ){

								if ( $det->status_validate == "passed" ) {
									$passed++;
								}
							}
						@endphp
						<tr>
							<td rowspan="{{ count($isi->testcase) + 1 }}" style="vertical-align: middle;">{{ $index + 1 }}</td>
							<td rowspan="{{ count($isi->testcase) + 1 }}" style="vertical-align: middle;">
								{{ $isi->task->task_name }}
								<br>
								<small>
									<b>Bobot Task : {{ $isi->bobot }}</b><br>
									<b>Skor mahasiswa yang diperoleh : +{{ number_format($isi->nilai, 2) }}</b><br>
								</small>
							</td>
							<td rowspan="{{ count($isi->testcase) + 1 }}" style="vertical-align: middle;">{{ $isi->validator }}</td>
							<td rowspan="{{ count($isi->testcase) + 1 }}" style="vertical-align: middle;">{{ $isi->created_at }}</td>
							<td rowspan="{{ count($isi->testcase) + 1 }}" style="vertical-align: middle;">{{ $isi->duration }}</td>
							<td rowspan="{{ count($isi->testcase) + 1 }}" style="vertical-align: middle;">{{ number_format($passed / $jumlah * 100, 2) }}%</td>
						</tr>
							
							@foreach ( $isi->testcase AS $det )
							<tr>
								<td>{{ explode(':', $det->case)[0] }}</td>
								<td>
									@php 
										$icon = '<i class="fas fa-check"></i>';
										$color = "text-primary";

										if ( $det->status_validate == "failed" ) {

											$icon = '<i class="fas fa-times"></i>';
											$color = "text-danger";
										} else {


											$passed++;
										}

										echo '<label class="text-sm '.$color.'">'.$icon.' '.$det->status_validate.'</label>';
									@endphp
								</td>
							</tr>
							@endforeach
						
						@endforeach
					</tbody>
				</table>

			</div>
		</div>
	</div>
</div>



@endsection