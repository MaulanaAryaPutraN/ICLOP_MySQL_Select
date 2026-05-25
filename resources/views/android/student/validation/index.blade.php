@extends('android.layouts.main')
@section('main-content')

	<main class="col-md-9">
		<div class="content" id="start-learning">
        	<p style="font-size: 24px; font-weight: 500; color: #34364A;">Topic Validation Result</p>

        	<div>
        		
        		<table class="" style="width: 100%">
        			
        			<thead>
        				<tr style="background-color: #1A79E333 !important; color: #3F3F46">
        					<th style="padding-top: 22px; padding-left: 44px; padding-bottom: 22px">Topic</th>
        					<th>Submission</th>
        					<th>Duration</th>
        					<th>Validation</th>
        					<th>Action</th>
        				</tr>
        			</thead>
        			<tbody>
        				@foreach ( $dt_keseluruhan AS $isi )
        				<tr>
        					<td style="padding-top: 7px; padding-left: 20px">{{ $isi->topic->title }}</td>
        					<td>
        						@php 

        							$duration = 0;
        							$avg = 0;
        						@endphp
        						@foreach ( $isi->submit AS $index => $isi_sb )

        						@php 

        							$duration += $isi_sb->duration;
        						@endphp
        						<small>{{ ($index + 1) .' '. $isi_sb->task->task_name }}</small><br>
        						@endforeach
        					</td>

        					@php 

        						if ( $duration > 0 ) {

        							$avg = $duration / count( $isi->submit );
        							$avg = number_format( $avg, 2 );
        						}

        					@endphp
        					<td>Total {{ $duration }} minutes,<br>Average {{ $avg }} minutes</td>
        					<td>
        						<label class="badge badge-success">{{ $isi->status }}</label>
        					</td>
        					<td>
        						<a href="{{ url('android23/detail/'. $isi->android_topic_id) }}" class="btn btn-sm btn-primary">Detail</a>
        					</td>
        				</tr>
        				@endforeach
        			</tbody>
        		</table>

        	</div>
        <div>
	</main>

@endsection