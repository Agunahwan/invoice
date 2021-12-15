@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Invoice</h1>
@stop

@section('content')
Form Add
@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
    var local='{{ URL::to('/') }}';
</script>
{!! Html::script('public/js/invoice.js') !!}
@stop
