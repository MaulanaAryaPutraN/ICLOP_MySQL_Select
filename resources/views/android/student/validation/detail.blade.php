@extends('android.layouts.main')
@section('main-content')

	<main class="col-md-9">
		<div class="content" id="start-learning">
        	<p style="font-size: 24px; font-weight: 500; color: #34364A;">Topic Validation Result</p>

        	<div>
        		
        		<table class="" style="width: 100%">
        			
        			<thead>
        				<tr style="background-color: #1A79E333 !important; color: #3F3F46">
        					<th style="padding-top: 22px; padding-left: 44px; padding-bottom: 22px">No</th>
        					<th>Task Name</th>
        					<th>Test Validation</th>
        					<th>Skor</th>
        					<th>Duration</th>
        				</tr>
        			</thead>

                                <tbody>
                                        @foreach ( $dt_keseluruhan['submits'] AS $index => $isi )
                                        <tr>
                                                <td style="text-align: center;">{{ $index + 1 }}</td>
                                                <td>{{ $isi['info']->task_name }}</td>
                                                <td style="padding: 10px">
                                                        @foreach ( $isi['testcase'] AS $index_tc => $tc )
                                                        <div class="row text-sm" style="font-size: 10px;">
                                                            <div class="col-md-12">
                                                                @php 

                                                                    $color = "";
                                                                    $icon = "";
                                                                    if ( $tc->status_validate == "failed" ) {

                                                                        $color = "danger";
                                                                        $icon = '<i class="fas fa-times"></i>';
                                                                    } else if ( $tc->status_validate == "passed" ) {

                                                                        $color = "success";
                                                                        $icon = '<i class="fas fa-check"></i>';
                                                                    }   
                                                                
                                                                @endphp 

                                                                <b class="text-{{ $color }}">@php echo $icon.' '.$tc->status_validate.' : '. $tc->android_testcase->case @endphp</b>
                                                            </div>
                                                            
                                                        </div>
                                                        @endforeach
                                                </td>
                                                <td><h1 style="font-size: 24px; color: #00C2FF">{{ number_format($isi['persentage'], 2) }}</h1></td>
                                                <td><span style="font-size: 14px">{{ $isi['info']->duration }} minutes</span></td>
                                        </tr>
                                        @endforeach
                                </tbody>
        			
        		</table>

        	</div>
        <div>
	</main>

@endsection