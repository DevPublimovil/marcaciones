@extends('layouts.public')

@section('content')
    <form action="{{ route('password.email') }}" class="mx-auto">
        @csrf
        <div class="input-div one">
            <div class="i">
                <i class="fa fa-envelope" aria-hidden="true"></i>
            </div>
            <div>
                <h5>{{ __('Correo') }}</h5>
                <input class="input @error('email') border-red-500 @enderror" type="text" name="email" id="email" required>
            </div>
        </div>
        <button class="w-1/2 mt-8 bg-orange-500 hover:bg-orange-400 h-10 rounded-lg m-2 outline-none border-none text-white uppercase" type="submit">{{ __('Enviar link') }}</button>
    </form>
@endsection
