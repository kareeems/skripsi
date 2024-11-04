@extends('layouts.dashboard')

@section('breadcrumb', 'Categori Tagihan')

@section('content')
<div class="container">
    <h1>User Management</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary">Create New User</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Name</th>
                <th>Role</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>
                    @if($user->role == 'admin')
                        <span class="badge bg-danger">Admin</span>
                    @elseif($user->role == 'teacher')
                        <span class="badge bg-primary">Teacher</span>
                    @else
                        <span class="badge bg-success">Student</span>
                    @endif
                </td>
                <td>{{ $user->email }}</td>
                <td>
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
