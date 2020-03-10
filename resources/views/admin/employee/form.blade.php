@extends('adminlte::page')

@section('title', 'Employees')

@section('content_header')
    <h1>Employees Form</h1>
@stop
@section('content')
    <div id="app" style="padding:20px">
        <form-employee
            :employee=" @if($employee){{ json_encode($employee)}} @else {{ json_encode(['id' => null]) }} @endif"
            :positions="{{ json_encode($positions) }}"
            :parents="@if($parents) {{ json_encode($parents) }} @endif"></form-employee>
    </div>
@stop
@section('js')
    <script src="{{ asset('css/app.css') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
@stop
