{{-- resources/views/admin/notices/edit.blade.php --}}

@extends('adminlte::page')

@section('title', 'Edit Notice')

@section('content_header')
    <h1>Edit Notice</h1>
@stop

@section('content')

<form
    method="POST"
    action="/notices/update/{{ $notice->id }}"
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
                        value="{{ old('title', $notice->title) }}"
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
                        value="{{ old('sent_by', $notice->sent_by) }}"
                    >
                </div>

                {{-- Notice Date --}}
                <div class="col-md-4 mb-3">
                    <label>Notice Date <span class="text-danger">*</span></label>
                    <input
                        type="date"
                        name="notice_date"
                        class="form-control"
                        value="{{ old('notice_date', $notice->notice_date) }}"
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
                        value="{{ old('notice_time', $notice->notice_time) }}"
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
                        <option
                            value="active"
                            {{ $notice->status == 'active' ? 'selected' : '' }}
                        >
                            Active
                        </option>

                        <option
                            value="inactive"
                            {{ $notice->status == 'inactive' ? 'selected' : '' }}
                        >
                            Inactive
                        </option>
                    </select>
                </div>

                {{-- Existing Attachment --}}
                <div class="col-md-6 mb-3">
                    <label>Current Attachment</label>

                    <div class="mb-2">
                        @if($notice->attachment)
                            <a
                                href="{{ asset('uploads/notices/' . $notice->attachment) }}"
                                target="_blank"
                                class="btn btn-sm btn-info"
                            >
                                View Current File
                            </a>
                        @else
                            <p class="text-muted mb-0">
                                No attachment uploaded
                            </p>
                        @endif
                    </div>

                    <label>Change Attachment</label>
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
                    >{{ old('description', $notice->description) }}</textarea>
                </div>

            </div>

        </div>

        <div class="card-footer">

            <button class="btn btn-success">
                <i class="fas fa-save"></i> Update Notice
            </button>

            <a href="/notices" class="btn btn-secondary">
                Back
            </a>

        </div>

    </div>

</form>

@stop