@extends('adminlte::page')

@section('title', 'Fee Payments')

@section('content_header')
    <h1>Fee Payments</h1>
@stop

@section('content')

<div class="card shadow-sm">

    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Payment History</h3>

        <a href="/fee-payments/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Collect Fee
        </a>
    </div>

    <div class="card-body">

        {{-- Search --}}
        <form method="GET" action="/fee-payments" class="mb-3">
            <div class="row">

                <div class="col-md-4">
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search receipt no / student name..."
                        value="{{ request('search') }}"
                    >
                </div>

                <div class="col-md-2">
                    <button class="btn btn-info">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>

            </div>
        </form>

        {{-- Bulk Delete --}}
        <form method="POST" action="/fee-payments/bulk-delete">
            @csrf

            <button
                type="submit"
                class="btn btn-danger mb-3"
                onclick="return confirm('Delete selected payments?')"
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
                            <th>Receipt No</th>
                            <th>Student</th>
                            <th>Fee Type</th>
                            <th>Paid Amount</th>
                            <th>Payment Mode</th>
                            <th>Payment Date</th>
                            <th>Status</th>
                            <th width="180">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($feePayments as $row)
                            <tr>

                                <td>
                                    <input
                                        type="checkbox"
                                        name="ids[]"
                                        value="{{ $row->id }}"
                                    >
                                </td>

                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <strong>{{ $row->receipt_no }}</strong>
                                </td>

                                <td>
                                    {{ $row->studentFee->student->user->name ?? '' }}
                                </td>

                                <td>
                                    {{ $row->studentFee->feeType->name ?? '' }}
                                </td>

                                <td>
                                    ₹ {{ number_format($row->paid_amount, 2) }}
                                </td>

                                <td>
                                    {{ ucfirst(str_replace('_', ' ', $row->payment_mode)) }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($row->payment_date)->format('d M Y') }}
                                </td>

                                <td>
                                    @if($row->status == 'success')
                                        <span class="badge badge-success">
                                            Success
                                        </span>
                                    @elseif($row->status == 'pending')
                                        <span class="badge badge-warning">
                                            Pending
                                        </span>
                                    @elseif($row->status == 'failed')
                                        <span class="badge badge-danger">
                                            Failed
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">
                                            Refunded
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <a
                                        href="/fee-payments/edit/{{ $row->id }}"
                                        class="btn btn-sm btn-warning"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form
                                        action="/fee-payments/delete/{{ $row->id }}"
                                        method="POST"
                                        style="display:inline-block;"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete this payment?')"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">
                                    No payment records found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

            <div class="mt-3">
                {{ $feePayments->links() }}
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