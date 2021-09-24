@extends('index')

@section('content')
    <nav class='mt-3' aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ route('users.index') }}">Users</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
        </ol>
    </nav>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.update', $user) }}" method="post">
        @csrf
        @method('patch')
        <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" name="name" value="{{ $user->name }}">
        </div>
        <div class="form-group">
            <label>Email address</label>
            <input type="email" class="form-control" name="email" value="{{ $user->email }}">
        </div>
        <div class="form-group">
            <label for="exampleFormControlSelect1">Role</label>
            <select class="form-control" name="role">
                @foreach($roles as $role)
                    <option @if($role->name == $user->role->name) selected="selected" @endif>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <button type="submit" value="edit" class="btn btn-primary">Edit user</button>
        </div>
    </form>
@endsection