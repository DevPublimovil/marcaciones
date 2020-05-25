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
                                <div>
                                    <form action="{{route('reports.create', $employee->id)}}" method="GET" target="_blank" class="p-0 m-0 h-full">
                                        @csrf
                                        <input type="hidden" name="start_date" v-model="start">
                                        <input type="hidden" name="end_date" v-model="end">
                                        <button class="bg-blue-600 h-full hover:bg-blue-500 font-semibold text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                                            Generar reporte  <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="pl-2">
                                    {{ $employees->links('pagination.pagination') }}
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
@endsection