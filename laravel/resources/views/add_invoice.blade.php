@extends('adminlte::page')

@section('title', 'Add Invoice')

@section('content_header')
<h1>{{ $header }}</h1>
@stop

@section('content')
<div class="row">
    <!-- left column -->
    <div class="col-md-12">
        <form id="frmSave" class="form-horizontal">
            <!-- general form elements -->
            <div class="card card-default">
                <!-- form start -->
                <div class="card-body">
                    <?php if (isset($failed) && $failed==1) { ?>
                    <div class="alert alert-danger fade in">
                        <a href="#" class="close" data-dismiss="alert">&times;</a> Data gagal disimpan
                    </div>
                    <?php } ?>

                    {{ csrf_field() }}
                    <input type="hidden" id="InvoiceId" name="InvoiceId" value="{{ $invoice->id }}" />
                    <input type="hidden" id="CompanyId" name="CompanyId" value="{{ $companyId }}" />
                    <input type="hidden" id="ClientIdSelected" name="ClientIdSelected"
                        value="{{ $invoice->client_id }}" />
                    <input type="hidden" id="Tax" name="Tax" value="{{ $tax }}" />

                    <div class="form-group row">
                        <div class="col-sm-2">
                            <label for="InvoiceId" class="control-label">Invoice ID</label>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control input-large" id="InvoiceId" placeholder="Invoice ID"
                                name="InvoiceId" value="{{ $formattedId }}" readonly />
                        </div>

                        <div class="col-sm-2">
                            <label for="InvoiceFor" class="control-label">Invoice For</label>
                        </div>
                        <div class="col-sm-4">
                            <select id="InvoiceFor" name="InvoiceFor" class="selectpicker form-control"
                                data-live-search="true" title="Pilih Client ..." {{ $isView==1 ? ' disabled' : '' }}>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-2">
                            <label for="IssueDate" class="control-label">Issue Date</label>
                        </div>
                        <div class="col-sm-4">
                            <input type="date" class="form-control input-large" id="IssueDate" name="IssueDate"
                                value="{{ $invoice->issue_date }}" {{ $isView==1 ? ' readonly' : '' }} />
                        </div>

                        <div class="col-sm-2">
                            <label for="DueDate" class="control-label">Due Date</label>
                        </div>
                        <div class="col-sm-4">
                            <input type="date" class="form-control input-large" id="DueDate" name="DueDate"
                                value="{{ $invoice->due_date }}" {{ $isView==1 ? ' readonly' : '' }} />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-2">
                            <label for="Subject" class="control-label">Subject</label>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control input-large" id="Subject" placeholder="Subject"
                                name="Subject" value="{{ $invoice->subject }}" {{ $isView==1 ? ' readonly' : '' }} />
                        </div>
                    </div>

                    @if($isView==0)
                    <div class="form-group">
                        <a class="btn btn-success" onclick="addItem()">Add Item</a>
                    </div>
                    @endif

                    <div class="form-group">
                        <table id="dataItem" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        Item type
                                    </th>
                                    <th>
                                        Description
                                    </th>
                                    <th>
                                        Quantity
                                    </th>
                                    <th>
                                        Unit Price
                                    </th>
                                    <th>
                                        Amount
                                    </th>
                                    @if($isView==0)
                                    <th class="act-column">Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="list"></tbody>
                        </table>
                    </div>

                    <div class="card bg-light">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <label for="Subtotal" class="control-label">Subtotal</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control input-large" id="Subtotal" name="Subtotal"
                                        placeholder="Subtotal" value="{{ $invoice->subtotal }}" readonly />
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <label for="TotalTax" class="control-label">Tax ({{ $tax }}%)</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control input-large" id="TotalTax" name="TotalTax"
                                        placeholder="Tax ({{ $tax }}%)" value="{{ $invoice->tax }}" readonly />
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <label for="TotalPayments" class="control-label">Total Payments</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control input-large" id="TotalPayments"
                                        name="TotalPayments" placeholder="Total Payments"
                                        value="{{ $invoice->total_payments }}" readonly />
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <label for="Payments" class="control-label">Payments</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="number" min="1" step="any" class="form-control input-large"
                                        id="Payments" name="Payments" placeholder="Payments"
                                        value="{{ $invoice->payments }}" onchange="onChangePayment()"
                                        oninput="onChangePayment()" {{ $isView==1 ? ' readonly' : '' }} />
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <label for="AmountDue" class="control-label">Amount Due</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control input-large" id="AmountDue"
                                        name="AmountDue" placeholder="Amount Due" readonly
                                        value="{{ $invoice->amount_due }}" />
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    @if($isView==0)
                    <input type="button" value="Save" class="btn btn-primary" onclick="onSave()" />&nbsp;
                    @endif
                    <a asp-action="Index">
                        <input type="button" value="Cancel" class="btn btn-default" onclick="onCancel()" />
                    </a>
                </div>
            </div>
            <!-- /.card -->
        </form>
    </div>
    <!--/.col (left) -->
    <!-- right column -->
</div>
<!-- /.row -->

<div id="item-dialog" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="message-title">Add Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="Item" class="control-label">Item</label>
                    </div>
                    <div class="col-sm-8">
                        <select id="Item" name="Item" class="selectpicker form-control" data-live-search="true"
                            title="Pilih Item ...">
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="Quantity" class="control-label">Quantity</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="number" min="0" class="form-control input-large" id="Quantity" name="Quantity" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="button" value="Save" class="btn btn-primary" onclick="onSaveItem()" />&nbsp;
                <button type="button" id="btn-message-common-close" class="btn btn-default pull-left"
                    data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Popup Message -->
<div class="modal fade" id="modal-message" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="message-data-title" class="modal-data-title">Title</h4>
                <button type="button" class="close" onclick="onClose()" aria-label="Close">
                    <span aria-hidden="true">??</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="redirectUrl" name="redirectUrl" value="" />
                <div class="row form-group">
                    <div class="col-md-12">
                        <div class="alert-success hide" role="alert" id="message-data-label">
                            <label id="message-data-body">Message</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" onclick="onClose()">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
{!! Html::script('public/js/main.js') !!}
<script>
    var local='{{ URL::to('/') }}';
    var invoiceId={{ $invoice->id }};
    @if(isset($invoice_detail))
    var invoiceItems = @json($invoice_detail);
    @else
	var invoiceItems = [];
	@endif
    var items = [];
    var isView = {{ $isView }};
</script>
{!! Html::script('public/js/form_invoice.js') !!}
@stop
