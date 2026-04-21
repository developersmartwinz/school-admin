@extends('adminlte::page')

@section('title', 'Edit Assignment')

@section('content_header')
    <h1>Edit Assignment</h1>
@stop

@section('content')

<form method="POST" action="/teacher-assignments/update">
@csrf

<div class="card shadow-sm">
    <div class="card-body">

        <div class="row">

            <!-- TEACHER -->
            <div class="col-md-6">
                <label>Teacher</label>
                <select name="teacher_id" class="form-control" required>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}"
                            {{ $teacher_id == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- CLASS -->
            <div class="col-md-6">
                <label>Class</label>
                <select name="class_id" id="class_id" class="form-control" required>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}"
                            {{ $class_id == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
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
            <i class="fas fa-save"></i> Update
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

    let classId = "{{ $class_id }}";
    let sectionId = "{{ $section_id }}";
    let selectedSubjects = @json($selectedSubjects);

    // ✅ Init Select2
    $('#subject_id').select2({
        placeholder: "Select Subjects",
        width: '100%'
    });

    // 🔹 Load Sections (initial)
    function loadSections(classId, selectedSection = null) {
        fetch('/get-sections/' + classId)
            .then(res => res.json())
            .then(data => {
                let sectionDropdown = $('#section_id');
                sectionDropdown.html('<option value="">Select Section</option>');

                data.forEach(section => {
                    let selected = section.id == selectedSection ? 'selected' : '';
                    sectionDropdown.append(
                        `<option value="${section.id}" ${selected}>
                            ${section.name}
                        </option>`
                    );
                });
            });
    }

    // 🔹 Load Subjects (initial)
    function loadSubjects(classId, selectedSubjects = []) {
        fetch('/get-subjects/' + classId)
            .then(res => res.json())
            .then(data => {

                let subjectDropdown = $('#subject_id');
                subjectDropdown.empty();

                data.forEach(item => {
                    let selected = selectedSubjects.includes(item.subject.id) ? 'selected' : '';

                    subjectDropdown.append(
                        `<option value="${item.subject.id}" ${selected}>
                            ${item.subject.name}
                        </option>`
                    );
                });

                subjectDropdown.trigger('change');
            });
    }

    // ✅ Initial load
    loadSections(classId, sectionId);
    loadSubjects(classId, selectedSubjects);

    // ✅ On class change
    $('#class_id').on('change', function () {

        let classId = $(this).val();

        loadSections(classId);
        loadSubjects(classId);

    });

});

</script>

@stop