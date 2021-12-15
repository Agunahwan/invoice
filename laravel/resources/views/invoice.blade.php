@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Invoice</h1>
@stop

@section('content')
<div class="card card-default">
    <div class="card-body">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="form-group">
                            <a class="btn btn-success" onclick="addData()">Add Invoice</a>
                        </div>
                        <div class="form-group">
                            <table id="dataTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>
                                            Invoice ID
                                        </th>
                                        <th>
                                            Subject
                                        </th>
                                        <th>
                                            Issue Date
                                        </th>
                                        <th>
                                            Due Date
                                        </th>
                                        <th>
                                            SubTotal
                                        </th>
                                        <th class="act-column">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
{!! Html::script('public/js/main.js') !!}
<script>
    var local='{{ URL::to('/') }}';
</script>
{!! Html::script('public/js/invoice.js') !!}
@stop
