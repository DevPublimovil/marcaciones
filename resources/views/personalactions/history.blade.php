@extends('layouts.app')

@section('header-content')
    <div class="flex w-full md:w-10/12 mx-auto flex-wrap py-4 px-6 align-middle content-center items-center bg-gray-100 shadow rounded-md">
        <div class="flex-auto p-0 m-0">
            <h3 class="md:text-2xl text-sm text-gray-700 font-bold">
                <span class="mr-3"><i class="fa fa-file-text" aria-hidden="true"></i></span>
                Acciones de personal
            </h3>
        </div>
        <div class="flex-auto p-0 m-0">
            @if (session('message'))
                <x-alert :type="session('type')" :message="session('message')"></x-alert>
            @endif
        </div>
    </div>
@endsection

@section('content')
        @if ($user->role->name == 'empleado')
            <status-action-component :user="{{ $user }}"></status-action-component>
        @else
            <history-component></history-component>
        @endif
@endsection

