@extends('adminlte::page')

@section('title', 'Edit Class')

@section('content_header')
    <h1>Edit Class</h1>
@stop

@section('content')

<form method="POST" action="/classes/update/{{ $class->id }}">
    @csrf

    <div class="card">
        <div class="card-body">

            <input type="text" name="name" value="{{ $class->name }}" class="form-control" required>

        </div>

        <div class="card-footer">
            <button class="btn btn-success">Update</button>
        </div>
    </div>

</form>

@stop