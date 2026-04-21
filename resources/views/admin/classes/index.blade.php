@extends('adminlte::page')

@section('title', 'Classes')

@section('content_header')
    <h1>Classes</h1>
@stop

@section('content')



<div class="card">

	    <div class="card-header d-flex justify-content-between align-items-center">

			<h5 class="mb-0">Classes List</h5>

			<div class="btn-group">
				<a href="/classes/create" class="btn btn-success mr-2">
					<i class="fas fa-plus"></i> Add Class
				</a>

				<button class="btn btn-danger" onclick="bulkDelete()">
					<i class="fas fa-trash"></i> Delete
				</button>
			</div>

		</div>

    <div class="card-body">

        <form id="bulkForm" method="POST" action="/classes/bulk-delete">
            @csrf

            <table class="table table-striped table-hover">
                <thead class="bg-dark text-white">
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>ID</th>
                        <th>Class Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($classes as $class)
                        <tr>
                            <td>
                                <input type="checkbox" name="ids[]" value="{{ $class->id }}">
                            </td>
                            <td>{{ $class->id }}</td>
                            <td>{{ $class->name }}</td>
                            <td>
                                
								
								 <div class="btn-group">
									<a href="/classes/edit/{{ $class->id }}" class="btn btn-sm btn-outline-primary">
										<i class="fas fa-edit"></i>
									</a>

									<button type="button" class="btn btn-sm btn-outline-danger"
										onclick="deleteItem({{ $class->id }})">
										<i class="fas fa-trash"></i>
									</button>
								</div>

                                <form id="delete-form-{{ $class->id }}" method="POST"
                                      action="/classes/delete/{{ $class->id }}" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </form>

        <div class="mt-3">
            {{ $classes->links() }}
        </div>

    </div>
</div>

@stop

@section('js')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Select all
document.getElementById('selectAll').addEventListener('click', function() {
    document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = this.checked);
});

// Single delete
function deleteItem(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This class will be deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}

// Bulk delete
function bulkDelete() {
    let checked = document.querySelectorAll('input[name="ids[]"]:checked');

    if (checked.length === 0) {
        alert('Select at least one record');
        return;
    }

    Swal.fire({
        title: 'Delete selected?',
        text: "Multiple classes will be deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete all'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('bulkForm').submit();
        }
    });
}
</script>

@stop