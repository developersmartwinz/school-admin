@extends('adminlte::page')

@section('title', 'Edit Student')

@section('content_header')
    <h1>Edit Student</h1>
@stop

@section('content')

<form method="POST" action="/students/update/{{ $student->id }}">
    @csrf

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="row">

                <!-- NAME -->
                <div class="col-md-6">
                    <label>Name</label>
                    <input type="text" name="name"
                        value="{{ $student->user->name }}"
                        class="form-control" required>
                </div>

                <!-- PHONE -->
                <div class="col-md-6">
                    <label>Phone</label>
                    <input type="text" name="phone"
                        value="{{ $student->user->phone }}"
                        class="form-control" required>
                </div>

                <!-- REG NO -->
                <div class="col-md-6 mt-2">
                    <label>Reg No</label>
                    <input type="text" name="reg_no"
                        value="{{ $student->reg_no }}"
                        class="form-control">
                </div>

                <!-- DOB -->
                <div class="col-md-6 mt-2">
                    <label>DOB</label>
                    <input type="date" name="dob"
                        value="{{ $student->dob }}"
                        class="form-control">
                </div>

                <!-- GENDER -->
                <div class="col-md-6 mt-2">
                    <label>Gender</label>
                    <select name="gender" class="form-control">
                        <option {{ $student->gender == 'Male' ? 'selected' : '' }}>Male</option>
                        <option {{ $student->gender == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <!-- HOUSE -->
                <div class="col-md-6 mt-2">
                    <label>House</label>
                    <input type="text" name="house"
                        value="{{ $student->house }}"
                        class="form-control">
                </div>

                <!-- CATEGORY -->
                <div class="col-md-6 mt-2">
                    <label>Category</label>
                    <input type="text" name="category"
                        value="{{ $student->category }}"
                        class="form-control">
                </div>

                <!-- BLOOD GROUP -->
                <div class="col-md-6 mt-2">
                    <label>Blood Group</label>
                    <input type="text" name="blood_group"
                        value="{{ $student->blood_group }}"
                        class="form-control">
                </div>

                <!-- CLASS -->
                <div class="col-md-6 mt-2">
                    <label>Class</label>
                    <select name="class_id" class="form-control" required>
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}"
                                {{ $student->class_id == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- SECTION -->
                <div class="col-md-6 mt-2">
                    <label>Section</label>
                    <select name="section_id" class="form-control" required>
                        <option value="">Select Section</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}"
                                {{ $student->section_id == $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- ADDRESS -->
                <div class="col-md-12 mt-2">
                    <label>Address</label>
                    <textarea name="address" class="form-control">{{ $student->address }}</textarea>
                </div>

                <!-- CITY -->
                <div class="col-md-6 mt-2">
                    <label>City</label>
                    <input type="text" name="city"
                        value="{{ $student->city }}"
                        class="form-control">
                </div>

                <!-- STATE -->
                <div class="col-md-6 mt-2">
                    <label>State</label>
                    <input type="text" name="state"
                        value="{{ $student->state }}"
                        class="form-control">
                </div>

                <!-- AADHAR -->
                <div class="col-md-6 mt-2">
                    <label>Aadhar</label>
                    <input type="text" name="aadhar"
                        value="{{ $student->aadhar }}"
                        class="form-control">
                </div>

            </div>
					<hr>
		<h5 class="mb-3">Father Details</h5>

		<div class="row">
			<div class="col-md-4">
				<label>Name</label>
				<input type="text" name="father_name" value="{{ $student->father_name }}" class="form-control">
			</div>

			<div class="col-md-4">
				<label>Contact No</label>
				<input type="text" name="father_phone" value="{{ $student->father_phone }}" class="form-control">
			</div>

			<div class="col-md-4">
				<label>Email</label>
				<input type="email" name="father_email" value="{{ $student->father_email }}" class="form-control">
			</div>

			<div class="col-md-4 mt-2">
				<label>Occupation</label>
				<input type="text" name="father_occupation" value="{{ $student->father_occupation }}" class="form-control">
			</div>

			<div class="col-md-4 mt-2">
				<label>Qualification</label>
				<input type="text" name="father_qualification" value="{{ $student->father_qualification }}" class="form-control">
			</div>

			<div class="col-md-4 mt-2">
				<label>Designation</label>
				<input type="text" name="father_designation" value="{{ $student->father_designation }}" class="form-control">
			</div>
		</div>

		<hr>
		<h5 class="mb-3">Mother Details</h5>

		<div class="row">
			<div class="col-md-4">
				<label>Name</label>
				<input type="text" name="mother_name" value="{{ $student->mother_name }}" class="form-control">
			</div>

			<div class="col-md-4">
				<label>Contact No</label>
				<input type="text" name="mother_phone" value="{{ $student->mother_phone }}" class="form-control">
			</div>

			<div class="col-md-4">
				<label>Email</label>
				<input type="email" name="mother_email" value="{{ $student->mother_email }}" class="form-control">
			</div>

			<div class="col-md-4 mt-2">
				<label>Occupation</label>
				<input type="text" name="mother_occupation" value="{{ $student->mother_occupation }}" class="form-control">
			</div>

			<div class="col-md-4 mt-2">
				<label>Qualification</label>
				<input type="text" name="mother_qualification" value="{{ $student->mother_qualification }}" class="form-control">
			</div>

			<div class="col-md-4 mt-2">
				<label>Designation</label>
				<input type="text" name="mother_designation" value="{{ $student->mother_designation }}" class="form-control">
			</div>
		</div>

        </div>

        <div class="card-footer d-flex justify-content-end">
            <button class="btn btn-success">
                <i class="fas fa-save"></i> Update Student
            </button>
        </div>
    </div>

</form>

@stop