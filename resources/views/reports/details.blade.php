@extends('layouts.app')

@section('content')
    @foreach ($employees as $employee)
        <div class="details-markings py-4">
            <section class="w-11/12 mx-auto shadow-lg pt-2 bg-gray-300 rounded border-2">
                <div class="dt-header mx-3">
                    <div class="flex justify-between p-0">
                        <div class="self-center flex-auto">
                            <h3 class="text-xl text-blue-800">Reporte de marcaciones</h3>
                            <small>mostrando empleado {{$employees->currentPage() }} de un total de {{$employees->total() }}</small>
                        </div>
                        <div class="flex-auto justify-end">
                            <div class="flex justify-end">
                                <form-report :start="start" :end="end" :token="'{{ csrf_token() }}'" :emp="{{$employee}}"></form-report>
                                <div class="pl-2">
                                    {{ $employees->links('pagination.pagination') }}
                                </div>
                                @if ($employees->total() == 1)
                                <div class="pl-1">
                                    <button type="button" class="bg-blue-600 h-full hover:bg-blue-500 font-semibold text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" onclick="window.location.href = '/reports'">
                                        Mostrar todos
                                    </button>
                                </div>
                                @endif
                                <div class="pl-1">
                                    <button type="button" class="bg-blue-600 h-full hover:bg-blue-500 font-semibold text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" @click="showModal = true">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dt-header-table bg-white rounded shadow-md mt-3 p-2">
                    <details-markings-component :employee="{{ $employee->load('departament','company') }}" 
                                :startdate="'{{ \Carbon\Carbon::now()->startOfWeek() }}'"
                                :enddate="'{{ \Carbon\Carbon::now()->endOfWeek() }}'">

                    </details-markings-component>
                </div>
            </section>
        </div>
    @endforeach
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