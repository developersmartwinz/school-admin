@extends('adminlte::page')

@section('title', 'Notice Board')

@section('content_header')
    <h1>Notice Board</h1>
@stop

@section('content')

<div class="card shadow-sm">

    {{-- Header --}}
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Notice List</h5>

        <div class="d-flex">
            <a href="/notices/create" class="btn btn-success mr-2">
                <i class="fas fa-plus"></i> Add Notice
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

    {{-- Body --}}
    <div class="card-body">

        {{-- Filters --}}
        <form method="GET" action="/notices" class="mb-4">
            <div class="row">

                <div class="col-md-4">
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search title / description / sender..."
                        value="{{ request('search') }}"
                    >
                </div>

                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>

                        <option
                            value="active"
                            {{ request('status') == 'active' ? 'selected' : '' }}
                        >
                            Active
                        </option>

                        <option
                            value="inactive"
                            {{ request('status') == 'inactive' ? 'selected' : '' }}
                        >
                            Inactive
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-info w-100">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>

                <div class="col-md-2">
                    <a
                        href="/notices"
                        class="btn btn-secondary w-100"
                    >
                        Reset
                    </a>
                </div>

            </div>
        </form>

        {{-- Bulk Delete Form --}}
        <form
            method="POST"
            action="/notices/bulk-delete"
            id="bulkForm"
        >
            @csrf

            <div class="table-responsive">
                <table class="table table-bordered table-hover">

                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>#</th>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Sent By</th>
                            <th>Attachment</th>
                            <th>Status</th>
                            <th width="180">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($notices as $row)
                            <tr>

                                <td>
                                    <input
                                        type="checkbox"
                                        name="ids[]"
                                        value="{{ $row->id }}"
                                    >
                                </td>

                                <td>
                                    {{ ($notices->currentPage() - 1) * $notices->perPage() + $loop->iteration }}
                                </td>

                                <td>
                                    <strong>{{ $row->title }}</strong>
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($row->notice_date)->format('d M Y') }}
                                </td>

                                <td>
                                    {{ $row->notice_time ?? '-' }}
                                </td>

                                <td>
                                    {{ $row->sent_by ?? '-' }}
                                </td>

                                <td>
                                    @if($row->attachment)
                                        <a
                                            href="{{ asset('uploads/notices/' . $row->attachment) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-info"
                                        >
                                            View File
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    @if($row->status == 'active')
                                        <span class="badge badge-success">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="btn-group">

                                        <a
                                            href="/notices/edit/{{ $row->id }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="deleteConfirm('/notices/delete/{{ $row->id }}')"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">
                                    No notices found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

        </form>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $notices->appends(request()->query())->links() }}
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
| Select All Checkbox
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
        title: 'Delete notice?',
        text: "This notice will be deleted!",
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
            text: 'Please select at least one notice.'
        });
        return;
    }

    Swal.fire({
        title: 'Delete selected notices?',
        text: "Selected notices will be deleted!",
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