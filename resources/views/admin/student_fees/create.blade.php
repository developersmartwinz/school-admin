@extends('adminlte::page')

@section('title', 'Assign Student Fees')

@section('content_header')
    <h1>Assign Student Fees</h1>
@stop

@section('content')

<form method="POST" action="/student-fees/store">
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
                            <option value="{{ $class->id }}">
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Section --}}
                <div class="col-md-6">
                    <label>Section</label>
                    <select name="section_id" id="section_id" class="form-control" required>
                        <option value="">Select Section</option>
                    </select>
                </div>

                {{-- Student --}}
                <div class="col-md-6 mt-3">
                    <label>Student</label>
                    <select name="student_id" id="student_id" class="form-control" required>
                        <option value="">Select Student</option>
                    </select>
                </div>

                {{-- Year --}}
                <div class="col-md-6 mt-3">
                    <label>Year</label>
                    <input
                        type="number"
                        name="year"
                        class="form-control"
                        value="{{ date('Y') }}"
                        required
                    >
                </div>

            </div>

            <hr>

            {{-- Multiple Fee Types --}}
            <div class="table-responsive">
                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th width="80">Select</th>
                            <th>Fee Type</th>
                            <th>Default Amount</th>
                            <th>Assign Amount</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($feeTypes as $feeType)
                            <tr>

                                <td>
                                    <input
                                        type="checkbox"
                                        name="fee_type_ids[]"
                                        value="{{ $feeType->id }}"
                                    >
                                </td>

                                <td>
                                    {{ $feeType->name }}
                                </td>

                                <td>
                                    ₹ {{ number_format($feeType->amount, 2) }}
                                </td>

                                <td>
                                    <input
                                        type="number"
                                        step="0.01"
                                        name="amounts[]"
                                        class="form-control"
                                        value="{{ $feeType->amount }}"
                                    >
                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>
            </div>

            

        </div>

        <div class="card-footer">
            <button class="btn btn-success">
                <i class="fas fa-save"></i> Save Assignment
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