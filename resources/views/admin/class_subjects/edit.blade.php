@extends('adminlte::page')

@section('title', 'Edit Class Subjects')

@section('content_header')
    <h1>Edit Class Subjects</h1>
@stop

@section('content')

<form method="POST" action="/class-subjects/store">
@csrf

<div class="card shadow-sm">
    <div class="card-body">

        <div class="row">

            <!-- CLASS -->
            <div class="col-md-6">
                <label>Class</label>
                <select name="class_id" class="form-control" required>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}"
                            {{ $class_id == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- SUBJECTS -->
            <div class="col-md-6">
                <label>Subjects</label>
                <select name="subject_ids[]" id="subject_id" class="form-control" multiple required>

                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}"
                            {{ in_array($subject->id, $selected) ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach

                </select>
            </div>

        </div>

    </div>

    <div class="card-footer text-end">
        <button class="btn btn-primary">
            <i class="fas fa-save"></i> Update
        </button>
    </div>
</div>

</form>

@stop


@section('js')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {

    $('#subject_id').select2({
        placeholder: "Select Subjects",
        width: '100%'
    });

});
</script>

@stop