@extends('layouts.public')

@section('content')
<form method="POST" action="{{ route('login') }}" class="mx-auto p-2">
    @csrf
    <h2 class="uppercase">ICLOCK</h2>
    <div class="input-div one">
        <div class="i">
            <i class="fa fa-envelope" aria-hidden="true"></i>
        </div>
        <div>
            <h5>{{ __('Correo') }}</h5>
            <input class="input" type="text" name="email" id="email" value="{{ old('email') }}" required>
        </div>
    </div>
    <div class="mb-8"></div>
    <div class="input-div two">
        <div class="i">
            <i class="fa fa-lock" aria-hidden="true"></i>
        </div>
        <div>
            <h5>{{ __('Password') }}</h5>
            <input class="input" type="password" name="password" id="password" minlength="8"  required>
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
        <button class="w-1/2 mt-8 bg-orange-500 hover:bg-orange-400 h-10 rounded-lg m-2 outline-none border-none text-white uppercase" type="submit">{{ __('Iniciar sesión') }}</button>
    </div>
</form>
@endsection
