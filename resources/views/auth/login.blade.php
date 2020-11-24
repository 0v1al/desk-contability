@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center">
    <form class="w-50 p-5 shadow-sm border login-form" method="POST" action="{{ route('login.login') }}">
        @csrf
        <h1 class="text-uppercase font-weight-bold">Conectare</h1>
        <p class="pb-2">Conectează-te aici pentru a putea utiliza servicile noastre!</p>
        <div class="form-group">
            <label for="exampleInputEmail1">Adresa Email:</label>
            <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Adresa email...">
            <small id="emailHelp" class="form-text text-muted">Adresa ta de email nu va fi niciodată facută publică altor persoane</small>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Parolă:</label>
            <input type="password" class="form-control" name="password" id="exampleInputPassword1" placeholder="Parolă...">
        </div>
        <button type="submit" class="btn btn-action text-uppercase mb-3">Conectare</button>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger">{{ $error }}</div>
            @endforeach
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger">{{ session()->get('error') }}</div>
        @endif
    </form>
</div>
@endsection
