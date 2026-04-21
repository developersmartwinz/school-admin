@extends('adminlte::page')

@section('title', 'Subjects')

@section('content_header')
    <h1>Subjects</h1>
@stop

@section('content')

<div class="card shadow-sm">

    <div class="card-header d-flex justify-content-between">
        <h5>Subjects</h5>

        		<div class="d-flex gap-2">
    <a href="/subjects/create" class="btn btn-success">
        <i class="fas fa-plus"></i> Add
    </a>

    <button class="btn btn-danger" onclick="bulkDelete()">
        <i class="fas fa-trash"></i> Delete
    </button>
</div>
    </div>

    <div class="card-body">

        <!-- SEARCH -->
        <form method="GET" class="mb-3 d-flex gap-2">
            <input type="text" name="search"
                   value="{{ request('search') }}"
                   class="form-control"
                   placeholder="Search subject">

            <button class="btn btn-primary">
                <i class="fas fa-search"></i>
            </button>
        </form>


        <form id="bulkForm" method="POST" action="/subjects/bulk-delete">
@csrf

<table class="table table-bordered table-hover text-center align-middle">
    <thead class="bg-dark text-white">
        <tr>
            <th><input type="checkbox" id="selectAll"></th>
            <th>ID</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        @foreach($subjects as $subject)
        <tr>
            <td>
                <input type="checkbox" name="ids[]" value="{{ $subject->id }}">
            </td>

            <td>{{ $subject->id }}</td>
            <td>{{ $subject->name }}</td>

            <td>
                <div class="btn-group">
                    <a href="/subjects/edit/{{ $subject->id }}"
                       class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit"></i>
                    </a>

                    <button type="button"
                        class="btn btn-sm btn-outline-danger"
                        onclick="deleteItem({{ $subject->id }})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <form id="delete-form-{{ $subject->id }}"
                      method="POST"
                      action="/subjects/delete/{{ $subject->id }}"
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

        {{ $subjects->links() }}

    </div>
</div>

@stop

@section('js')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

// Select all
document.getElementById('selectAll').onclick = function() {
    document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = this.checked);
};

// Single delete
function deleteItem(id) {
    Swal.fire({
        title: 'Delete subject?',
        icon: 'warning',
        showCancelButton: true
    }).then(res => {
        if(res.isConfirmed) {
            document.getElementById('delete-form-'+id).submit();
        }
    });
}

// Bulk delete
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