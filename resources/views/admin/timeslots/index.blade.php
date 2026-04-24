@extends('adminlte::page')

@section('title', 'Time Slots')

@section('content_header')
    <h1>Time Slots</h1>
@stop

@section('content')

<div class="card shadow-sm">

    {{-- HEADER --}}
    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">Time Slot List</h5>

        <div class="d-flex">
            <a href="/time-slots/create" class="btn btn-success mr-2">
                <i class="fas fa-plus"></i> Add Slot
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

        {{-- Bulk Delete Form --}}
        <form
            method="POST"
            action="/time-slots/bulk-delete"
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
                            <th>Class</th>
                            <th>Time</th>
                            <th>Type</th>
                            <th>Order</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($data as $row)
                            <tr>

                                <td>
                                    <input
                                        type="checkbox"
                                        name="ids[]"
                                        value="{{ $row->id }}"
                                    >
                                </td>

                                <td>
                                    {{ $row->class->name ?? '' }}
                                </td>

                                <td>
                                    {{ $row->start_time }} - {{ $row->end_time }}
                                </td>

                                <td>
                                    @if($row->type == 'break')
                                        <span class="badge bg-warning">
                                            Break
                                        </span>
                                    @else
                                        <span class="badge bg-primary">
                                            Class
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $row->order }}
                                </td>

                                <td>
                                    <div class="btn-group">

                                        <a
                                            href="/time-slots/edit/{{ $row->id }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="deleteConfirm('/time-slots/delete/{{ $row->id }}')"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    No time slots found
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

        </form>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $data->links() }}
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
        title: 'Delete time slot?',
        text: "This time slot will be deleted!",
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
            text: 'Please select at least one time slot.'
        });
        return;
    }

    Swal.fire({
        title: 'Delete selected time slots?',
        text: "Selected time slots will be deleted!",
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