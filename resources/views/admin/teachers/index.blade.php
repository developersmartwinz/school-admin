@extends('adminlte::page')

@section('title', 'Teachers')

@section('content_header')
    <h1>Teachers</h1>
@stop

@section('content')

<div class="card shadow-sm">

    {{-- HEADER --}}
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Teacher List</h5>

        <div class="d-flex" data-aos="flip-left">
            <a href="/teachers/create" class="btn btn-success mr-2">
                <i class="fas fa-plus"></i> Add
            </a>

            <button
                type="button"
                class="btn btn-danger"
                onclick="bulkDeleteConfirm()"
            >
                <i class="fas fa-trash"></i> Delete
            </button>
        </div>
    </div>

    {{-- BODY --}}
    <div class="card-body">

        {{-- Search --}}
        <form
            method="GET"
            class="mb-3 d-flex gap-2"
            data-aos="fade-right"
        >
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                class="form-control"
                placeholder="Search name or phone"
            >

            <button class="btn btn-primary">
                <i class="fas fa-search"></i>
            </button>
        </form>

        {{-- Bulk Delete Form --}}
        <form
            id="bulkForm"
            method="POST"
            action="/teachers/bulk-delete"
        >
            @csrf

            <div class="table-responsive">
                <table
                    class="table table-bordered table-hover text-center align-middle"
                    data-aos="zoom-in"
                >
                    <thead class="bg-dark text-white">
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($teachers as $teacher)
                            <tr>

                                <td>
                                    <input
                                        type="checkbox"
                                        name="ids[]"
                                        value="{{ $teacher->id }}"
                                    >
                                </td>

                                <td>{{ $teacher->id }}</td>

                                <td>
                                    {{ $teacher->user->name ?? '' }}
                                </td>

                                <td>
                                    {{ $teacher->user->phone ?? '' }}
                                </td>

                                <td>
                                    <div class="btn-group">

                                        <a
                                            href="/teachers/edit/{{ $teacher->id }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="deleteConfirm('/teachers/delete/{{ $teacher->id }}')"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    No teachers found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </form>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $teachers->links() }}
        </div>

        {{-- Hidden Single Delete Form --}}
        <form
            id="delete-form"
            method="POST"
            style="display:none;"
        >
            @csrf
            @method('DELETE')
        </form>

    </div>
</div>

@stop

@section('js')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
/*
|--------------------------------------------------------------------------
| Select All
|--------------------------------------------------------------------------
*/
document.getElementById('selectAll').addEventListener('click', function () {
    let checkboxes = document.querySelectorAll(
        'input[name="ids[]"]'
    );

    checkboxes.forEach((checkbox) => {
        checkbox.checked = this.checked;
    });
});


/*
|--------------------------------------------------------------------------
| Single Delete
|--------------------------------------------------------------------------
*/
function deleteConfirm(url) {
    Swal.fire({
        title: 'Delete teacher?',
        text: "This teacher record will be deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.getElementById('delete-form');
            form.action = url;
            form.submit();
        }
    });
}


/*
|--------------------------------------------------------------------------
| Bulk Delete
|--------------------------------------------------------------------------
*/
function bulkDeleteConfirm() {
    let checked = document.querySelectorAll(
        'input[name="ids[]"]:checked'
    );

    if (checked.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No Selection',
            text: 'Please select at least one teacher.'
        });
        return;
    }

    Swal.fire({
        title: 'Delete selected teachers?',
        text: "Selected teacher records will be deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete all!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('bulkForm').submit();
        }
    });
}
</script>

@stop