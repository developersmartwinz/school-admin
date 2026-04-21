@extends('adminlte::page')

@section('title', 'Attendance Report')

@section('content_header')
<h1>Attendance Report</h1>
@stop

@section('content')

<div class="card">

    <!-- FILTER -->
    <div class="card-header">
        <form class="row">

            <div class="col-md-3">
                <select name="class_id" id="class_id" class="form-control">
                    <option value="">Select Class</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select name="section_id" id="section_id" class="form-control">
                    <option value="">Select Section</option>
                </select>
            </div>

            <div class="col-md-3">
                <input type="month" name="month" class="form-control">
            </div>

            <div class="col-md-3">
                <button class="btn btn-primary w-100">Filter</button>
            </div>

        </form>
    </div>

    <!-- TABLE -->
    <div class="card-body">

        <table class="table table-bordered text-center">

            <thead class="bg-dark text-white">
                <tr>
                    <th>Student</th>
                    <th>Total Days</th>
                    <th>Present</th>
                    <th>Absent</th>
                    <th>%</th>
                </tr>
            </thead>

            <tbody>

            @forelse($report as $row)
                <tr>
                    <td>{{ $row['student']->name }}</td>
                    <td>{{ $row['total'] }}</td>
                    <td class="text-success">{{ $row['present'] }}</td>
                    <td class="text-danger">{{ $row['absent'] }}</td>
                    <td>
                        <span class="badge bg-info">
                            {{ $row['percentage'] }}%
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No data</td>
                </tr>
            @endforelse

            </tbody>

        </table>

    </div>

</div>

@stop


@section('js')

<script>
document.getElementById('class_id').addEventListener('change', function () {

    fetch('/get-sections/' + this.value)
        .then(res => res.json())
        .then(data => {

            let s = document.getElementById('section_id');
            s.innerHTML = '<option>Select</option>';

            data.forEach(i => {
                s.innerHTML += `<option value="${i.id}">${i.name}</option>`;
            });

        });
});
</script>

@stop