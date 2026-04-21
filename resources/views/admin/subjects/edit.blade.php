@extends('adminlte::page')

@section('content')

<form method="POST" action="/subjects/update/{{ $subject->id }}">
@csrf

<div class="card">
    <div class="card-body">

        <input name="name"
               value="{{ $subject->name }}"
               class="form-control" required>

    </div>

    <div class="card-footer">
        <button class="btn btn-success">Update</button>
    </div>
</div>

</form>

@stop