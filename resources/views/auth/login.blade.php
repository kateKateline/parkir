@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div style="max-width: 400px; margin: 50px auto;">
    <div class="content">
        <h2 style="margin-bottom: 20px; text-align: center;">Login Sistem Parkir</h2>
        
        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="{{ old('username') }}" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" style="width: 100%;">Login</button>
        </form>
    </div>
</div>
@endsection
