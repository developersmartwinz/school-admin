@extends('adminlte::page')

@section('title', 'Timetable')

@section('content_header')
<h1>Timetable</h1>
@stop

@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between">
        <form class="d-flex gap-2">

            <select name="class_id" class="form-control">
                <option value="">All Classes</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>

            <select name="day" class="form-control">
                <option value="">All Days</option>
                <option>Monday</option>
                <option>Tuesday</option>
                <option>Wednesday</option>
                <option>Thursday</option>
                <option>Friday</option>
                <option>Saturday</option>
            </select>

            <button class="btn btn-primary">Filter</button>
        </form>

        <a href="/timetable/create" class="btn btn-success">
            <i class="fas fa-plus"></i> Add
        </a>
    </div>

    <div class="card-body">

        <table class="table table-bordered text-center">
            <thead class="bg-dark text-white">
                <tr>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Teacher</th>
                    <th>Subject</th>
                    <th>Day</th>
                    <th>Time</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

            @foreach($data as $row)
                <tr>
                    <td>{{ $row->class->name }}</td>
                    <td>{{ $row->section->name }}</td>
                    <td>{{ $row->teacher->user->name }}</td>
                    <td>{{ $row->subject->name }}</td>
                    <td>{{ $row->day }}</td>
                    <td>{{ $row->start_time }} - {{ $row->end_time }}</td>

                    <td>
                        <a href="/timetable/edit/{{ $row->id }}" class="btn btn-sm btn-primary">Edit</a>

                        <form method="POST" action="/timetable/delete/{{ $row->id }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>

        <div class="mt-3">
            {{ $data->links() }}
        </div>

    </div>

</div>

@stop