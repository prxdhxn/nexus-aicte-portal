@extends('layouts.app')

@section('title', 'Manage Users - Portal')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 mt-3">
    <h3 class="fw-bold m-0">System Users</h3>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary fw-bold">+ Add New User</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="ps-4">{{ $user->id }}</td>
                        <td class="fw-medium">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge bg-danger">Admin</span>
                            @elseif($user->role === 'sme')
                                <span class="badge bg-info text-dark">SME</span>
                            @else
                                <span class="badge bg-secondary">Institute</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete user {{ $user->name }}?')">Delete</button>
                                </form>
                            @else
                                <button class="btn btn-sm btn-outline-secondary" disabled>Current User</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
