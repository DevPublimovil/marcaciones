@extends('layouts.app')

@section('header-content')
    <div class="flex mx-auto flex-wrap py-4 border-b">
        <div class="flex-1">
            <h3 class="font-bold text-2xl text-center md:text-left"> <span class="mr-3"><i class="fa fa-file-text" aria-hidden="true"></i></span>Reporte de Asistencias</h3>
        </div>
        <div class="flex-1">
            @if (session('message'))
                <x-alert :type="session('type')" :message="session('message')"></x-alert>
            @endif
        </div>
    </div>
@endsection

@section('content')

    @if (session('message'))
        <x-alert :type="session('type')" :message="session('message')"></x-alert>
    @endif
    
    <div class="flex  mt-4 justify-between">
        <div class="flex-1 content-center">
            <div class="flex">
                <div class="mx-1 self-center">
                    <span>Desde: </span>
                </div>
                <div>
                    <datepicker :format="customFormatter" :language="es" v-model="startDate" @input="changeDate()" input-class="inline-block form-input"  placeholder="Selecciona la fecha"></datepicker>
                </div>
                <div class="mx-1 self-center">
                    <span>Hasta: </span>
                </div>
                <div>
                    <datepicker :format="customFormatter" :language="es" v-model="endDate" @input="changeDate()" input-class="inline-block form-input"  placeholder="Selecciona la fecha"></datepicker>
                </div>
            </div>
        </div>
        <div class="flex-1">
                <div class="flex justify-end">
                    <form-report :start="startDate" :end="endDate" :token="'{{ csrf_token() }}'"></form-report>
                    <input type="search" class="form-input self-center ml-1" name="" id="" v-model="employee" v-on:keyup.enter="changeDate()" placeholder="Buscar">
                    <button class="btn bg-blue-500 self-center ml-1" v-on:click="changeDate()"><i class="fa fa-search" aria-hidden="true"></i></button>
                </div>
        </div>
    </div>

    <div v-for="(item, $index) in list" :key="$index">
        <div class="details-markings py-4">
            <div class="dt-header-table bg-white rounded shadow-md mt-3 p-2">
                <details-markings-component :employee="item" :startdate="startDate" :enddate="endDate"></details-markings-component>
            </div>
        </div>
    </div>
    <infinite-loading :identifier="infiniteId" @infinite="infiniteHandler"></infinite-loading>
    <my-modal-component v-if="showModal" @close="showModal = false">
        <template v-slot:header-modal>
            <p class="text-2xl font-bold">Buscar un empleado</p>
        </template>
        <template v-slot:body-modal>
            <form action="{{ route('reports.index') }}" method="GET">
                @csrf
                <div class="flex mt-1 mb-4">
                    <div class="w-full">
                        <input type="search" class="form-input block w-full" name="employee" placeholder="Buscar por nombre">
                    </div>
                </div>
                <div class="flex justify-end content-end text-right">
                    <button class="focus:outline-none btn border border-gray-600 mx-1 hover:bg-gray-200" @click="showModal = false">Cancelar</button>
                    <button type="submit" class="focus:outline-none btn border border-blue-700 bg-blue-600 text-white hover:bg-blue-500 mx-1">
                        Buscar
                    </button>
                </div>
            </form>
        </template>
     </my-modal-component>
@endsection