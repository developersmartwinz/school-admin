@extends('adminlte::page')

@section('title', 'Collect Fee')

@section('content_header')
    <h1>Collect Fee</h1>
@stop

@section('content')

<form method="POST" action="/fee-payments/store">
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
                            <option value="{{ $class->id }}">
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Section --}}
                <div class="col-md-6">
                    <label>Section</label>
                    <select name="section_id" id="section_id" class="form-control" required>
                        <option value="">Select Section</option>
                    </select>
                </div>

                {{-- Student --}}
                <div class="col-md-6 mt-3">
                    <label>Student</label>
                    <select name="student_id" id="student_id" class="form-control" required>
                        <option value="">Select Student</option>
                    </select>
                </div>

            </div>

            <hr>

            {{-- Dynamic Fee Heads --}}
            <div id="fee-heads-container">

                <div class="text-muted">
                    Select student to load assigned fee heads
                </div>

            </div>

            <hr>

            <div class="row">

                {{-- Payment Mode --}}
                <div class="col-md-6">
                    <label>Payment Mode</label>
                    <select
                        name="payment_mode"
                        id="payment_mode"
                        class="form-control"
                        required
                    >
                        <option value="">Select Payment Mode</option>
                        <option value="cash">Cash</option>
                        <option value="upi">UPI</option>
                        <option value="card">Card</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="online">Online</option>
                    </select>
                </div>

                {{-- Transaction ID --}}
                <div
                    class="col-md-6"
                    id="transaction-box"
                    style="display:none;"
                >
                    <label>Transaction ID</label>
                    <input
                        type="text"
                        name="transaction_id"
                        class="form-control"
                    >
                </div>

                {{-- Payment Date --}}
                <div class="col-md-6 mt-3">
                    <label>Payment Date</label>
                    <input
                        type="date"
                        name="payment_date"
                        class="form-control"
                        value="{{ date('Y-m-d') }}"
                        required
                    >
                </div>

                {{-- Status --}}
                <div class="col-md-6 mt-3">
                    <label>Status</label>
                    <select name="status" class="form-control" required>
                        <option value="success">Success</option>
                        <option value="pending">Pending</option>
                        <option value="failed">Failed</option>
                        <option value="refunded">Refunded</option>
                    </select>
                </div>

                {{-- Remarks --}}
                <div class="col-md-12 mt-3">
                    <label>Remarks</label>
                    <textarea
                        name="remarks"
                        class="form-control"
                        rows="3"
                    ></textarea>
                </div>

            </div>

        </div>

        <div class="card-footer">
            <button class="btn btn-success">
                <i class="fas fa-save"></i> Save Payment
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
/*
|--------------------------------------------------------------------------
| Load Sections
|--------------------------------------------------------------------------
*/
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

    resetStudents();
    resetFeeHeads();
});


/*
|--------------------------------------------------------------------------
| Load Students
|--------------------------------------------------------------------------
*/
document.getElementById('section_id').addEventListener('change', function () {
    let classId = document.getElementById('class_id').value;
    let sectionId = this.value;

    fetch(`/get-students/${classId}/${sectionId}`)
        .then(res => res.json())
        .then(data => {
            let studentDropdown = document.getElementById('student_id');

            studentDropdown.innerHTML =
                '<option value="">Select Student</option>';

            data.forEach(student => {
                studentDropdown.innerHTML +=
                    `<option value="${student.id}">
                        ${student.name}
                    </option>`;
            });
        });

    resetFeeHeads();
});


/*
|--------------------------------------------------------------------------
| Load Multiple Fee Heads
|--------------------------------------------------------------------------
*/
document.getElementById('student_id').addEventListener('change', function () {
    let studentId = this.value;

    fetch(`/get-student-fees/${studentId}`)
        .then(res => res.json())
        .then(data => {

            let container = document.getElementById('fee-heads-container');

            if (data.length === 0) {
                container.innerHTML =
                    '<div class="text-danger">No assigned fee heads found</div>';
                return;
            }

            let html = `
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Fee Type</th>
                                <th>Assigned Amount</th>
                                <th>Pay Now</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            data.forEach(item => {
                html += `
                    <tr>
                        <td>
                            ${item.fee_type.name}
                            <input
                                type="hidden"
                                name="student_fee_ids[]"
                                value="${item.id}"
                            >
                        </td>

                        <td>
                            ₹ ${item.amount}
                        </td>

                        <td>
                            <input
                                type="number"
                                step="0.01"
                                name="amount_paid[]"
                                class="form-control"
                                placeholder="Enter amount"
                            >
                        </td>
                    </tr>
                `;
            });

            html += `
                        </tbody>
                    </table>
                </div>
            `;

            container.innerHTML = html;
        });
});


/*
|--------------------------------------------------------------------------
| Payment Mode Logic
|--------------------------------------------------------------------------
*/
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


/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/
function resetStudents() {
    document.getElementById('student_id').innerHTML =
        '<option value="">Select Student</option>';
}

function resetFeeHeads() {
    document.getElementById('fee-heads-container').innerHTML =
        '<div class="text-muted">Select student to load assigned fee heads</div>';
}
</script>

@stop