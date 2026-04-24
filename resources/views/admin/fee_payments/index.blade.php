@extends('adminlte::page')

@section('title', 'Fee Payments')

@section('content_header')
    <h1>Fee Payments</h1>
@stop

@section('content')

<div class="card shadow-sm">

    {{-- HEADER --}}
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Payment History</h5>

        <div class="d-flex">
            <a href="/fee-payments/create" class="btn btn-primary mr-2">
                <i class="fas fa-plus"></i> Collect Fee
            </a>

            <button
                type="button"
                class="btn btn-danger"
                onclick="bulkDeleteConfirm()"
            >
                <i class="fas fa-trash"></i> Delete Selected
            </button>
        </div>
    </div>

    {{-- BODY --}}
    <div class="card-body">

        {{-- Search + Filter --}}
        <form
            method="GET"
            action="/fee-payments"
            class="mb-4"
        >
            <div class="row">

                {{-- Search --}}
                <div class="col-md-4">
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search receipt no / student name..."
                        value="{{ request('search') }}"
                    >
                </div>

                {{-- Payment Mode --}}
                <div class="col-md-2">
                    <select name="payment_mode" class="form-control">
                        <option value="">All Modes</option>
                        <option value="cash" {{ request('payment_mode') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="upi" {{ request('payment_mode') == 'upi' ? 'selected' : '' }}>UPI</option>
                        <option value="card" {{ request('payment_mode') == 'card' ? 'selected' : '' }}>Card</option>
                        <option value="bank_transfer" {{ request('payment_mode') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="online" {{ request('payment_mode') == 'online' ? 'selected' : '' }}>Online</option>
                    </select>
                </div>

                {{-- Status --}}
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>

                {{-- Filter --}}
                <div class="col-md-2">
                    <button class="btn btn-info w-100">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>

                {{-- Reset --}}
                <div class="col-md-2">
                    <a
                        href="/fee-payments"
                        class="btn btn-secondary w-100"
                    >
                        Reset
                    </a>
                </div>

            </div>
        </form>

        {{-- Bulk Delete Form --}}
        <form
            method="POST"
            action="/fee-payments/bulk-delete"
            id="bulkForm"
        >
            @csrf

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
                            <th>Total Paid</th>
                            <th>Payment Mode</th>
                            <th>Transaction ID</th>
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

                                <td>
                                    {{ ($feePayments->currentPage() - 1) * $feePayments->perPage() + $loop->iteration }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $row->receipt_no }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $row->student->user->name ?? '' }}
                                </td>

                                <td>
                                    ₹ {{ number_format($row->total_paid, 2) }}
                                </td>

                                <td>
                                    {{ ucfirst(str_replace('_', ' ', $row->payment_mode)) }}
                                </td>

                                <td>
                                    {{ $row->transaction_id ?? '-' }}
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
                                    <div class="btn-group">

                                        <a
                                            href="/fee-payments/edit/{{ $row->id }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="deleteConfirm('/fee-payments/delete/{{ $row->id }}')"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </div>
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

        </form>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $feePayments->appends(request()->query())->links() }}
        </div>

        {{-- Hidden Single Delete Form --}}
        <form
            id="delete-form"
            method="POST"
            style="display:none;"
        >
            @csrf
            @method('DELETE')
        </form>

    </div>
</div>

@stop

@section('js')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
/*
|--------------------------------------------------------------------------
| Select All
|--------------------------------------------------------------------------
*/
document.getElementById('select-all').addEventListener('click', function () {
    let checkboxes = document.querySelectorAll(
        'input[name="ids[]"]'
    );

    checkboxes.forEach((checkbox) => {
        checkbox.checked = this.checked;
    });
});


/*
|--------------------------------------------------------------------------
| Single Delete
|--------------------------------------------------------------------------
*/
function deleteConfirm(url) {
    Swal.fire({
        title: 'Delete payment?',
        text: "This fee payment record will be deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.getElementById('delete-form');
            form.action = url;
            form.submit();
        }
    });
}


/*
|--------------------------------------------------------------------------
| Bulk Delete
|--------------------------------------------------------------------------
*/
function bulkDeleteConfirm() {
    let checked = document.querySelectorAll(
        'input[name="ids[]"]:checked'
    );

    if (checked.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No Selection',
            text: 'Please select at least one payment.'
        });
        return;
    }

    Swal.fire({
        title: 'Delete selected payments?',
        text: "Selected payment records will be deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete all!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('bulkForm').submit();
        }
    });
}
</script>

@stop