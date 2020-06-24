@extends('layouts.app')

@section('header-content')
    <div class="flex md:w-10/12 mx-auto flex-wrap py-4 border-b">
        <div class="flex-1">
            <h3 class="font-bold text-2xl text-center md:text-left"> <span class="mr-3"><i class="fa fa-file-text" aria-hidden="true"></i></span> Acciones de personal</h3>
        </div>
    </div>
    {{-- <div class="flex w-full md:w-10/12 mx-auto flex-wrap py-4 px-6 align-middle content-center items-center bg-gray-100 shadow rounded-md">
        <div class="flex-auto p-0 m-0">
            <h3 class="md:text-2xl text-sm text-gray-700 font-bold">
                <span class="mr-3"><i class="fa fa-file-text" aria-hidden="true"></i></span>
                Acciones de personal
            </h3>
        </div>
        <div class="flex-auto p-0 m-0">
            <div class="inline-flex">
            <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-l">
                Prev
            </button>
            <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-r">
                Next
            </button>
            </div>
            @if (session('message'))
                <x-alert :type="session('type')" :message="session('message')"></x-alert>
            @endif
        </div>
    </div> --}}
@endsection

@section('content')
        
        @if ($user->role->name == 'empleado')
            <status-action-component :user="{{ $user }}"></status-action-component>
        @else
            <history-component></history-component>
        @endif
@endsection

