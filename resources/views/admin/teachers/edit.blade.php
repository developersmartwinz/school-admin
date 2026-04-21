@extends('adminlte::page')

@section('title', 'Edit Teacher')

@section('content_header')
    <h1>Edit Teacher</h1>
@stop

@section('content')

<form method="POST" action="/teachers/update/{{ $teacher->id }}">
@csrf

<div class="card shadow-sm">

    <div class="card-body">

        <div class="row">

            <!-- NAME -->
            <div class="col-md-6">
                <label>Name</label>
                <input type="text" name="name" value="{{ $teacher->user->name }}" class="form-control" required>
            </div>

            <!-- EMAIL -->
            <div class="col-md-6">
                <label>Email</label>
                <input type="email" name="email" value="{{ $teacher->user->email }}" class="form-control" required>
            </div>

            <!-- PASSWORD -->
            <div class="col-md-6 mt-3">
                <label>Password (Leave blank to keep same)</label>
                <input type="password" name="password" class="form-control">
            </div>

        </div>

    </div>

    <div class="card-footer text-end">
        <button class="btn btn-primary">
            <i class="fas fa-save"></i> Update
        </button>
    </div>

</div>

</form>

@stop