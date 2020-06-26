@extends('layouts.app')

@section('header-content')
    <div class="flex mx-auto flex-wrap py-4 border-b">
        <div class="flex-1">
            <h3 class="font-bold text-base md:text-2xl text-center md:text-left"> <span class="mr-3"><i class="fa fa-file-text" aria-hidden="true"></i></span>Reporte de Asistencias</h3>
        </div>
        <div class="flex-1 hidden md:block">
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
    
    <div class="flex flex-wrap  mt-4 justify-between items-end">
        <div class="flex-auto md:flex-1 content-end">
            <div class="flex">
                <div>
                    <span>Desde</span>
                    <datepicker :format="customFormatter" :language="es" v-model="startDate" @input="changeDate()" input-class="inline-block w-full md:w-32 form-input"  placeholder="Selecciona la fecha"></datepicker>
                </div>
                <div>
                    <span class="ml-3">Hasta</span>
                    <datepicker :format="customFormatter" :language="es" v-model="endDate" @input="changeDate()" input-class="inline-block w-full md:w-32 form-input ml-2"  placeholder="Selecciona la fecha"></datepicker>
                </div>
            </div>
        </div>
        <div class="flex-auto md:flex-1 mt-2 md:mt-0 content-end">
                <div class="flex justify-start md:justify-end items-end">
                    <form-report :start="startDate" :end="endDate" :token="'{{ csrf_token() }}'"></form-report>
                    <input type="search" class="form-input self-center w-full md:w-40 ml-1" name="" id="" v-model="employee" v-on:keyup.enter="changeDate()" placeholder="Buscar">
                    <button class="btn bg-blue-500 text-white hover:bg-blue-400 self-center ml-1" v-on:click="changeDate()"><i class="fa fa-search" aria-hidden="true"></i></button>
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
    <infinite-loading :identifier="infiniteId" @infinite="infiniteHandler">
        <div slot="no-more">No hay más empleados</div>
    </infinite-loading>
@endsection