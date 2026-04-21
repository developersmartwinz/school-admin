@extends('adminlte::page')

@section('title', 'Create Time Slot')

@section('content')

<form method="POST" action="/time-slots/store">
@csrf

<div class="card">
    <div class="card-body">

        <div class="row">

            <div class="col-md-4">
                <label>Class</label>
                <select name="class_id" class="form-control">
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label>Start Time</label>
                <input type="time" name="start_time" class="form-control">
            </div>

            <div class="col-md-4">
                <label>End Time</label>
                <input type="time" name="end_time" class="form-control">
            </div>

            <div class="col-md-4 mt-2">
                <label>Type</label>
                <select name="type" class="form-control">
                    <option value="class">Class</option>
                    <option value="break">Break</option>
                </select>
            </div>

            <div class="col-md-4 mt-2">
                <label>Order</label>
                <input type="number" name="order" class="form-control">
            </div>

        </div>

    </div>

    <div class="card-footer text-end">
        <button class="btn btn-success">Save</button>
    </div>
</div>

</form>
@stop