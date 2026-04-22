@extends('adminlte::page')

@section('title', 'Fee Types')

@section('content_header')
    <h1>Fee Types</h1>
@stop

@section('content')

<div class="card shadow-sm">

    {{-- HEADER --}}
    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">Fee Type List</h5>

        <div class="d-flex gap-2">
            <a href="/fee-types/create" class="btn btn-success">
                <i class="fas fa-plus"></i> Add Fee Type
            </a>

            <button
                type="button"
                class="btn btn-danger"
                onclick="bulkDeleteConfirm()"
            >
                <i class="fas fa-trash"></i> Delete Selected
            </button>
        </div>

    </div>

    {{-- BODY --}}
    <div class="card-body">

        {{-- Search --}}
        <form
            method="GET"
            action="/fee-types"
            class="mb-3 d-flex gap-2"
        >
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                class="form-control"
                placeholder="Search fee type name"
            >

            <button class="btn btn-primary">
                <i class="fas fa-search"></i>
            </button>

            <a href="/fee-types" class="btn btn-secondary">
                Reset
            </a>
        </form>

        {{-- Bulk Delete Form --}}
        <form
            method="POST"
            action="/fee-types/bulk-delete"
            id="bulkForm"
        >
            @csrf

            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">

                    <thead class="bg-dark text-white">
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>#</th>
                            <th>Fee Type Name</th>
                            <th>Default Amount</th>
                            <th>Description</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($feeTypes as $row)
                            <tr>

                                <td>
                                    <input
                                        type="checkbox"
                                        name="ids[]"
                                        value="{{ $row->id }}"
                                    >
                                </td>

                                <td>
                                    {{ ($feeTypes->currentPage() - 1) * $feeTypes->perPage() + $loop->iteration }}
                                </td>

                                <td>
                                    <strong>{{ $row->name }}</strong>
                                </td>

                                <td>
                                    ₹ {{ number_format($row->amount, 2) }}
                                </td>

                                <td>
                                    {{ $row->description ?? '-' }}
                                </td>

                                <td>
                                    <div class="btn-group">

                                        <a
                                            href="/fee-types/edit/{{ $row->id }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="deleteConfirm('/fee-types/delete/{{ $row->id }}')"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    No fee types found
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

        </form>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $feeTypes->appends(request()->query())->links() }}
        </div>

        {{-- Hidden Delete Form --}}
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
        title: 'Delete fee type?',
        text: "This fee type will be deleted!",
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
            text: 'Please select at least one fee type.'
        });
        return;
    }

    Swal.fire({
        title: 'Delete selected fee types?',
        text: "Selected fee types will be deleted!",
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