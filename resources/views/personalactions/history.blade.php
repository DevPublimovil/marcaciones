@extends('layouts.app')

@section('header-content')
    <div class="flex p-2 pt-8 mb-y align-middle content-center items-center">
        <div class="flex-1 p-0 m-0">
            <h3 class="text-2xl text-gray-700 font-bold">
                <span class="mr-3"><i class="fa fa-file-text" aria-hidden="true"></i></span>
                Acciones de personal
            </h3>
        </div>
        <div class="flex-1 p-0 m-0">
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
    </div>
    
@endsection

