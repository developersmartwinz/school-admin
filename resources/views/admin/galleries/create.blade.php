{{-- resources/views/admin/galleries/create.blade.php --}}

@extends('adminlte::page')

@section('title', 'Add Gallery')

@section('content_header')
    <h1>Add Gallery</h1>
@stop

@section('content')

<form
    method="POST"
    action="/galleries/store"
    enctype="multipart/form-data"
>
    @csrf

    <div class="card shadow-sm">

        <div class="card-body">

            <div class="row">

                {{-- Title --}}
                <div class="col-md-6 mb-3">
                    <label>Title <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        value="{{ old('title') }}"
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
                        value="{{ old('category') }}"
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
                        value="{{ old('event_date') }}"
                    >
                </div>

                {{-- Status --}}
                <div class="col-md-6 mb-3">
                    <label>Status <span class="text-danger">*</span></label>
                    <select
                        name="status"
                        class="form-control"
                        required
                    >
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                {{-- Multiple Images --}}
                <div class="col-md-12 mb-3">
                    <label>
                        Upload Images
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="file"
                        name="images[]"
                        class="form-control"
                        multiple
                        accept=".jpg,.jpeg,.png"
                        required
                    >

                    <small class="text-muted">
                        You can select multiple images
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
                    >{{ old('description') }}</textarea>
                </div>

            </div>

        </div>

        <div class="card-footer">

            <button class="btn btn-success">
                <i class="fas fa-save"></i> Save Gallery
            </button>

            <a href="/galleries" class="btn btn-secondary">
                Back
            </a>

        </div>

    </div>

</form>

@stop