@extends('adminlte::page')

@section('title', 'Assign Teacher')

@section('content_header')
    <h1>Assign Teacher</h1>
@stop

@section('content')

<form method="POST" action="/teacher-assignments/store">
@csrf

<div class="card shadow-sm">
    <div class="card-body">

        <div class="row">

            <!-- TEACHER -->
            <div class="col-md-6">
                <label>Teacher</label>
                <select name="teacher_id" class="form-control" required>
                    <option value="">Select Teacher</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">
                            {{ $teacher->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- CLASS -->
            <div class="col-md-6">
                <label>Class</label>
                <select name="class_id" id="class_id" class="form-control" required>
                    <option value="">Select Class</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- SECTION -->
            <div class="col-md-6 mt-2">
                <label>Section</label>
                <select name="section_id" id="section_id" class="form-control" required>
                    <option value="">Select Section</option>
                </select>
            </div>

            <!-- SUBJECTS -->
            <div class="col-md-6 mt-2">
                <label>Subjects</label>
                <select name="subject_ids[]" id="subject_id" class="form-control" multiple required></select>
            </div>

        </div>

    </div>

    <div class="card-footer text-end">
        <button class="btn btn-success">
            <i class="fas fa-save"></i> Assign
        </button>
    </div>
</div>

</form>

@stop


@section('js')

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script>

$(document).ready(function() {

    // ✅ Init Select2
    $('#subject_id').select2({
        placeholder: "Select Subjects",
        width: '100%'
    });

    // ✅ Class change handler (SECTION + SUBJECT)
    $('#class_id').on('change', function () {

        let classId = $(this).val();

        if (!classId) return;

        // 🔹 Load Sections
        fetch('/get-sections/' + classId)
            .then(res => res.json())
            .then(data => {
                let sectionDropdown = $('#section_id');
                sectionDropdown.html('<option value="">Select Section</option>');

                data.forEach(section => {
                    sectionDropdown.append(
                        `<option value="${section.id}">${section.name}</option>`
                    );
                });
            });

        // 🔹 Load Subjects
        fetch('/get-subjects/' + classId)
            .then(res => res.json())
            .then(data => {

                let subjectDropdown = $('#subject_id');
                subjectDropdown.empty(); // clear

                data.forEach(item => {
                    subjectDropdown.append(
                        `<option value="${item.subject.id}">
                            ${item.subject.name}
                        </option>`
                    );
                });

                // ✅ Refresh Select2
                subjectDropdown.trigger('change');
            });

    });

});

</script>

@stop