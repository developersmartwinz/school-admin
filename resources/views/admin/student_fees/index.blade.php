@extends('adminlte::page')

@section('title', 'Student Fee Assignments')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1>Student Fee Assignments</h1>

    <a href="/student-fees/create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Assign Fees
    </a>
</div>
@stop

@section('content')

<div class="card shadow-sm">
    <div class="card-body">

        {{-- Filters --}}
        <form method="GET" action="/student-fees" class="mb-4">
            <div class="row">

                <div class="col-md-3">
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search student name..."
                        value="{{ request('search') }}"
                    >
                </div>

                <div class="col-md-2">
                    <select name="class_id" class="form-control">
                        <option value="">All Classes</option>

                        @foreach($classes as $class)
                            <option
                                value="{{ $class->id }}"
                                {{ request('class_id') == $class->id ? 'selected' : '' }}
                            >
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <input
                        type="number"
                        name="year"
                        class="form-control"
                        placeholder="Year"
                        value="{{ request('year') }}"
                    >
                </div>

                <div class="col-md-2">
                    <button class="btn btn-info w-100">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>

                <div class="col-md-2">
                    <a href="/student-fees" class="btn btn-secondary w-100">
                        Reset
                    </a>
                </div>

            </div>
        </form>

        {{-- Bulk Delete --}}
        <form
            method="POST"
            action="/student-fees/bulk-delete"
            onsubmit="bulkDeleteConfirm(event, this)"
        >
            @csrf

            <button
                type="submit"
                class="btn btn-danger mb-3"
            >
                <i class="fas fa-trash"></i> Bulk Delete
            </button>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">

                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th>#</th>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Fee Type</th>
                            <th>Assigned Amount</th>
                            <th>Year</th>
                            <th width="180">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($students as $row)
                            <tr>

                                <td>
                                    <input
                                        type="checkbox"
                                        name="ids[]"
                                        value="{{ $row->id }}"
                                    >
                                </td>

                                <td>
                                    {{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $row->student->user->name ?? '' }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $row->student->class->name ?? '' }}
                                </td>

                                <td>
                                    {{ $row->student->section->name ?? '' }}
                                </td>

                                <td>
                                    {{ $row->feeType->name ?? '' }}
                                </td>

                                <td>
                                    ₹ {{ number_format($row->amount, 2) }}
                                </td>

                                <td>
                                    {{ $row->year }}
                                </td>

                                <td>
                                    <a
                                        href="/student-fees/edit/{{ $row->id }}"
                                        class="btn btn-sm btn-warning"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-danger"
                                        onclick="deleteConfirm('/student-fees/delete/{{ $row->id }}')"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">
                                    No student fee assignments found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

            <div class="mt-3">
                {{ $students->appends(request()->query())->links() }}
            </div>
        </form>

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
<script>
function deleteConfirm(url) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This fee assignment will be deleted!",
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

function bulkDeleteConfirm(event, form) {
    event.preventDefault();

    let checked = document.querySelectorAll(
        'input[name="ids[]"]:checked'
    );

    if (checked.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No Selection',
            text: 'Please select at least one record.'
        });
        return;
    }

    Swal.fire({
        title: 'Delete Selected?',
        text: "Selected fee assignments will be deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete all!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}

document.getElementById('select-all').addEventListener('click', function () {
    let checkboxes = document.querySelectorAll(
        'input[name="ids[]"]'
    );

    checkboxes.forEach((checkbox) => {
        checkbox.checked = this.checked;
    });
});
</script>
@stop