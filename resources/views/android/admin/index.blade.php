@extends('admin/admin')

@section('content')

<div class="row">

    <div class="col-12">

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">Validation Result of Learning Result</h3>

                <div class="card-tools">



                </div>

            </div>

            <div class="card-body">

                @if (Session::has('message'))

                <div id="alert-msg" class="alert alert-success alert-dismissible">

                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>

                    {{ Session::get('message') }}

                </div>

                @endif

                {{ Form::open(['method' => 'GET']) }}

                <div class="form-group">

                    {!! Form::label('student', 'Student:') !!}

                    <select class="form-control" id="stdList" name="stdList" onChange='this.form.submit();'>

                        @foreach($items as $item)

                        <option value="{{ $item->id }}" {{ $item->id == $filter ? 'selected' : '' }}>{{ '[ '.$item->classname.' / '.$item->teacher.' ] '.$item->name }}</option>

                        @endforeach

                    </select>

                    {{ Form::close() }}

                    <div class="row">

                        <div class="col-md-12">

                            <table class="table table-bordered table-hover">

                                <thead>

                                    <tr class="text-center">

                                        <th>Topic Name</th>

                                        <th>Student Result</th>

                                        <th>Validation Result</th>

                                        <th>Validation Detail</th>

                                        <th>Status</th>

                                        <th>Action</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($entities as $entity)

                                    <tr>

                                        <td>{{ $entity['id'].'. '.$entity['name'] }}</td>

                                        <td>{{ $entity['passed'] }} Task(s) Passed, {{ $entity['failed'] }} Failed</td>

                                        <td>

                                            @if ($entity['vpassed']=='')

                                            WAITING

                                            @else

                                            {{ $entity['vpassed'] }} Task(s) Passed, {{ $entity['vfailed'] }} Failed

                                            @endif

                                        </td>

                                        <td>{!! nl2br($entity['checkresult']) !!}</td>

                                        <td>{{ $entity['checkstat'] }}</td>

                                        <td class="text-center">

                                            <div class="btn-group">

                                                <a class="btn  btn-info " href="{{ URL::to('/admin/studentres/'.$filter.'/'.$entity['id']) }}"><i class="fa fa-eye"></i>&nbsp;Show Detail</a>

                                            </div>

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



    @endsection
