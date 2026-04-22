@extends('adminlte::page')

@section('title', 'Create Fee Type')

@section('content_header')
    <h1>Create Fee Type</h1>
@stop

@section('content')

<form method="POST" action="/fee-types/store">
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
                        <option value="">Select Frequency</option>
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="half_yearly">Half Yearly</option>
                        <option value="yearly">Yearly</option>
                        <option value="one_time">One Time</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label>Optional Fee</label><br>

                    <input
                        type="checkbox"
                        name="is_optional"
                        value="1"
                    >
                    Yes
                </div>

                <div class="col-md-12 mt-3">
                    <label>Description</label>
                    <textarea
                        name="description"
                        class="form-control"
                        rows="4"
                    ></textarea>
                </div>

            </div>

        </div>

        <div class="card-footer">
            <button class="btn btn-success">
                <i class="fas fa-save"></i> Save
            </button>

            <a href="/fee-types" class="btn btn-secondary">
                Back
            </a>
        </div>
    </div>

</form>

@stop