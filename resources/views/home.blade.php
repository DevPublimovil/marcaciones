@extends('layouts.app')

@section('content')
@if (session('message'))
    <notification-component :mensaje="'{{ session('message') }}'"></notification-component>
@endif

<div class="py-2">
    @if (Auth::user()->role->name != 'gerente')
        <bad-progress :employee="{{ $employee->employee->id }}" v-if="showBar"></bad-progress>
    @endif
    <div class="flex flex-wrap justify-between mx-2 py-2">
        <div class="hidden md:block w-1/4">
            <x-profile></x-profile>
        </div>
        <div class="block md:hidden text-center w-full h-10 px-2">
            <a href="{{ route('employees.edit', Auth::id()) }}" class="btn block bg-blue-500 hover:bg-blue-600 text-white text-sm w-full">Editar pérfil</a>
        </div>
        <div class="md:w-3/4 w-full px-2">
            <my-markings :employee="{{ $employee }}"/>
        </div>
    </div>
</div>
@endsection


