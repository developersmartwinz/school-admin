@extends('adminlte::page')

@section('title', 'Teacher Assignments')

@section('content_header')
    <h1>Teacher Assignments</h1>
@stop

@section('content')

<div class="card shadow-sm">

    <!-- HEADER -->
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Assignments List</h5>

        <div class="d-flex">
            <a href="/teacher-assignments/create" class="btn btn-success mr-2">
                <i class="fas fa-plus"></i> Add
            </a>

            <button class="btn btn-danger" onclick="bulkDelete()">
                <i class="fas fa-trash"></i> Delete
            </button>
        </div>
    </div>

    <!-- BODY -->
    <div class="card-body">

        <form id="bulkForm" method="POST" action="/teacher-assignments/bulk-delete">
            @csrf

            <table class="table table-bordered table-hover text-center align-middle">
                <thead class="bg-dark text-white">
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Teacher</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Subjects</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                @foreach($assignments as $group)

                    @php
                        $first = $group->first();

                        $key = $first->teacher_id . '|' . 
                               $first->class_id . '|' . 
                               $first->section_id;
                    @endphp

                    <tr>

                        <!-- CHECKBOX -->
                        <td>
                            <input type="checkbox" name="groups[]" value="{{ $key }}">
                        </td>

                        <!-- DATA -->
                        <td>{{ $first->teacher->user->name }}</td>
                        <td>{{ $first->class->name }}</td>
                        <td>{{ $first->section->name }}</td>

                        <!-- SUBJECTS -->
                        <td>
                            @foreach($group as $item)
                                <span class="badge bg-primary mr-1">
                                    {{ $item->subject->name }}
                                </span>
                            @endforeach
                        </td>

                        <!-- ACTIONS -->
                        <td>
                            <div class="btn-group">

                                <!-- EDIT -->
                                <a href="/teacher-assignments/edit/{{ $first->teacher_id }}/{{ $first->class_id }}/{{ $first->section_id }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- DELETE -->
                                <button type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="deleteGroup('{{ $key }}')">
                                    <i class="fas fa-trash"></i>
                                </button>

                            </div>
                        </td>

                    </tr>

                @endforeach

                </tbody>
            </table>

        </form>

    </div>
</div>

@stop


@section('js')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

// ✅ Select All
document.getElementById('selectAll').onclick = function() {
    document.querySelectorAll('input[name="groups[]"]').forEach(cb => cb.checked = this.checked);
};


// ✅ Bulk Delete
function bulkDelete() {
    let checked = document.querySelectorAll('input[name="groups[]"]:checked');

    if (checked.length === 0) {
        alert('Select at least one');
        return;
    }

    Swal.fire({
        title: 'Delete selected assignments?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete'
    }).then(res => {
        if (res.isConfirmed) {
            document.getElementById('bulkForm').submit();
        }
    });
}


// ✅ Single Delete (Group)
function deleteGroup(key) {
    Swal.fire({
        title: 'Delete this assignment?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete'
    }).then(res => {
        if (res.isConfirmed) {

            let form = document.createElement('form');
            form.method = 'POST';
            form.action = '/teacher-assignments/bulk-delete';

            let csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';

            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'groups[]';
            input.value = key;

            form.appendChild(csrf);
            form.appendChild(input);

            document.body.appendChild(form);
            form.submit();
        }
    });
}

</script>

@stop