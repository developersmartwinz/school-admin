@extends('adminlte::page')

@section('title', 'Add Class')

@section('content_header')
    <h1>Add Class</h1>
@stop

@section('content')

<form method="POST" action="/classes/store">
    @csrf

    <div class="card">
        <div class="card-body">

            <div class="form-group">
                <label>Class Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

        </div>

        <div class="card-footer">
            <button class="btn btn-primary">Save</button>
        </div>
    </div>

</form>

@stop