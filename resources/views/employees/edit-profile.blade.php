@extends('layouts.app')

@section('content')
<div class="container-xl w-11/12 mx-auto px-6 py-6">
    @if(session('message'))
        <div class="alert alert-warning" role="alert">
            {{ session('message') }}
        </div>
    @endif
    <div class="card-profile bg-gray-300 shadow-lg rounded border">
        <h3 class="text-right my-4 mr-3 text-xl text-blue-900">Editar perfil</h3>
        <div class="flex justify-between p-2 bg-white">
            <div class="flex-1 pl-4">
            <form action="{{ route('employees.update', $user->id) }}" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="mt-4 mb-3">
                    <label for="name">Nombre:</label>
                    <input class="shadow appearance-none text-xs border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" name="name" id="name" value="{{ $user->name }}" required>
                </div>
                <div class="mb-3">
                    <label for="email">Correo:</label>
                    <input class="shadow appearance-none text-xs border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="email" name="email" id="email" value="{{ $user->email }}" required>
                </div>
                <div class="mb-3">
                    <label for="password">Contraseña:</label>
                    <input class="shadow appearance-none text-xs border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="password" minlength="8" name="password" id="password">
                <span class="text-gray-500 text-xs italic">* Dejarlo vacío para mantener la misma contraseña</span>
                </div>
                <div class="mb-3">
                    <label for="email">Salario:</label>
                    <input class="shadow appearance-none text-xs border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="number" name="salary" id="salary" placeholder="0.00" step="any" value="{{ $user->employee->salary }}" required>
                </div>
                <div class="mb-3">
                <label for="firma"><a href="{{ route('employees.editfirm', $user->id) }}" class="change text-indigo-500 cursor-pointer underline hover:text-indigo-900">Editar firma</a></label>
                <div class="flex w-full h-40">
                    <img class="rounded-lg w-1/2 h-auto" id="firm-image" src="{{ asset('storage/' . $user->firm) }}" alt="">
                </div>
                </div>
                <div class="flex justify-end mb-3 text-xs">
                <a href="{{ route('home') }}" class="btn border border-gray-500 mx-1 hover:bg-blue-100 text-blue-900">
                    Cancelar
                </a>
                <button type="submit" class="btn border border-blue-700 bg-blue-600 text-white mx-1 hover:bg-blue-500">
                    Aceptar
                </button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
      function changeProfile() {
        $("#form-send-photo").submit();
        $("#avatar").val("");
      }
    </script>
@endsection