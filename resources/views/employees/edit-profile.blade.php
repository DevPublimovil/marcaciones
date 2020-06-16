@extends('layouts.app')

@section('content')
<div class="container-xl w-full md:w-8/12 mx-auto md:px-6 px-0 py-6">
    @if ($errors->any())
        <div  class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4 mb-4" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>@lang($error)</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('message'))
        <div  class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            {{ session('message') }}
        </div>
    @endif
    <h3 class="text-2xl text-blue-900 mb-3 font-bold"><i class="fa fa-address-book" aria-hidden="true"></i> Mi cuenta</h3>
    <div class="shadow-lg rounded-lg border border-gray-300 bg-white">
        <div class="flex justify-between p-2">
            <div class="flex-1 pl-4">
            <form action="{{ route('myaccount.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mt-4 mb-3">
                    <label for="name">Nombre:</label>
                    <input class="shadow appearance-none text-xs border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" name="name" id="name" value="{{ old('name',$user->name ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label for="email">Correo:</label>
                    <input class="shadow appearance-none text-xs border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="email" name="email" id="email" value="{{ old('email',$user->email ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label for="password">Contraseña:</label>
                    <input class="shadow appearance-none text-xs border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="password" minlength="8" name="password" id="password">
                <span class="text-gray-500 text-xs italic">* Dejarlo vacío para mantener la misma contraseña</span>
                </div>
                {{-- @if($user->employee)
                <div class="mb-3">
                    <label for="email">Salario:</label>
                    <input class="shadow appearance-none text-xs border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="number" name="salary" id="salary" placeholder="0.00" step="any" value="{{ $user->employee->salary }}" required>
                </div>
                @endif --}}
                <div class="mb-3">
                <label for="firma"><a href="{{ route('employees.editfirm', $user->id) }}" class="change text-indigo-500 cursor-pointer underline hover:text-indigo-900">Editar firma</a></label>
                @if ($user->firm)
                <div class="flex w-full h-40">
                    <img class="rounded-lg w-1/2 h-auto" id="firm-image" src="{{ asset('storage/' . $user->firm) }}" alt="">
                </div>
                @endif
                </div>
                <div class="flex justify-end mb-3 text-xs">
                    <button type="submit" class="btn border border-blue-700 bg-blue-600 text-white mx-1 hover:bg-blue-500">
                        Guardar cambios
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
@endsection