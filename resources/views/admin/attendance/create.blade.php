@extends('adminlte::page')

@section('title', 'Attendance')

@section('content_header')
<h1>Mark Attendance</h1>
@stop

@section('content')

<form method="POST" action="/attendance/store">
@csrf

<div class="card shadow-sm">

    <div class="card-body">

        <div class="row">

            <!-- DATE -->
            <div class="col-md-3">
                <label>Date</label>
                <input type="date" name="date" id="date" class="form-control" required>
            </div>

            <!-- CLASS -->
            <div class="col-md-3">
                <label>Class</label>
                <select id="class_id" name="class_id" class="form-control">
                    <option value="">Select</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- SECTION -->
            <div class="col-md-3">
                <label>Section</label>
                <select id="section_id" name="section_id" class="form-control"></select>
            </div>

            <!-- BUTTON -->
            <div class="col-md-3 d-flex align-items-end">
                <button type="button" onclick="loadStudents()" class="btn btn-primary w-100">
                    Load Students
                </button>
            </div>

        </div>

        <hr>

        <!-- ACTION BUTTON -->
        <button type="button" class="btn btn-success mb-2" onclick="markAllPresent()">
            Mark All Present
        </button>

        <!-- STUDENTS TABLE -->
        <div id="students"></div>

    </div>

    <div class="card-footer text-end">
        <button class="btn btn-success">Save Attendance</button>
    </div>

</div>

</form>

@stop

@section('js')

<script>

// load sections
document.getElementById('class_id').addEventListener('change', function () {

    let classId = this.value;

    fetch('/get-sections/' + classId)
        .then(res => res.json())
        .then(data => {

            let s = document.getElementById('section_id');
            s.innerHTML = '<option value="">Select</option>';

            data.forEach(i => {
                s.innerHTML += `<option value="${i.id}">${i.name}</option>`;
            });

        });
});


// load students + attendance
function loadStudents() {

    let classId = document.getElementById('class_id').value;
    let sectionId = document.getElementById('section_id').value;
    let date = document.getElementById('date').value;

    if (!classId || !sectionId || !date) {
        alert('Select class, section & date');
        return;
    }

    fetch(`/get-attendance/${classId}/${sectionId}/${date}`)
        .then(res => res.json())
        .then(res => {

            let html = `
            <table class="table table-bordered text-center">
                <tr>
                    <th>Name</th>
                    <th>Present</th>
                    <th>Absent</th>
                </tr>`;

            res.students.forEach(student => {

                let status = res.attendance[student.id]?.status ?? 'present';

                html += `
                <tr>
                    <td>${student.name}</td>

                    <td>
                        <input type="radio" name="students[${student.id}]" value="present"
                        ${status == 'present' ? 'checked' : ''}>
                    </td>

                    <td>
                        <input type="radio" name="students[${student.id}]" value="absent"
                        ${status == 'absent' ? 'checked' : ''}>
                    </td>
                </tr>`;
            });

            html += '</table>';

            document.getElementById('students').innerHTML = html;

        });
}


// mark all present
function markAllPresent() {
    document.querySelectorAll('input[value="present"]').forEach(el => {
        el.checked = true;
    });
}

</script>

@stop