@extends('android/teacher/home')
@section('content')


<style>
    .custom-control.overflow-checkbox .overflow-control-input {
        display: none;
    }

    .custom-control.overflow-checkbox .overflow-control-input:checked~.overflow-control-indicator::after {
        -webkit-transform: rotateZ(45deg) scale(1);
        -ms-transform: rotate(45deg) scale(1);
        transform: rotateZ(45deg) scale(1);
        top: -6px;
        left: 5px;
    }

    .custom-control.overflow-checkbox .overflow-control-input:checked~.overflow-control-indicator::before {
        opacity: 1;
    }

    .custom-control.overflow-checkbox .overflow-control-input:disabled~.overflow-control-indicator {
        opacity: .5;
        border: 2px solid #ccc;
    }

    .custom-control.overflow-checkbox .overflow-control-input:disabled~.overflow-control-indicator:after {
        border-bottom: 4px solid #ccc;
        border-right: 4px solid #ccc;
    }

    .custom-control.overflow-checkbox .overflow-control-indicator {
        border-radius: 3px;
        display: inline-block;
        position: absolute;
        top: 4px;
        left: 0;
        width: 16px;
        height: 16px;
        border: 2px solid #00909e;
    }

    .custom-control.overflow-checkbox .overflow-control-indicator::after {
        content: '';
        display: block;
        position: absolute;
        width: 16px;
        height: 16px;
        -webkit-transition: .3s;
        -o-transition: .3s;
        transition: .3s;
        -webkit-transform: rotateZ(90deg) scale(0);
        -ms-transform: rotate(90deg) scale(0);
        transform: rotateZ(90deg) scale(0);
        width: 10px;
        border-bottom: 4px solid #00909e;
        border-right: 4px solid #00909e;
        border-radius: 3px;
        top: -2px;
        left: 2px;
    }

    .custom-control.overflow-checkbox .overflow-control-indicator::before {
        content: '';
        display: block;
        position: absolute;
        width: 16px;
        height: 16px;
        -webkit-transition: .3s;
        -o-transition: .3s;
        transition: .3s;
        width: 10px;
        border-right: 7px solid #fff;
        border-radius: 3px;
        -webkit-transform: rotateZ(45deg) scale(1);
        -ms-transform: rotate(45deg) scale(1);
        transform: rotateZ(45deg) scale(1);
        top: -4px;
        left: 5px;
        opacity: 0;
    }


    .cc-selector input{
        margin:0;padding:0;
        -webkit-appearance:none;
        -moz-appearance:none;
                appearance:none;
    }

    .cc-selector-2 input{
        position:absolute;
        z-index:999;
    }

    .github{background-image:url(https://cdn-icons-png.flaticon.com/512/25/25231.png);}
    .zip{background-image:url(https://cdn-icons-png.flaticon.com/128/5721/5721939.png);}

    .cc-selector-2 input:active +.drinkcard-cc, .cc-selector input:active +.drinkcard-cc{opacity: .9;}
    .cc-selector-2 input:checked +.drinkcard-cc, .cc-selector input:checked +.drinkcard-cc{
        -webkit-filter: none;
        -moz-filter: none;
                filter: none;
    }
    .drinkcard-cc{
        cursor:pointer;
        background-size:contain;
        background-repeat:no-repeat;
        display:inline-block;
        width:100px;height:70px;
        -webkit-transition: all 100ms ease-in;
        -moz-transition: all 100ms ease-in;
                transition: all 100ms ease-in;
        -webkit-filter: brightness(1.8) grayscale(1) opacity(.7);
        -moz-filter: brightness(1.8) grayscale(1) opacity(.7);
                filter: brightness(1.8) grayscale(1) opacity(.7);
    }
    .drinkcard-cc:hover{
        -webkit-filter: brightness(1.2) grayscale(.5) opacity(.9);
        -moz-filter: brightness(1.2) grayscale(.5) opacity(.9);
                filter: brightness(1.2) grayscale(.5) opacity(.9);
    }

    /* Extras */
    .event:visited{color:#888}
    .event{color:#444;text-decoration:none;}
    .cc-selector-2 input{ margin: 5px 0 0 12px; }
    .cc-selector-2 label{ margin-left: 7px; }
    span.cc{ color:#6d84b4 }
</style>

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
                		<div class="card">
                			<div class="card-body">
                				
                				<div class="row">
                					
                					<div class="col-md-4" style="border-right: 1.4px solid #e0e0e0">
                						
                						<div class="text-center">
                							<img src="https://pheirlaboratory.weebly.com/uploads/1/1/8/4/118426356/icon-study-skills2_1_orig.png" style="width: 100px;">

                							<h4 style="margin: 0px">{{ $submit->user->name }}</h4>
                							<b>Student - ICLOP</b>
                						</div>


                						<div class="form-group">
                							<small>Email</small><br>
                							<b>{{ $submit->user->email }}</b>
                						</div>

                						<div class="form-group">
                							<small>Role</small><br>
                							<b>Student / {{ date('d F Y H.i', strtotime($submit->user->created_at)) }}</b>
                						</div>


                					</div>
                					<div class="col-md-8">
                						<h5>Task Preview - <i>#{{ $submit->task->task_name }}</i></h5>
                						
                						<div class="row">
                							<div class="col-md-9">
                								
                								<p style="margin: 0px">Submitted {{ date('d F Y H.i', ( $submit->task->created_at )) }}</p>

		                						@php 

		                						$label = "danger";
		                						if ( $submit->validator != "process" ) {

		                							$label = "success";
		                						}

		                						@endphp 
		                						<p class="text-sm">Status : <label class="badge badge-{{ $label }}">{{ $submit->validator }}</label></p>

		                						<small><b><i class="fas fa-paper-plane"></i> Comment</b></small>
		                						"{{ $submit->comment }}"	
                							</div>
                							<div class="col-md-3 text-center">
                								<small>Score target <span id="target">-</span></small>
                								<div id="score"></div>
                							</div>
                						</div>

                						<hr>

                						<div class="">
                							<b>Evidence</b>
                							<img src="{{ asset('android23/submission/'. $submit->upload) }}" alt="preview" style="object-fit: contain; width: 100%; height: 150px; background: #f1f1f1; border-radius: 5px">
                							<small>{{ $submit->upload }}</small>
                						</div>

                						<hr>

                						<!-- Checkbox -->
                						<div class="row">
                							<div class="col-md-6">
                								<h4>Student Answer</h4>
                								@php 
	                                            foreach ( $testcase AS $index => $isi ) :

	                                            // checked status
	                                            $checked = "";
	                                            if ( $isi->status == "passed" ){

	                                            	$checked = 'checked=""';
	                                            }

	                                                
	                                                
	                                            @endphp 

	                                            <label class="custom-control overflow-checkbox">
	                                                <input type="checkbox" name="task[]" class="overflow-control-input" value="{{ $isi->id }}" {{ $checked }}>
	                                                <span class="overflow-control-indicator"></span>
	                                                <span class="overflow-control-description">{{ $isi->case }}</span>
	                                            </label>

	                                            @php endforeach; @endphp
                							</div>
                							<div class="col-md-6">
                								<h4>Lecturer - Validation</h4>
                								@php 
	                                            foreach ( $testcase AS $index => $isi ) :


	                                            // checked status
	                                            $checked = "";
	                                            if ( $isi->status_validate == "passed" ){

	                                            	$checked = 'checked=""';
	                                            }

	                                                
	                                                
	                                            @endphp 

	                                            <label class="custom-control overflow-checkbox">
	                                                <input type="checkbox" name="task[]" onchange="penilaian('{{ $isi->id }}', '{{ $isi->android_submit_id }}')" class="overflow-control-input" value="{{ $isi->id }}" {{ $checked }}>
	                                                <span class="overflow-control-indicator"></span>
	                                                <span class="overflow-control-description">{{ $isi->case }}

                                                        <br><small>Bobot Testcase : {{ $isi->score }}</small>
                                                    </span>
	                                            </label>

	                                            @php endforeach; @endphp
                							</div>
                						</div>
                					</div>
                				</div>


                			</div>
                		</div>
                	</div>
                </div>

            </div>
        </div>
	</div>
</div>


<script type="text/javascript">


    function pointerkini( submit_id ) {


        $.ajax({

            type: "GET",
            url : "{{ url('teacher/android23/load-point') }}/" + submit_id,
            dataType: "json",
            success: function ( res ) {

                console.log(res);
                let color;
                if ( res.point == res.bobot ) {

                    color = "text-primary";
                } else {

                    color = "text-main";
                }

                let html = `<h2 class="${color}">${res.point}</h2>`;

                $('#score').html( html ).hide().fadeIn();
                $('#target').text(res.bobot);
            }
        });
    }


    function penilaian( id, submit_id ) {

        $.ajax({

            type: "GET",
            url : "{{ url('teacher/android23/onvalidate/') }}/" + id + "/" + submit_id,
            success: function() {

                pointerkini( submit_id );
            }
        });
    }


    pointerkini('{{ $submit->id }}');
</script>
@endsection