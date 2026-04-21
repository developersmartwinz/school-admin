@extends('adminlte::page')

@section('title', 'Edit Time Slot')

@section('content')

<form method="POST" action="/time-slots/update/{{ $data->id }}">
@csrf

<div class="card">
    <div class="card-body">

        <div class="row">

            <div class="col-md-4">
                <label>Class</label>
                <select name="class_id" class="form-control">
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}"
                            {{ $data->class_id == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label>Start Time</label>
                <input type="time" name="start_time" value="{{ $data->start_time }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label>End Time</label>
                <input type="time" name="end_time" value="{{ $data->end_time }}" class="form-control">
            </div>

            <div class="col-md-4 mt-2">
                <label>Type</label>
                <select name="type" class="form-control">
                    <option value="class" {{ $data->type=='class'?'selected':'' }}>Class</option>
                    <option value="break" {{ $data->type=='break'?'selected':'' }}>Break</option>
                </select>
            </div>

            <div class="col-md-4 mt-2">
                <label>Order</label>
                <input type="number" name="order" value="{{ $data->order }}" class="form-control">
            </div>

        </div>

    </div>

    <div class="card-footer text-end">
        <button class="btn btn-primary">Update</button>
    </div>
</div>

</form>
@stop