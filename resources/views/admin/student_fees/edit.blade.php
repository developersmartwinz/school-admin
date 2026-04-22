@extends('adminlte::page')

@section('title', 'Edit Student Fee Assignment')

@section('content_header')
    <h1>Edit Student Fee Assignment</h1>
@stop

@section('content')

<form method="POST" action="/student-fees/update/{{ $studentFee->id }}">
    @csrf

    <div class="card">
        <div class="card-body">

            <div class="row">

                {{-- Class --}}
                <div class="col-md-6">
                    <label>Class</label>
                    <select name="class_id" id="class_id" class="form-control" required>
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option
                                value="{{ $class->id }}"
                                {{ $studentFee->student->class_id == $class->id ? 'selected' : '' }}
                            >
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Section --}}
                <div class="col-md-6">
                    <label>Section</label>
                    <select name="section_id" id="section_id" class="form-control" required>
                        <option
                            value="{{ $studentFee->student->section_id }}"
                            selected
                        >
                            {{ $studentFee->student->section->name ?? 'Selected Section' }}
                        </option>
                    </select>
                </div>

                {{-- Student --}}
                <div class="col-md-6 mt-3">
                    <label>Student</label>
                    <select name="student_id" id="student_id" class="form-control" required>
                        <option
                            value="{{ $studentFee->student->id }}"
                            selected
                        >
                            {{ $studentFee->student->user->name ?? '' }}
                        </option>
                    </select>
                </div>

                {{-- Fee Type --}}
                <div class="col-md-6 mt-3">
                    <label>Fee Type</label>
                    <select name="fee_type_id" class="form-control" required>
                        <option value="">Select Fee Type</option>
                        @foreach($feeTypes as $feeType)
                            <option
                                value="{{ $feeType->id }}"
                                {{ $studentFee->fee_type_id == $feeType->id ? 'selected' : '' }}
                            >
                                {{ $feeType->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Amount --}}
                <div class="col-md-6 mt-3">
                    <label>Assigned Amount</label>
                    <input
                        type="number"
                        step="0.01"
                        name="amount"
                        class="form-control"
                        value="{{ $studentFee->amount }}"
                        required
                    >
                </div>

                {{-- Year --}}
                <div class="col-md-6 mt-3">
                    <label>Year</label>
                    <input
                        type="number"
                        name="year"
                        class="form-control"
                        value="{{ $studentFee->year }}"
                        required
                    >
                </div>

            </div>

        </div>

        <div class="card-footer">
            <button class="btn btn-success">
                <i class="fas fa-save"></i> Update Assignment
            </button>

            <a href="/student-fees" class="btn btn-secondary">
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
| Load Sections by Class
|--------------------------------------------------------------------------
*/
document.getElementById('class_id').addEventListener('change', function () {
    let classId = this.value;

    fetch('/get-sections/' + classId)
        .then(res => res.json())
        .then(data => {
            let sectionDropdown = document.getElementById('section_id');

            sectionDropdown.innerHTML =
                '<option value="">Select Section</option>';

            data.forEach(section => {
                sectionDropdown.innerHTML +=
                    `<option value="${section.id}">
                        ${section.name}
                    </option>`;
            });
        });
});


/*
|--------------------------------------------------------------------------
| Load Students by Section
|--------------------------------------------------------------------------
*/
document.getElementById('section_id').addEventListener('change', function () {
    let classId = document.getElementById('class_id').value;
    let sectionId = this.value;

    fetch(`/get-students/${classId}/${sectionId}`)
        .then(res => res.json())
        .then(data => {
            let studentDropdown = document.getElementById('student_id');

            studentDropdown.innerHTML =
                '<option value="">Select Student</option>';

            data.forEach(student => {
                studentDropdown.innerHTML +=
                    `<option value="${student.id}">
                        ${student.name}
                    </option>`;
            });
        });
});
</script>

@stop