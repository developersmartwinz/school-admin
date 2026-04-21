@extends('adminlte::page')

@section('title', 'Add Student')

@section('content_header')
    <h1>Add Student</h1>
@stop

@section('content')

<form method="POST" action="/students/store">
@csrf

<div class="card shadow-sm">
    <div class="card-body">

        <div class="row">

            <div class="col-md-6">
                <label>Name</label>
                <input name="name" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label>Phone</label>
                <input name="phone" class="form-control" required>
            </div>
			
			<div class="col-md-6">
                <label>Email</label>
                <input name="email" type="email" class="form-control" required>
            </div>

            <div class="col-md-6 mt-2">
                <label>Reg No</label>
                <input name="reg_no" class="form-control">
            </div>

            <div class="col-md-6 mt-2">
                <label>DOB</label>
                <input type="date" name="dob" class="form-control">
            </div>

            <div class="col-md-6 mt-2">
                <label>Gender</label>
                <select name="gender" class="form-control">
                    <option>Male</option>
                    <option>Female</option>
                </select>
            </div>
			<div class="col-md-6 mt-2">
				<label>Class</label>
				<select name="class_id" class="form-control" required>
					<option value="">Select Class</option>
					@foreach($classes as $class)
						<option value="{{ $class->id }}">{{ $class->name }}</option>
					@endforeach
				</select>
			</div>

			<div class="col-md-6 mt-2">
				<label>Section</label>
				<select name="section_id" class="form-control" required>
					<option value="">Select Section</option>
					@foreach($sections as $section)
						<option value="{{ $section->id }}">{{ $section->name }}</option>
					@endforeach
				</select>
			</div>

            <div class="col-md-6 mt-2">
                <label>House</label>
                <input name="house" class="form-control">
            </div>

            <div class="col-md-6 mt-2">
                <label>Category</label>
                <input name="category" class="form-control">
            </div>

            <div class="col-md-6 mt-2">
                <label>Blood Group</label>
                <input name="blood_group" class="form-control">
            </div>

            <div class="col-md-12 mt-2">
                <label>Address</label>
                <textarea name="address" class="form-control"></textarea>
            </div>

            <div class="col-md-6 mt-2">
                <label>City</label>
                <input name="city" class="form-control">
            </div>

            <div class="col-md-6 mt-2">
                <label>State</label>
                <input name="state" class="form-control">
            </div>

            <div class="col-md-6 mt-2">
                <label>Aadhar</label>
                <input name="aadhar" class="form-control">
            </div>

        </div>
		<hr>
		<h5 class="mb-3">Father Details</h5>

		<div class="row">
			<div class="col-md-4">
				<label>Name</label>
				<input type="text" name="father_name" class="form-control">
			</div>

			<div class="col-md-4">
				<label>Contact No</label>
				<input type="text" name="father_phone" class="form-control">
			</div>

			<div class="col-md-4">
				<label>Email</label>
				<input type="email" name="father_email" class="form-control">
			</div>

			<div class="col-md-4 mt-2">
				<label>Occupation</label>
				<input type="text" name="father_occupation" class="form-control">
			</div>

			<div class="col-md-4 mt-2">
				<label>Qualification</label>
				<input type="text" name="father_qualification" class="form-control">
			</div>

			<div class="col-md-4 mt-2">
				<label>Designation</label>
				<input type="text" name="father_designation" class="form-control">
			</div>
		</div>

		<hr>
		<h5 class="mb-3">Mother Details</h5>

		<div class="row">
			<div class="col-md-4">
				<label>Name</label>
				<input type="text" name="mother_name" class="form-control">
			</div>

			<div class="col-md-4">
				<label>Contact No</label>
				<input type="text" name="mother_phone" class="form-control">
			</div>

			<div class="col-md-4">
				<label>Email</label>
				<input type="email" name="mother_email" class="form-control">
			</div>

			<div class="col-md-4 mt-2">
				<label>Occupation</label>
				<input type="text" name="mother_occupation" class="form-control">
			</div>

			<div class="col-md-4 mt-2">
				<label>Qualification</label>
				<input type="text" name="mother_qualification" class="form-control">
			</div>

			<div class="col-md-4 mt-2">
				<label>Designation</label>
				<input type="text" name="mother_designation" class="form-control">
			</div>
		</div>

    </div>

    <div class="card-footer">
        <button class="btn btn-success">Save Student</button>
    </div>
</div>

</form>

@stop