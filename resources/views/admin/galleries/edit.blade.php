{{-- resources/views/admin/galleries/edit.blade.php --}}

@extends('adminlte::page')

@section('title', 'Edit Gallery')

@section('content_header')
    <h1>Edit Gallery</h1>
@stop

@section('content')

<form
    method="POST"
    action="/galleries/update/{{ $gallery->id }}"
    enctype="multipart/form-data"
>
    @csrf

    <div class="card shadow-sm">

        <div class="card-body">

            <div class="row">

                {{-- Title --}}
                <div class="col-md-6 mb-3">
                    <label>
                        Title
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        value="{{ old('title', $gallery->title) }}"
                        required
                    >
                </div>

                {{-- Category --}}
                <div class="col-md-6 mb-3">
                    <label>Category</label>

                    <input
                        type="text"
                        name="category"
                        class="form-control"
                        value="{{ old('category', $gallery->category) }}"
                        placeholder="Example: Sports Day / Annual Function"
                    >
                </div>

                {{-- Event Date --}}
                <div class="col-md-6 mb-3">
                    <label>Event Date</label>

                    <input
                        type="date"
                        name="event_date"
                        class="form-control"
                        value="{{ old('event_date', $gallery->event_date) }}"
                    >
                </div>

                {{-- Status --}}
                <div class="col-md-6 mb-3">
                    <label>
                        Status
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="status"
                        class="form-control"
                        required
                    >
                        <option
                            value="active"
                            {{ $gallery->status == 'active' ? 'selected' : '' }}
                        >
                            Active
                        </option>

                        <option
                            value="inactive"
                            {{ $gallery->status == 'inactive' ? 'selected' : '' }}
                        >
                            Inactive
                        </option>
                    </select>
                </div>

                {{-- Existing Images --}}
                <div class="col-md-12 mb-4">
                    <label>Current Images</label>

                    <div class="row">

                        @forelse($gallery->images as $image)
                            <div class="col-md-3 mb-3">

                                <div class="card shadow-sm">

                                    <img
                                        src="{{ asset('uploads/galleries/' . $image->image) }}"
                                        class="img-fluid"
                                        style="height: 180px; object-fit: cover;"
                                    >

                                    <div class="card-body text-center">

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-danger"
                                            onclick="deleteImageConfirm('/galleries/delete-image/{{ $image->id }}')"
                                        >
                                            <i class="fas fa-trash"></i>
                                            Remove
                                        </button>

                                    </div>

                                </div>

                            </div>
                        @empty
                            <div class="col-md-12">
                                <p class="text-muted mb-0">
                                    No images uploaded
                                </p>
                            </div>
                        @endforelse

                    </div>
                </div>

                {{-- Add More Images --}}
                <div class="col-md-12 mb-3">
                    <label>Add More Images</label>

                    <input
                        type="file"
                        name="images[]"
                        class="form-control"
                        multiple
                        accept=".jpg,.jpeg,.png"
                    >

                    <small class="text-muted">
                        You can upload additional images
                    </small>
                </div>

                {{-- Description --}}
                <div class="col-md-12 mb-3">
                    <label>Description</label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="5"
                        placeholder="Enter gallery details..."
                    >{{ old('description', $gallery->description) }}</textarea>
                </div>

            </div>

        </div>

        <div class="card-footer">

            <button class="btn btn-success">
                <i class="fas fa-save"></i>
                Update Gallery
            </button>

            <a
                href="/galleries"
                class="btn btn-secondary"
            >
                Back
            </a>

        </div>

    </div>

</form>

{{-- Hidden Delete Image Form --}}
<form
    id="delete-image-form"
    method="POST"
    style="display:none;"
>
    @csrf
    @method('DELETE')
</form>

@stop


@section('js')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
/*
|--------------------------------------------------------------------------
| Delete Existing Image
|--------------------------------------------------------------------------
*/
function deleteImageConfirm(url) {
    Swal.fire({
        title: 'Delete image?',
        text: "This image will be permanently removed!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.getElementById('delete-image-form');
            form.action = url;
            form.submit();
        }
    });
}
</script>

@stop