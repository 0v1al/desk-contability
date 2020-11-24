@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center">
    <form class="w-50 p-5 shadow-sm border form-register-user" method="POST" action="{{ route('admin.register_user') }}">
        @csrf
        <h1 class="text-uppercase font-weight-bold">Înregistrare Clienți</h1>
        <p class="pb-2">Înregistrează un client aici pentru a putea avea acces la aplicație</p>
        <div class="form-group">
            <label for="exampleInputName">Nume complet:</label>
            <input type="text" name="name" class="form-control" id="name" placeholder="Nume complet...">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Adresă Email:</label>
            <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="Adresa email...">
            <small id="emailHelp" class="form-text text-muted">Adresa ta de email nu va fi niciodată facută publică altor persoane</small>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Parolă:</label>
            <input type="password" name="password" class="form-control" id="password" placeholder="Parolă...">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword2">Confirmare Parolă:</label>
            <input type="password" name="confirmPassword" class="form-control" id="confirmPassword" placeholder="Confirmare parolă...">
        </div>
        <button type="submit" class="btn btn-action text-uppercase mb-3">Înregistrare</button>
        <div class="info"></div>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger">{{ $error }}</div>
            @endforeach
        @endif
        @if (session()->has('message'))
            <div class="alert alert-success">{{session()->get('message')}}</div>
        @elseif (session()->has('error'))
            <div class="alert alert-danger">{{session()->get('error')}}</div>
        @endif
    </form>
</div>
@endsection
