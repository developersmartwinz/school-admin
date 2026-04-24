{{-- resources/views/admin/galleries/index.blade.php --}}

@extends('adminlte::page')

@section('title', 'Gallery')

@section('content_header')
    <h1>Gallery</h1>
@stop

@section('content')

<div class="card shadow-sm">

    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Gallery List</h5>

        <div class="d-flex">
            <a href="/galleries/create" class="btn btn-success mr-2">
                <i class="fas fa-plus"></i> Add Gallery
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

    <div class="card-body">

        <form method="GET" action="/galleries" class="mb-4">
            <div class="row">

                <div class="col-md-4">
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search title / category..."
                        value="{{ request('search') }}"
                    >
                </div>

                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="active"
                            {{ request('status') == 'active' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="inactive"
                            {{ request('status') == 'inactive' ? 'selected' : '' }}>
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
                    <a href="/galleries" class="btn btn-secondary w-100">
                        Reset
                    </a>
                </div>

            </div>
        </form>

        <form method="POST" action="/galleries/bulk-delete" id="bulkForm">
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
                            <th>Category</th>
                            <th>Event Date</th>
                            <th>Total Images</th>
                            <th>Status</th>
                            <th width="180">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($galleries as $row)
                            <tr>

                                <td>
                                    <input
                                        type="checkbox"
                                        name="ids[]"
                                        value="{{ $row->id }}"
                                    >
                                </td>

                                <td>
                                    {{ ($galleries->currentPage() - 1) * $galleries->perPage() + $loop->iteration }}
                                </td>

                                <td>
                                    <strong>{{ $row->title }}</strong>
                                </td>

                                <td>
                                    {{ $row->category ?? '-' }}
                                </td>

                                <td>
                                    {{ $row->event_date ?? '-' }}
                                </td>

                                <td>
                                    {{ $row->images->count() }}
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
                                    <a
                                        href="/galleries/edit/{{ $row->id }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="deleteConfirm('/galleries/delete/{{ $row->id }}')"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">
                                    No galleries found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

        </form>

        <div class="mt-3">
            {{ $galleries->links() }}
        </div>

        <form id="delete-form" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>

    </div>
</div>

@stop