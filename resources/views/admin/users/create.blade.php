@extends('layouts.app')

@section('title', 'Add User - Portal')

@section('content')
<div class="row align-items-center mb-4 mt-3">
    <div class="col">
        <h3 class="fw-bold m-0">Add New User</h3>
    </div>
    <div class="col-auto">
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary fw-bold">Back to Users</a>
    </div>
</div>

<div class="card shadow-sm col-md-8 mx-auto">
    <div class="card-body p-4">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label fw-bold">Full Name</label>
                <input type="text" name="name" id="name" class="form-control px-3" value="{{ old('name') }}" required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Email Address</label>
                <input type="email" name="email" id="email" class="form-control px-3" value="{{ old('email') }}" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label fw-bold">Temporary Password</label>
                <input type="password" name="password" id="password" class="form-control px-3" required minlength="6">
            </div>

            <div class="mb-4">
                <label for="role" class="form-label fw-bold">System Role</label>
                <select name="role" id="role" class="form-select px-3" required>
                    <option value="" disabled selected>Select Role...</option>
                    <option value="institute" {{ old('role') === 'institute' ? 'selected' : '' }}>Institute</option>
                    <option value="sme" {{ old('role') === 'sme' ? 'selected' : '' }}>SME</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            
            <hr class="my-4">
            
            <button class="btn btn-primary fw-bold px-4 py-2" type="submit">Create User</button>
        </form>
    </div>
</div>
@endsection
