@extends('workspace.master_workspace')

@section('title', 'Delete User')

@section('body')
<div class="container-fluid text-center page-content">
    <div class="row">
        <div class="col-md-12">
            <header>
                <h1>
                    Delete User {{ $user->name }}
                </h1>
            </header>
            <p class="confirm mt-3 fs-5">
                Are you sure you want to delete this user?
            </p>
        </div>
    </div>
</div>

<div class="container-fluid text-center">
    <div class="row">
        <!-- Confirm deletion -->
        <div class="col-md-6 order-md-2 mt-4">
            <div class="card border-danger shadow-sm">
                <div class="card-header bg-danger text-white">
                    Confirm
                </div>
                <div class="card-body">
                    <p class="text-danger fw-bold">
                        This series <strong>will be permanently removed</strong> from the database.
                    </p>
                    <form method="POST" action="{{ route('user.destroy', ['id' => $user->id]) }}">
                        @csrf
                        @method('DELETE')
                        <label for="deleteSubmit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Delete
                        </label>
                        <input id="deleteSubmit" type="submit" class="d-none">
                    </form>
                </div>
            </div>
        </div>

        <!-- Cancel / go back -->
        <div class="col-md-6 order-md-1 mt-4">
            <div class="card border-secondary shadow-sm">
                <div class="card-header">
                    Revert
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        The user <strong>will not be deleted</strong>.
                    </p>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="bi bi-box-arrow-left"></i> Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
