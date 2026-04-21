@extends('adminlte::page')

@section('title', 'Class Subjects')

@section('content_header')
    <h1>Class Subjects</h1>
@stop

@section('content')

<div class="card shadow-sm">

    <!-- HEADER -->
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Class Subject Mapping</h5>

        <a href="/class-subjects/create" class="btn btn-success">
            <i class="fas fa-plus"></i> Assign Subjects
        </a>
    </div>

    <!-- BODY -->
    <div class="card-body">

        <table class="table table-bordered table-hover text-center align-middle">

            <thead class="bg-dark text-white">
                <tr>
                    <th>Class</th>
                    <th>Subjects</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

            @forelse($classes as $class)

                <tr>
                    <td>{{ $class->name }}</td>

                    <td>
                        @forelse($class->classSubjects as $cs)
                            <span class="badge bg-primary mr-1">
                                {{ $cs->subject->name }}
                            </span>
                        @empty
                            <span class="text-muted">No Subjects</span>
                        @endforelse
                    </td>

                    <td>
                        <div class="btn-group">

                            <!-- EDIT -->
                            <a href="/class-subjects/edit/{{ $class->id }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- DELETE -->
                            <form method="POST" action="/class-subjects/delete/{{ $class->id }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Delete all subjects for this class?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>

            @empty
                <tr>
                    <td colspan="3">No data found</td>
                </tr>
            @endforelse

            </tbody>

        </table>

        <!-- PAGINATION -->
        <div class="d-flex justify-content-end mt-3">
            {{ $classes->links() }}
        </div>

    </div>

</div>

@stop