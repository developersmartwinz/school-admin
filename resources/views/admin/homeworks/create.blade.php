{{-- resources/views/admin/homeworks/create.blade.php --}}

@extends('adminlte::page')

@section('title', 'Add Homework')

@section('content_header')
    <h1>Add Homework</h1>
@stop

@section('content')

<form method="POST"
      action="/homeworks/store"
      enctype="multipart/form-data">
    @csrf

    <div class="card shadow-sm">

        <div class="card-body">

            <div class="row">

                {{-- Title --}}
                <div class="col-md-6 mb-3">
                    <label>
                        Title
                        <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           name="title"
                           class="form-control"
                           value="{{ old('title') }}"
                           required>
                </div>

                {{-- Type --}}
                <div class="col-md-6 mb-3">
                    <label>
                        Type
                        <span class="text-danger">*</span>
                    </label>

                    <select name="type"
                            class="form-control"
                            required>
                        <option value="">Select Type</option>
                        <option value="homework">Homework</option>
                        <option value="assignment">Assignment</option>
                        <option value="project">Project</option>
                    </select>
                </div>

                {{-- Class --}}
                <div class="col-md-3 mb-3">
                    <label>
                        Class
                        <span class="text-danger">*</span>
                    </label>

                    <select name="class_id"
                            id="class_id"
                            class="form-control"
                            required>
                        <option value="">Select Class</option>

                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Section --}}
                <div class="col-md-3 mb-3">
                    <label>
                        Section
                        <span class="text-danger">*</span>
                    </label>

                    <select name="section_id"
                            id="section_id"
                            class="form-control"
                            required>
                        <option value="">Select Section</option>
                    </select>
                </div>

                {{-- Subject --}}
                <div class="col-md-3 mb-3">
                    <label>
                        Subject
                        <span class="text-danger">*</span>
                    </label>

                    <select name="subject_id"
                            id="subject_id"
                            class="form-control"
                            required>
                        <option value="">Select Subject</option>
                    </select>
                </div>

                {{-- Teacher --}}
                <div class="col-md-3 mb-3">
                    <label>
                        Teacher
                        <span class="text-danger">*</span>
                    </label>

                    <select name="teacher_id"
                            id="teacher_id"
                            class="form-control"
                            required>
                        <option value="">Select Teacher</option>
                    </select>
                </div>

                {{-- Assign Date --}}
                <div class="col-md-4 mb-3">
                    <label>
                        Assign Date
                        <span class="text-danger">*</span>
                    </label>

                    <input type="date"
                           name="assign_date"
                           class="form-control"
                           value="{{ old('assign_date', date('Y-m-d')) }}"
                           required>
                </div>

                {{-- Assign Time --}}
                <div class="col-md-4 mb-3">
                    <label>Assign Time</label>

                    <input type="time"
                           name="assign_time"
                           class="form-control"
                           value="{{ old('assign_time') }}">
                </div>

                {{-- Submission Date --}}
                <div class="col-md-4 mb-3">
                    <label>Submission Date</label>

                    <input type="date"
                           name="submission_date"
                           class="form-control"
                           value="{{ old('submission_date') }}">
                </div>

                {{-- Attachment --}}
                <div class="col-md-6 mb-3">
                    <label>Attachment</label>

                    <input type="file"
                           name="attachment"
                           class="form-control"
                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                </div>

                {{-- Status --}}
                <div class="col-md-6 mb-3">
                    <label>
                        Status
                        <span class="text-danger">*</span>
                    </label>

                    <select name="status"
                            class="form-control"
                            required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                {{-- Description --}}
                <div class="col-md-12 mb-3">
                    <label>Description</label>

                    <textarea name="description"
                              class="form-control"
                              rows="5"
                              placeholder="Enter homework details...">{{ old('description') }}</textarea>
                </div>

            </div>

        </div>

        <div class="card-footer">
            <button class="btn btn-success">
                <i class="fas fa-save"></i>
                Save Homework
            </button>

            <a href="/homeworks"
               class="btn btn-secondary">
                Back
            </a>
        </div>

    </div>

</form>

@stop


@section('js')

<script>
/*
|--------------------------------------------------------------------------
| Class Change → Load Sections
|--------------------------------------------------------------------------
*/
document.getElementById('class_id').addEventListener('change', function () {
    let classId = this.value;

    document.getElementById('section_id').innerHTML =
        '<option value="">Loading...</option>';

    document.getElementById('subject_id').innerHTML =
        '<option value="">Select Subject</option>';

    document.getElementById('teacher_id').innerHTML =
        '<option value="">Select Teacher</option>';

    if (!classId) return;

    fetch(`/get-sections/${classId}`)
        .then(res => res.json())
        .then(data => {
            let options = '<option value="">Select Section</option>';

            data.forEach(item => {
                options += `<option value="${item.id}">${item.name}</option>`;
            });

            document.getElementById('section_id').innerHTML = options;
        });
});


/*
|--------------------------------------------------------------------------
| Section Change → Load Subjects
|--------------------------------------------------------------------------
*/
document.getElementById('section_id').addEventListener('change', function () {
    let classId = document.getElementById('class_id').value;
    let sectionId = this.value;

    document.getElementById('subject_id').innerHTML =
        '<option value="">Loading...</option>';

    document.getElementById('teacher_id').innerHTML =
        '<option value="">Select Teacher</option>';

    if (!classId || !sectionId) return;

    fetch(`/get-class-section-subjects/${classId}/${sectionId}`)
        .then(res => res.json())
        .then(data => {
            let options = '<option value="">Select Subject</option>';

            data.forEach(item => {
                options += `<option value="${item.id}">${item.name}</option>`;
            });

            document.getElementById('subject_id').innerHTML = options;
        });
});


/*
|--------------------------------------------------------------------------
| Subject Change → Load Teachers
|--------------------------------------------------------------------------
*/
document.getElementById('subject_id').addEventListener('change', function () {
    let classId = document.getElementById('class_id').value;
    let sectionId = document.getElementById('section_id').value;
    let subjectId = this.value;

    document.getElementById('teacher_id').innerHTML =
        '<option value="">Loading...</option>';

    if (!classId || !sectionId || !subjectId) return;

    fetch(`/get-homework-teachers/${classId}/${sectionId}/${subjectId}`)
        .then(res => res.json())
        .then(data => {
            let options = '<option value="">Select Teacher</option>';

            data.forEach(item => {
                options += `<option value="${item.id}">${item.name}</option>`;
            });

            document.getElementById('teacher_id').innerHTML = options;
        });
});
</script>

@stop