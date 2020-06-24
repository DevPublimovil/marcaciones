@extends('layouts.app')

@section('header-content')
    <div class="flex md:w-10/12 mx-auto flex-wrap py-4 border-b">
        <div class="flex-1">
            <h3 class="font-bold text-2xl text-center md:text-left"> <span class="mr-3"><i class="fa fa-file-text" aria-hidden="true"></i></span> Acciones de personal</h3>
        </div>
        <div class="flex-1">
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
            <history-component :user="{{ $user }}"></history-component>
        @endif
@endsection

