@extends('adminlte::page')

@section('title', 'Sections')

@section('content_header')
    <h1>Sections</h1>
@stop

@section('content')

<div class="card shadow-sm">

    {{-- HEADER --}}
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Section List</h5>

        <div class="d-flex" data-aos="flip-left">
            <a href="/sections/create" class="btn btn-success mr-2">
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
                placeholder="Search section name"
            >

            <button class="btn btn-primary">
                <i class="fas fa-search"></i>
            </button>
        </form>

        {{-- Bulk Delete Form --}}
        <form
            id="bulkForm"
            method="POST"
            action="/sections/bulk-delete"
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
                            <th>Section Name</th>
                            <th>Class</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($sections as $section)
                            <tr>

                                <td>
                                    <input
                                        type="checkbox"
                                        name="ids[]"
                                        value="{{ $section->id }}"
                                    >
                                </td>

                                <td>{{ $section->id }}</td>

                                <td>
                                    {{ $section->name ?? '' }}
                                </td>

                                <td>
                                    {{ $section->class->name ?? '' }}
                                </td>

                                <td>
                                    <div class="btn-group">

                                        <a
                                            href="/sections/edit/{{ $section->id }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="deleteConfirm('/sections/delete/{{ $section->id }}')"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    No sections found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </form>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $sections->links() }}
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
        title: 'Delete section?',
        text: "This section record will be deleted!",
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
            text: 'Please select at least one section.'
        });
        return;
    }

    Swal.fire({
        title: 'Delete selected sections?',
        text: "Selected section records will be deleted!",
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