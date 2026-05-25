@extends('admin/admin')

@section('content')

<div class="row">

    <div class="col-12">

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">Validation Result Detail [Student name: {{ $student['name'] }}, Topic: {{ $topic['name'] }} ]</h3>

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

                <div class="row">

                    <div class="col-md-12">

                        <table class="table table-bordered table-hover">

                            <thead>

                                <tr class="text-center">

                                    <th>Submit Number</th>

                                    <th>Submit Total</th>

                                    <th>Test File</th>

                                    <th>Topic Name</th>

                                    <th>Result</th>

                                    <th>Report</th>

                                    <th>Duration</th>

                                    <th>Exec Time</th>

                                    <th>History</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php $x = count($entities); ?>

                                @foreach($entities as $index => $entity)

                                <tr>

                                    <td>{{ $entity['submissions_number'] }}</td>

                                    <td>{{ $entity['submissions_total'] }}</td>

                                    <td>{{ $testfile[0]['filename'] }}</td>

                                    <td>{{ $entity['topic'] }}</td>

                                    <td>{{ $entity['checkstat'] }}</td>

                                    <td>{!! nl2br(e($entity['report'])) !!}</td>

                                    <td>00:50:22</td>

                                    <td>{{ $entity['created_at'] }}</td>

                                    <td class="text-center">

                                        <div class="btn-group">

                                            <a class="btn  btn-warning " href="{{ URL::to('/admin/uiresview/'.$student['id'].'/'.$topic['id']) }}">

                                                <i class="fa fa-eye"></i>&nbsp;History</a>

                                        </div>

                                    </td>

                                </tr>

                                @endforeach

                            </tbody>

                        </table>





                    </div>

                </div>

            </div>

            <div class="card-footer">

                <input type="button" value="Back" onclick="history.back()" class="btn btn-outline-info">

                <!--

            <a href="{{ URL::to('admin/topic') }}" class="btn btn-outline-info">Back</a>

          -->

            </div>

        </div>

    </div>

</div>



@endsection
