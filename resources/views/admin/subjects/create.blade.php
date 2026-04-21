@extends('adminlte::page')

@section('content')

<form method="POST" action="/subjects/store">
@csrf

<div class="card">
    <div class="card-body">

        <input name="name" placeholder="Subject Name"
               class="form-control" required>

    </div>

    <div class="card-footer">
        <button class="btn btn-success">Save</button>
    </div>
</div>

</form>

@stop