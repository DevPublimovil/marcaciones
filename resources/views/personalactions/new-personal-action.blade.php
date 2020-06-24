@extends('layouts.app')

@section('header-content')
    <div class="flex w-full xl:w-10/12 mx-auto flex-wrap py-4 border-b">
        <div class="flex-1">
            <h3 class="font-bold text-2xl text-center md:text-left">
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
    @endisset  @isset($employee) :myemployee="{{$employee}}" @endisset></personal-action-component>
@endsection