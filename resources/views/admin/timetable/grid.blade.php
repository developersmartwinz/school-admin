@extends('adminlte::page')

@section('title', 'Timetable Grid')

@section('content_header')
    <h1>Timetable Grid</h1>
@stop

@section('content')

<div class="card shadow-sm">

    <!-- FILTER -->
    <div class="card-header">
        <form class="row">

            <div class="col-md-4">
                <select name="class_id" id="class_id" class="form-control">
                    <option value="">Select Class</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}"
                            {{ $class_id == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <select name="section_id" id="section_id" class="form-control">
                    <option value="">Select Section</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}"
                            {{ $section_id == $section->id ? 'selected' : '' }}>
                            {{ $section->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <button class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Load
                </button>
            </div>

        </form>
    </div>

    <!-- GRID -->
    <div class="card-body">

        @if($class_id && $section_id)

        <table class="table table-bordered table-hover text-center align-middle">

            <thead class="bg-dark text-white">
                <tr>
                    <th>Time</th>
                    <th>Monday</th>
                    <th>Tuesday</th>
                    <th>Wednesday</th>
                    <th>Thursday</th>
                    <th>Friday</th>
                    <th>Saturday</th>
                </tr>
            </thead>

            <tbody>

            @foreach($data as $time => $row)

                <tr>

                    <!-- TIME -->
                    <td class="bg-light">
                        <strong>{{ $time }}</strong>
                    </td>

                    @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $day)

                        <td>

                            {{-- 🍱 BREAK --}}
                            @if($row['type'] === 'break')

                                <div class="bg-warning text-dark p-2 rounded text-center">
                                    <strong>🍱 Break</strong>
                                </div>

                            {{-- CLASS --}}
                            @elseif(isset($row['days'][$day]) && $row['days'][$day])

                                <div class="p-2 rounded bg-primary text-white">

                                    <strong>
                                        {{ optional($row['days'][$day]->subject)->name }}
                                    </strong>

                                    <br>

                                    <small>
                                        {{ optional($row['days'][$day]->teacher->user)->name }}
                                    </small>

                                </div>

                            {{-- EMPTY --}}
                            @else

                                <span class="text-muted">—</span>

                            @endif

                        </td>

                    @endforeach

                </tr>

            @endforeach

            </tbody>

        </table>

        @else

            <div class="alert alert-info text-center">
                Select class and section to view timetable
            </div>

        @endif

    </div>

</div>

@stop


@section('js')

<script>
document.getElementById('class_id').addEventListener('change', function () {

    let classId = this.value;

    fetch('/get-sections/' + classId)
        .then(res => res.json())
        .then(data => {

            let sectionDropdown = document.getElementById('section_id');
            sectionDropdown.innerHTML = '<option value="">Select Section</option>';

            data.forEach(section => {
                sectionDropdown.innerHTML += 
                    `<option value="${section.id}">${section.name}</option>`;
            });

        });

});
</script>

@stop