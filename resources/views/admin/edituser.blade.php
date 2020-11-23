@extends('layouts.app')

@section('content')
    <div class="container d-flex align-items-center justify-content-center">
        <form class="w-50 p-5 shadow-sm border form-register-user login-form" method="POST" action="{{ route('admin.update_user') }}">
            @csrf
            <h3 class="text-uppercase font-weight-bold">Editare Client: {{ $user->name ?? '' }}</h3>
            <p class="pb-2">Modifică informațiile legate de un anumit client</p>
            <div class="form-group d-flex flex-column">
                <label for="exampleInputName">Nume complet:</label>
                <input type="text" name="name" class="form-control w-100" id="name" placeholder="Nume complet..." value="{{ $user->name ?? '' }}">
                <input type="hidden" value="{{ $user->name ?? '' }}" name="oldUserName">
                <input type="hidden" value="{{ $user->id ?? '' }}" name="userId">
            </div>
            <div class="form-group d-flex flex-column">
                <label for="exampleInputEmail1">Adresă Email:</label>
                <input type="email" class="form-control w-100" name="email" id="email" aria-describedby="emailHelp" placeholder="Adresa email..." value="{{ $user->email ?? '' }}">
                <input type="hidden" value="{{ $user->email ?? '' }}" name="oldUserEmail">
            </div>
            <button type="submit" class="btn btn-action text-uppercase mb-3">Modificare</button>
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
