@extends('adminlte::page')

@section('title', 'Students')

@section('content_header')
    <h1>Students</h1>
@stop

@section('content')

<div class="card shadow-sm">

    <!-- HEADER -->
    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">Student List</h5>

        <div class="d-flex gap-2" data-aos="flip-left">
            <a href="/students/create" class="btn btn-success">
                <i class="fas fa-plus"></i> Add
            </a>

            <button class="btn btn-danger" onclick="bulkDelete()">
                <i class="fas fa-trash"></i> Delete
            </button>
        </div>
    </div>

    <!-- FILTER -->
    <div class="card-body">

        <form method="GET" class="mb-3 d-flex gap-2" data-aos="fade-right">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control" placeholder="Search name or phone">

            <button class="btn btn-primary">
                <i class="fas fa-search"></i>
            </button>
        </form>

        <form id="bulkForm" method="POST" action="/students/bulk-delete" >
            @csrf

            <table class="table table-bordered table-hover text-center align-middle" data-aos="zoom-in">
                <thead class="bg-dark text-white">
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td>
                                <input type="checkbox" name="ids[]" value="{{ $student->id }}">
                            </td>

                            <td>{{ $student->id }}</td>
                            <td>{{ $student->user->name }}</td>
                            <td>{{ $student->user->phone }}</td>

                            <td>
                                <div class="btn-group">
                                    <a href="/students/edit/{{ $student->id }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <button class="btn btn-sm btn-outline-danger"
                                        onclick="deleteItem({{ $student->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <form id="delete-form-{{ $student->id }}"
                                      method="POST"
                                      action="/students/delete/{{ $student->id }}"
                                      style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </form>

        {{ $students->links() }}

    </div>
</div>

@stop

@section('js')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('selectAll').onclick = function() {
    document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = this.checked);
};

function deleteItem(id) {
    Swal.fire({
        title: 'Delete student?',
        icon: 'warning',
        showCancelButton: true
    }).then(res => {
        if(res.isConfirmed) {
            document.getElementById('delete-form-'+id).submit();
        }
    });
}

function bulkDelete() {
    let checked = document.querySelectorAll('input[name="ids[]"]:checked');

    if(checked.length === 0) {
        alert('Select at least one');
        return;
    }

    Swal.fire({
        title: 'Delete selected?',
        icon: 'warning',
        showCancelButton: true
    }).then(res => {
        if(res.isConfirmed) {
            document.getElementById('bulkForm').submit();
        }
    });
}
</script>

@stop