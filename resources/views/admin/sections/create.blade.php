@extends('adminlte::page')

@section('title', 'Add Section')

@section('content_header')
    <h1>Add Section</h1>
@stop

@section('content')



<form method="POST" action="/sections/store">
    @csrf

    <div class="card">
        <div class="card-body">

            <div class="form-group">
                <label>Section Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Select Class</label>
                <select name="class_id" class="form-control" required>
                    <option value="">Select</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="card-footer">
            <button class="btn btn-primary">Save</button>
        </div>
    </div>

</form>

@stop