@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="w-11/12 mx-auto shadow-lg uppercase pt-2 bg-gray-300 rounded">
            <div class="flex justify-between mx-6 mb-3">
                <a href="{{ route('home') }}" class="border border-blue-800 bg-transparent hover:bg-blue-700 hover:text-white text-blue-800 font-bold py-2 px-4 rounded-full">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                    Volver
                </a>
                <h5 class="text-xl text-right py-2 px-4 text-blue-900">Accion de personal</h5>
            </div>
            <div class="bg-white p-6">
                <h4 class="text-xl text-center text-gray-700">Faltas cometidas</h4>
                <div class="form-control-ic text-xs grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($typeactions as $key => $action)
                    <div>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="actions[]" class="form-checkbox" value="{{$action->id}}">
                        <span class="ml-2">{{$action->name_type_action}}</span>
                    </label>
                    </div>
                    @endforeach
                </div>
                <div class="form-control-ic">
                    <label class="block">
                        <span class="text-gray-700">Otro</span>
                        <textarea class="form-textarea mt-1 block w-full" rows="2" name="other_action"></textarea>
                    </label>
                </div>
                <h4 class="text-xl text-center text-gray-700">Desripción de la acción</h4>
                <div class="form-control-ic">
                    <textarea class="form-textarea w-full" rows="5" name="description"></textarea>
                </div>
                <div class="btn-group text-center">
                    <button class="btn text-blue-900 border border-blue-700 hover:bg-gray-200">
                        Cancelar
                    </button>
                    <button class="btn btn-blue">
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection