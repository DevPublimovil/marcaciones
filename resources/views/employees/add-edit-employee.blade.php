@extends('layouts.app')

@section('content')
    <div class="w-11/12 mx-auto py-4">
        <form action="@if(isset($employee)) {{ route('employees.update', $employee->id) }} @else {{ route('employees.store') }} @endif" method="POST">
            @csrf
            <div class="body-form bg-gray-300 rounded shadow-lg">
                <div class="flex">
                    <div class="flex">
                        <h3 class="text-xl text-blue-900 py-2 mx-2 font-bold">@if(isset($employee)) Editar empleado @else Nuevo empleado @endif</h3>
                    </div>
                </div>
                <div class="content-form p-4 bg-white">
                    <div class="flex">
                        <div class="flex-1 p-2">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                                    Correo
                                </label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                                focus:outline-none focus:shadow-outline" name="email" id="email" type="email" value="@isset($employee) {{$employee->user->email }} @endisset">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="nameemployee">
                                    Nombre
                                </label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                                focus:outline-none focus:shadow-outline" name="nameemployee" id="nameemployee" type="text" value="@isset($employee) {{$employee->name_employee }} @endisset">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="surnameemployee">
                                    Apellidos
                                </label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                                focus:outline-none focus:shadow-outline" name="surnameemployee" id="surnameemployee" type="text" value="@isset($employee) {{$employee->surname_employee }} @endisset">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="codemployee">
                                    Código
                                </label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                                focus:outline-none focus:shadow-outline" name="codemployee" id="codemployee" type="text" value="@isset($employee) {{$employee->cod_marking }} @endisset">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="positionemployee">
                                    Cargo
                                </label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                                focus:outline-none focus:shadow-outline" name="positionemployee" id="positionemployee" type="text" value="@isset($employee) {{$employee->position }} @endisset">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="salaryemployee">
                                    Salario
                                </label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                                focus:outline-none focus:shadow-outline" name="salaryemployee" id="salaryemployee" type="number" step="any" value="@isset($employee) {{$employee->salary }} @endisset">
                            </div>
                        </div>
                        <div class="flex-1 p-2 border-l border-gray-200">
                            <div class="mb-4">
                                <label class="block tracking-wide text-gray-700 font-bold mb-2" for="terminal">
                                    Reloj marcador
                                </label>
                                <div class="relative">
                                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="terminal" id="terminal">
                                        <option @isset($employee) @if($employee->type_employee == 1) selected @endif @endisset>Administrativo</option>
                                        <option @isset($employee) @if($employee->type_employee == 2) selected @endif @endisset>Operativo</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block tracking-wide text-gray-700 font-bold mb-2" for="companie">
                                    Compañia
                                </label>
                                <div class="relative">
                                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="company" id="companie">
                                        @foreach ($companies as $item)
                                            <option value="{{ $item->id }}" @isset($employee) @if($employee->company_id == $item->id) selected @endif @endisset>{{ $item->display_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block tracking-wide text-gray-700 font-bold mb-2" for="departament">
                                    Departamento
                                </label>
                                <div class="relative">
                                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="departament" id="departament">
                                        @foreach ($departaments as $item)
                                            <option value="{{ $item->id }}" @isset($employee) @if($employee->departament_id == $item->id) selected @endif @endisset>{{ $item->display_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block tracking-wide text-gray-700 font-bold mb-2" for="boss">
                                    Jefe inmediato
                                </label>
                                <div class="relative">
                                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="boss" id="boss">
                                        @foreach ($managers as $item)
                                            <option value="{{ $item->id }}" @isset($employee) @if($employee->jefe_id == $item->id) selected @endif @endisset>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block tracking-wide text-gray-700 font-bold mb-2" for="typeemployee">
                                    Tipo de empleado
                                </label>
                                <div class="relative">
                                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="typeemployee" id="typeemployee">
                                        <option value="1" @isset($employee) @if($employee->type_employee == 1) selected @endif @endisset>Administrativo</option>
                                        <option value="2" @isset($employee) @if($employee->type_employee == 2) selected @endif @endisset>Operativo</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                    </div>
                                </div>
                            </div>
                            @isset($employee)
                            <div class="mb-4">
                                <label class="block tracking-wide text-gray-700 font-bold mb-2" for="status">
                                    Estado
                                </label>
                                <div class="relative">
                                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="status" id="status">
                                        <option @if($employee->status == 1) selected @endif>Activo</option>
                                        <option @if($employee->status == 0) selected @endif>No activo</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                    </div>
                                </div>
                            </div>
                            @endisset
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="reset" class="btn text-blue-900 hover:bg-blue-100 border  border-blue-800 rounded">Cancelar</button>
                        <button type="submit" class="btn bg-blue-600 text-white rounded hover:bg-blue-500">Guardar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection