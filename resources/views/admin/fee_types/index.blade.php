@extends('adminlte::page')

@section('title', 'Fee Types')

@section('content_header')
    <h1>Fee Types</h1>
@stop

@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Fee Type List</h3>

        <a href="/fee-types/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Fee Type
        </a>
    </div>

    <div class="card-body">

        {{-- Search + Bulk Delete --}}
        <form method="GET" action="/fee-types" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Search fee type..."
                    >
                </div>

                <div class="col-md-2">
                    <button class="btn btn-info">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </div>
        </form>

        <form method="POST" action="/fee-types/bulk-delete">
            @csrf

            <button
                type="submit"
                class="btn btn-danger mb-3"
                onclick="return confirm('Delete selected records?')"
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
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Frequency</th>
                            <th>Optional</th>
                            <th width="180">Action</th>
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

                                <td>{{ $loop->iteration }}</td>

                                <td>{{ $row->name }}</td>

                                <td>₹ {{ number_format($row->amount, 2) }}</td>

                                <td>{{ ucfirst(str_replace('_', ' ', $row->frequency)) }}</td>

                                <td>
                                    @if($row->is_optional)
                                        <span class="badge badge-success">Yes</span>
                                    @else
                                        <span class="badge badge-secondary">No</span>
                                    @endif
                                </td>

                                <td>
                                    <a
                                        href="/fee-types/edit/{{ $row->id }}"
                                        class="btn btn-sm btn-warning"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form
                                        action="/fee-types/delete/{{ $row->id }}"
                                        method="POST"
                                        style="display:inline-block;"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete this record?')"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    No records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <div class="mt-3">
                {{ $feeTypes->links() }}
            </div>

        </form>
    </div>
</div>

@stop

@section('js')
<script>
document.getElementById('select-all').addEventListener('click', function () {
    let checkboxes = document.querySelectorAll('input[name="ids[]"]');

    checkboxes.forEach(function (checkbox) {
        checkbox.checked = document.getElementById('select-all').checked;
    });
});
</script>
@stop