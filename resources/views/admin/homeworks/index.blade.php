{{-- resources/views/admin/homeworks/index.blade.php --}}
@extends('adminlte::page')

@section('title', 'Homeworks')

@section('content_header')
    <h1>Homework Management</h1>
@stop

@section('content')

<div class="card shadow-sm">

    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Homework List</h5>

        <div class="d-flex">
            <a href="/homeworks/create" class="btn btn-success mr-2">
                <i class="fas fa-plus"></i> Add Homework
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

        {{-- Filters --}}
        <form method="GET" action="/homeworks" class="mb-4">
            <div class="row">

                <div class="col-md-3">
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search title..."
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
                    <select name="teacher_id" class="form-control">
                        <option value="">All Teachers</option>

                        @foreach($teachers as $teacher)
                            <option
                                value="{{ $teacher->id }}"
                                {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}
                            >
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <button class="btn btn-primary w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>

                <div class="col-md-2">
                    <a href="/homeworks" class="btn btn-secondary w-100">
                        Reset
                    </a>
                </div>

            </div>
        </form>

        {{-- Bulk Form --}}
        <form
            method="POST"
            action="/homeworks/bulk-delete"
            id="bulkForm"
        >
            @csrf

            <div class="table-responsive">
                <table class="table table-bordered table-hover">

                    <thead class="bg-dark text-white">
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>#</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Subject</th>
                            <th>Teacher</th>
                            <th>Assign Date</th>
                            <th>Status</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($homeworks as $row)
                            <tr>

                                <td>
                                    <input
                                        type="checkbox"
                                        name="ids[]"
                                        value="{{ $row->id }}"
                                    >
                                </td>

                                <td>{{ $loop->iteration }}</td>

                                <td>{{ $row->title }}</td>

                                <td>{{ ucfirst($row->type) }}</td>

                                <td>{{ $row->class->name ?? '' }}</td>

                                <td>{{ $row->section->name ?? '' }}</td>

                                <td>{{ $row->subject->name ?? '' }}</td>

                                <td>{{ $row->teacher->user->name ?? '' }}</td>

                                <td>{{ $row->assign_date }}</td>

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
                                        href="/homeworks/edit/{{ $row->id }}"
                                        class="btn btn-sm btn-primary"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-danger"
                                        onclick="deleteConfirm('/homeworks/delete/{{ $row->id }}')"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">
                                    No homeworks found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </form>

        <div class="mt-3">
            {{ $homeworks->links() }}
        </div>

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