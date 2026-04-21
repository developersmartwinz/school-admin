@extends('adminlte::page')

@section('content')

<form method="POST" action="/teachers/store">
@csrf

<div class="card">
    <div class="card-body">

        <input name="name" placeholder="Name" class="form-control mb-2" required>
        <input name="email" placeholder="Email" class="form-control mb-2" required>
        <input name="password" type="password" placeholder="Password" class="form-control mb-2">

        <input name="subject" placeholder="Subject" class="form-control mb-2">
        <input name="designation" placeholder="Designation" class="form-control mb-2">

    </div>

    <div class="card-footer">
        <button class="btn btn-success">Save</button>
    </div>
</div>

</form>

@stop