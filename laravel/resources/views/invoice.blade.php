@extends('adminlte::page')

@section('title', 'Invoice')

@section('content_header')
<h1>Invoice</h1>
@stop

@section('content')
<div class="row">
    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-default">
            <!-- /.box-header -->
            <div class="card-body">
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

<!-- Confirmation Delete -->
<div id="dialog-confirmation" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="message-title">Confirmation</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row form-group">
                    <div class="col-md-12">
                        <div role="alert" id="message-label">
                            Apakah kamu yakin ingin menghapus data ini?
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="id" name="id" />
                <input type="hidden" id="url" name="url" />
                <input type="hidden" id="redirectUrl" name="redirectUrl" value="" />
                <input type="submit" value="Hapus" class="btn btn-danger" onclick="onDelete()" />
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal" id="btn-no">Batal</button>
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
