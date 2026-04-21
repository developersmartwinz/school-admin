@extends('adminlte::page')

@section('title', 'Edit Section')

@section('content_header')
    <h1>Edit Section</h1>
@stop

@section('content')

<form method="POST" action="/sections/update/{{ $section->id }}">
    @csrf

    <div class="card">
        <div class="card-body">

            <div class="form-group">
                <label>Section Name</label>
                <input type="text" name="name" value="{{ $section->name }}" class="form-control">
            </div>

            <div class="form-group">
                <label>Class</label>
                <select name="class_id" class="form-control">
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}"
                            {{ $class->id == $section->class_id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="card-footer">
            <button class="btn btn-success">Update</button>
        </div>
    </div>

</form>

@stop