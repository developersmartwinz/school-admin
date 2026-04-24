{{-- resources/views/admin/notices/create.blade.php --}}

@extends('adminlte::page')

@section('title', 'Add Notice')

@section('content_header')
    <h1>Add Notice</h1>
@stop

@section('content')

<form
    method="POST"
    action="/notices/store"
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

                {{-- Sent By --}}
                <div class="col-md-6 mb-3">
                    <label>Sent By</label>
                    <input
                        type="text"
                        name="sent_by"
                        class="form-control"
                        value="{{ old('sent_by') }}"
                        placeholder="Example: Principal / Teacher Name"
                    >
                </div>

                {{-- Notice Date --}}
                <div class="col-md-4 mb-3">
                    <label>Notice Date <span class="text-danger">*</span></label>
                    <input
                        type="date"
                        name="notice_date"
                        class="form-control"
                        value="{{ old('notice_date', date('Y-m-d')) }}"
                        required
                    >
                </div>

                {{-- Notice Time --}}
                <div class="col-md-4 mb-3">
                    <label>Notice Time</label>
                    <input
                        type="time"
                        name="notice_time"
                        class="form-control"
                        value="{{ old('notice_time') }}"
                    >
                </div>

                {{-- Status --}}
                <div class="col-md-4 mb-3">
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

                {{-- Attachment --}}
                <div class="col-md-6 mb-3">
                    <label>Attachment (PDF / Image / Doc)</label>
                    <input
                        type="file"
                        name="attachment"
                        class="form-control"
                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                    >
                </div>

                {{-- Description --}}
                <div class="col-md-12 mb-3">
                    <label>Description</label>
                    <textarea
                        name="description"
                        class="form-control"
                        rows="5"
                        placeholder="Enter notice details..."
                    >{{ old('description') }}</textarea>
                </div>

            </div>

        </div>

        <div class="card-footer">

            <button class="btn btn-success">
                <i class="fas fa-save"></i> Save Notice
            </button>

            <a href="/notices" class="btn btn-secondary">
                Back
            </a>

        </div>

    </div>

</form>

@stop