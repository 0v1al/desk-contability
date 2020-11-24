@extends('layouts.app')

@section('content')
    <div class="home-container">
        <img src="{{ url('/images/bg.jpg') }}" alt="" class="bg-img">
        <div class="overlay"></div>
        <div class="home-content container mt-5">
            <h1 class="home-title w-75">Birou contabil autorizat</h1>
            <p class="home-desc mt-2 w-75">Birou contabil autorizat, realizat cu scopul facilitării comunicării între clienți și contabil, precum și a altor servicii utile</p>
            <a href="{{ route('login') }}" class="btn btn-action text-uppercase shadow-sm mt-4 btn-home">Conectare</a>
        </div>
    </div>
@endsection
