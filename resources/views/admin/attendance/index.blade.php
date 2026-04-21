@extends('adminlte::page')

@section('title', 'Attendance')

@section('content_header')
<h1>Attendance Records</h1>
@stop

@section('content')

<div class="card shadow-sm">

    <!-- FILTER -->
    <div class="card-header">
        <form class="row">

            <div class="col-md-3">
                <input type="date" name="date" value="{{ request('date') }}" class="form-control">
            </div>

            <div class="col-md-3">
                <select name="class_id" id="class_id" class="form-control">
                    <option value="">Select Class</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}"
                            {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select name="section_id" id="section_id" class="form-control">
                    <option value="">Select Section</option>
                </select>
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Filter
                </button>

                <a href="/attendance" class="btn btn-secondary w-100">
                    Reset
                </a>
            </div>

        </form>
    </div>

    <!-- TABLE -->
    <div class="card-body">

        <table class="table table-bordered table-hover text-center align-middle">

            <thead class="bg-dark text-white">
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Student</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

            @forelse($data as $key => $row)
                <tr>
                    <td>{{ $data->firstItem() + $key }}</td>

                    <td>{{ $row->date }}</td>

                    <td>{{ optional($row->student)->name }}</td>

                    <td>{{ optional($row->class)->name }}</td>

                    <td>{{ optional($row->section)->name }}</td>

                    <td>
                        @if($row->status == 'present')
                            <span class="badge bg-success">Present</span>
                        @else
                            <span class="badge bg-danger">Absent</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No records found</td>
                </tr>
            @endforelse

            </tbody>

        </table>

        <!-- PAGINATION -->
        <div class="mt-3">
            {{ $data->links() }}
        </div>

    </div>

</div>

@stop


@section('js')

<script>

// function to load sections
function loadSections(classId, selectedSection = null) {

    if (!classId) return;

    fetch('/get-sections/' + classId)
        .then(res => res.json())
        .then(data => {

            let s = document.getElementById('section_id');
            s.innerHTML = '<option value="">Select Section</option>';

            data.forEach(i => {
                let selected = selectedSection == i.id ? 'selected' : '';
                s.innerHTML += `<option value="${i.id}" ${selected}>${i.name}</option>`;
            });

        });
}


// ON CLASS CHANGE
document.getElementById('class_id').addEventListener('change', function () {
    loadSections(this.value);
});


// ✅ ON PAGE LOAD (IMPORTANT FIX)
window.onload = function () {

    let classId = "{{ request('class_id') }}";
    let sectionId = "{{ request('section_id') }}";

    if (classId) {
        loadSections(classId, sectionId);
    }
};

</script>

@stop