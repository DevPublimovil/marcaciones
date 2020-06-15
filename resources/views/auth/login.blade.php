@extends('layouts.public')

@section('content')
<form method="POST" action="{{ route('login') }}" class="mx-auto p-2">
    @csrf
    <h2 class="uppercase">{{ config('app.name', 'Laravel') }}</h2>
    <div class="input-div one">
        <div class="i">
            <i class="fa fa-envelope" aria-hidden="true"></i>
        </div>
        <div>
            <input class="input" type="text" name="email" id="email" value="{{ old('email') }}" placeholder="Correo" required>
        </div>
    </div>
    <div class="mb-8"></div>
    <div class="input-div two">
        <div class="i">
            <i class="fa fa-lock" aria-hidden="true"></i>
        </div>
        <div>
            <input class="input" type="password" name="password" id="password" minlength="8"  placeholder="Contraseña"  required>
        </div>
    </div>
    @error('email')
        <span class="text-orange-400 text-xs italic" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
    @if (Route::has('password.request'))
        <a class="text-white" href="{{ route('password.request') }}">
            {{ __('¿Olvidastes tu contraseña?')}}
        </a>
    @endif
    <div class="block items-center justify-center text-center">
        <button class="w-1/2 mt-8 bg-blue-800 hover:bg-blue-900 h-10 rounded-lg m-2 outline-none border border-blue-600 text-white uppercase" type="submit">{{ __('Iniciar sesión') }}</button>
    </div>
</form>
@endsection
