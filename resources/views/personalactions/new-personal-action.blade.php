@extends('layouts.app')

@section('header-content')
    <div class="flex p-2 pt-8 mb-y">
        <div class="flex-1">
            <h3 class="text-base md:text-2xl text-gray-700 font-bold">
                <span class="mr-3"><i class="fa fa-file-text" aria-hidden="true"></i></span>
                @if (isset($action))
                    Editar acción de personal
                @else
                    Crear Acción de personal
                @endif
            </h3>
        </div>
    </div>
@endsection

@section('content')
    <personal-action-component :types="{{ json_encode($typeactions) }}" :user="{{ json_encode($user)}}" @isset ($action)
        :action="{{$action}}"
    @endisset></personal-action-component>
@endsection