@extends('adminlte::page')

@section('title', 'Edit Fee Payment')

@section('content_header')
    <h1>Edit Fee Payment</h1>
@stop

@section('content')

<form method="POST" action="/fee-payments/update/{{ $feePayment->id }}">
    @csrf

    <div class="card">
        <div class="card-body">

            <div class="row">

                {{-- Class --}}
                <div class="col-md-6">
                    <label>Class</label>
                    <select name="class_id" id="class_id" class="form-control" required>
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option
                                value="{{ $class->id }}"
                                {{ $feePayment->studentFee->student->class_id == $class->id ? 'selected' : '' }}
                            >
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Section --}}
                <div class="col-md-6">
                    <label>Section</label>
                    <select name="section_id" id="section_id" class="form-control" required>
                        <option
                            value="{{ $feePayment->studentFee->student->section_id }}"
                            selected
                        >
                            {{ $feePayment->studentFee->student->section->name ?? 'Selected Section' }}
                        </option>
                    </select>
                </div>

                {{-- Student --}}
                <div class="col-md-6 mt-3">
                    <label>Student</label>
                    <select name="student_id" id="student_id" class="form-control" required>
                        <option
                            value="{{ $feePayment->studentFee->student->id }}"
                            selected
                        >
                            {{ $feePayment->studentFee->student->user->name ?? '' }}
                        </option>
                    </select>
                </div>

                {{-- Fee Type --}}
                <div class="col-md-6 mt-3">
                    <label>Fee Type</label>
                    <select name="student_fee_id" id="student_fee_id" class="form-control" required>
                        <option
                            value="{{ $feePayment->student_fee_id }}"
                            selected
                        >
                            {{ $feePayment->studentFee->feeType->name ?? '' }}
                            (₹ {{ number_format($feePayment->studentFee->amount, 2) }})
                        </option>
                    </select>
                </div>

                {{-- Paid Amount --}}
                <div class="col-md-6 mt-3">
                    <label>Paid Amount</label>
                    <input
                        type="number"
                        step="0.01"
                        name="paid_amount"
                        class="form-control"
                        value="{{ $feePayment->paid_amount }}"
                        required
                    >
                </div>

                {{-- Payment Mode --}}
                <div class="col-md-6 mt-3">
                    <label>Payment Mode</label>
                    <select
                        name="payment_mode"
                        id="payment_mode"
                        class="form-control"
                        required
                    >
                        <option value="cash" {{ $feePayment->payment_mode == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="upi" {{ $feePayment->payment_mode == 'upi' ? 'selected' : '' }}>UPI</option>
                        <option value="card" {{ $feePayment->payment_mode == 'card' ? 'selected' : '' }}>Card</option>
                        <option value="bank_transfer" {{ $feePayment->payment_mode == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="online" {{ $feePayment->payment_mode == 'online' ? 'selected' : '' }}>Online</option>
                    </select>
                </div>

                {{-- Transaction ID --}}
                <div
                    class="col-md-6 mt-3"
                    id="transaction-box"
                    style="{{ $feePayment->transaction_id ? '' : 'display:none;' }}"
                >
                    <label>Transaction ID</label>
                    <input
                        type="text"
                        name="transaction_id"
                        class="form-control"
                        value="{{ $feePayment->transaction_id }}"
                    >
                </div>

                {{-- Payment Date --}}
                <div class="col-md-6 mt-3">
                    <label>Payment Date</label>
                    <input
                        type="date"
                        name="payment_date"
                        class="form-control"
                        value="{{ $feePayment->payment_date }}"
                        required
                    >
                </div>

                {{-- Status --}}
                <div class="col-md-6 mt-3">
                    <label>Status</label>
                    <select name="status" class="form-control" required>
                        <option value="success" {{ $feePayment->status == 'success' ? 'selected' : '' }}>Success</option>
                        <option value="pending" {{ $feePayment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ $feePayment->status == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="refunded" {{ $feePayment->status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>

                {{-- Remarks --}}
                <div class="col-md-12 mt-3">
                    <label>Remarks</label>
                    <textarea
                        name="remarks"
                        class="form-control"
                        rows="3"
                    >{{ $feePayment->remarks }}</textarea>
                </div>

            </div>

        </div>

        <div class="card-footer">
            <button class="btn btn-success">
                <i class="fas fa-save"></i> Update
            </button>

            <a href="/fee-payments" class="btn btn-secondary">
                Back
            </a>
        </div>
    </div>
</form>

@stop

@section('js')

<script>
document.getElementById('class_id').addEventListener('change', function () {
    let classId = this.value;

    fetch('/get-sections/' + classId)
        .then(res => res.json())
        .then(data => {
            let sectionDropdown = document.getElementById('section_id');

            sectionDropdown.innerHTML =
                '<option value="">Select Section</option>';

            data.forEach(section => {
                sectionDropdown.innerHTML +=
                    `<option value="${section.id}">
                        ${section.name}
                    </option>`;
            });
        });
});

document.getElementById('section_id').addEventListener('change', function () {
    let classId = document.getElementById('class_id').value;
    let sectionId = this.value;

    fetch(`/get-students/${classId}/${sectionId}`)
        .then(res => res.json())
        .then(data => {
            let studentDropdown = document.getElementById('student_id');

            studentDropdown.innerHTML =
                '<option value="">Select Student</option>';

            data.students.forEach(student => {
                studentDropdown.innerHTML +=
                    `<option value="${student.id}">
                        ${student.user.name}
                    </option>`;
            });
        });
});

document.getElementById('student_id').addEventListener('change', function () {
    let studentId = this.value;

    fetch(`/fee-payments/get-student-fees/${studentId}`)
        .then(res => res.json())
        .then(data => {
            let feeDropdown = document.getElementById('student_fee_id');

            feeDropdown.innerHTML =
                '<option value="">Select Fee Type</option>';

            data.forEach(item => {
                feeDropdown.innerHTML +=
                    `<option value="${item.id}">
                        ${item.fee_type.name}
                        (₹ ${item.amount})
                    </option>`;
            });
        });
});

document.getElementById('payment_mode').addEventListener('change', function () {
    let value = this.value;
    let box = document.getElementById('transaction-box');

    if (
        value === 'upi' ||
        value === 'card' ||
        value === 'bank_transfer' ||
        value === 'online'
    ) {
        box.style.display = 'block';
    } else {
        box.style.display = 'none';
    }
});
</script>

@stop