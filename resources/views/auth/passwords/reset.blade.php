@extends('layouts.public')

@section('content')
<form action="{{ route('password.update') }}" method="POST" class="p-0 m-0 mx-auto text-white">
    @csrf
    <h3 class="text-xl font-bold mb-6">{{ __('Reestablecer contraseña') }}</h2>
    <input type="hidden" name="token" value="{{ $token }}">
    <div class="flex flex-col">
        <label for="email" class="text-left">{{ __('Correo') }}</label>
        <div class="text-gray-600">
            <input id="email" type="email" class="form-input block w-full @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

            @error('email')
                <span class="invalid-feedback text-black text-sm" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="flex flex-col">
        <label for="password" class="text-left">{{ __('Contraseña') }}</label>
        <div class="text-gray-600">
            <input id="password" type="password" class="form-input block w-full @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

            @error('password')
                <span class="invalid-feedback text-black text-sm" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="flex flex-col">
        <label for="password-confirm" class="text-left">{{ __('Confirmar contraseña') }}</label>
        <div class="text-gray-600">
            <input id="password-confirm" type="password" class="form-input block w-full" name="password_confirmation" required autocomplete="new-password">
        </div>
    </div>
    <div class="block items-center justify-center text-center">
        <button class="px-2 mt-8 bg-blue-800 hover:bg-blue-900 h-10 rounded-lg m-2 outline-none border border-blue-600 text-white uppercase" type="submit">{{ __('Reestablecer contraseña') }}</button>
    </div>
</form>
@endsection
