@extends('adminlte::page')

@section('title', 'Time Slots')

@section('content_header')
<h1>Time Slots</h1>
@stop

@section('content')

<form method="POST" action="/time-slots/bulk-delete" id="bulkForm">
@csrf

<div class="card">

    <div class="card-header d-flex justify-content-between">
        <a href="/time-slots/create" class="btn btn-success">
            <i class="fas fa-plus"></i> Add Slot
        </a>

        <button type="submit" class="btn btn-danger">
            Delete Selected
        </button>
    </div>

    <div class="card-body">

        <table class="table table-bordered text-center">
            <thead class="bg-dark text-white">
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Class</th>
                    <th>Time</th>
                    <th>Type</th>
                    <th>Order</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

            @foreach($data as $row)
                <tr>
                    <td>
                        <input type="checkbox" name="ids[]" value="{{ $row->id }}">
                    </td>

                    <td>{{ $row->class->name }}</td>

                    <td>{{ $row->start_time }} - {{ $row->end_time }}</td>

                    <td>
                        @if($row->type == 'break')
                            <span class="badge bg-warning">Break</span>
                        @else
                            <span class="badge bg-primary">Class</span>
                        @endif
                    </td>

                    <td>{{ $row->order }}</td>

                    <td>
                        <a href="/time-slots/edit/{{ $row->id }}" class="btn btn-sm btn-primary">Edit</a>

                        <form method="POST" action="/time-slots/delete/{{ $row->id }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>

        {{ $data->links() }}

    </div>

</div>

</form>

@stop

@section('js')
<script>
document.getElementById('selectAll').onclick = function() {
    document.querySelectorAll('input[name="ids[]"]').forEach(el => el.checked = this.checked);
}
</script>
@stop