@extends('adminlte::page')

@section('title', 'Add Timetable')

@section('content_header')
    <h1>Add Timetable</h1>
@stop

@section('content')

<form method="POST" action="/timetable/store">
@csrf

<div class="card">
    <div class="card-body">

        <div class="row">

            <!-- CLASS -->
            <div class="col-md-4">
                <label>Class</label>
                <select id="class_id" name="class_id" class="form-control" required>
                    <option value="">Select</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- SECTION -->
            <div class="col-md-4">
                <label>Section</label>
                <select id="section_id" name="section_id" class="form-control" required></select>
            </div>

            <!-- TEACHER -->
            <div class="col-md-4">
                <label>Teacher</label>
                <select name="teacher_id" class="form-control" required>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->user->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- SUBJECT -->
            <div class="col-md-4 mt-2">
                <label>Subject</label>
                <select id="subject_id" name="subject_id" class="form-control" required></select>
            </div>

            <!-- DAY -->
            <div class="col-md-4 mt-2">
                <label>Day</label>
                <select name="day" class="form-control">
                    <option>Monday</option>
                    <option>Tuesday</option>
                    <option>Wednesday</option>
                    <option>Thursday</option>
                    <option>Friday</option>
                    <option>Saturday</option>
                </select>
            </div>

            <!-- TIME -->
            <div class="col-md-2 mt-2">
                <label>Start</label>
                <input type="time" name="start_time" class="form-control">
            </div>

            <div class="col-md-2 mt-2">
                <label>End</label>
                <input type="time" name="end_time" class="form-control">
            </div>

        </div>

    </div>

    <div class="card-footer text-end">
        <button class="btn btn-success">Save</button>
    </div>
</div>

</form>

@stop

@section('js')

<script>
document.getElementById('class_id').addEventListener('change', function () {

    let classId = this.value;

    // Sections
    fetch('/get-sections/' + classId)
        .then(res => res.json())
        .then(data => {
            let s = document.getElementById('section_id');
            s.innerHTML = '';
            data.forEach(i => {
                s.innerHTML += `<option value="${i.id}">${i.name}</option>`;
            });
        });

    // Subjects
    fetch('/get-subjects/' + classId)
        .then(res => res.json())
        .then(data => {
            let s = document.getElementById('subject_id');
            s.innerHTML = '';
            data.forEach(i => {
                s.innerHTML += `<option value="${i.subject.id}">${i.subject.name}</option>`;
            });
        });

});
</script>

@stop