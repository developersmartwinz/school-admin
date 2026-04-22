@extends('adminlte::page')

@section('title', 'Edit Fee Type')

@section('content_header')
    <h1>Edit Fee Type</h1>
@stop

@section('content')

<form method="POST" action="/fee-types/update/{{ $feeType->id }}">
    @csrf

    <div class="card">
        <div class="card-body">

            <div class="row">

                <div class="col-md-6">
                    <label>Fee Type Name</label>
                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ $feeType->name }}"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label>Amount</label>
                    <input
                        type="number"
                        step="0.01"
                        name="amount"
                        class="form-control"
                        value="{{ $feeType->amount }}"
                        required
                    >
                </div>

                <div class="col-md-6 mt-3">
                    <label>Frequency</label>
                    <select
                        name="frequency"
                        class="form-control"
                        required
                    >
                        <option value="monthly" {{ $feeType->frequency == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="quarterly" {{ $feeType->frequency == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                        <option value="half_yearly" {{ $feeType->frequency == 'half_yearly' ? 'selected' : '' }}>Half Yearly</option>
                        <option value="yearly" {{ $feeType->frequency == 'yearly' ? 'selected' : '' }}>Yearly</option>
                        <option value="one_time" {{ $feeType->frequency == 'one_time' ? 'selected' : '' }}>One Time</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label>Optional Fee</label><br>

                    <input
                        type="checkbox"
                        name="is_optional"
                        value="1"
                        {{ $feeType->is_optional ? 'checked' : '' }}
                    >
                    Yes
                </div>

                <div class="col-md-12 mt-3">
                    <label>Description</label>
                    <textarea
                        name="description"
                        class="form-control"
                        rows="4"
                    >{{ $feeType->description }}</textarea>
                </div>

            </div>

        </div>

        <div class="card-footer">
            <button class="btn btn-success">
                <i class="fas fa-save"></i> Update
            </button>

            <a href="/fee-types" class="btn btn-secondary">
                Back
            </a>
        </div>
    </div>

</form>

@stop