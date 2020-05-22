@extends('layouts.app')

@section('content')
    @foreach ($employees as $employee)
        <div class="details-markings py-4">
            <section class="w-11/12 mx-auto">
                <div class="dt-header">
                    <div class="flex justify-between p-0">
                        <div class="self-center flex-auto">
                            <h3>Reporte de marcaciones</h3>
                        </div>
                        <div class="flex-auto justify-end">
                            <div class="flex justify-end">
                                <div class="pl-2">
                                    <form action="{{route('reports.create', $employee->id)}}" method="GET" target="_blank">
                                        @csrf
                                        <input type="hidden" name="start_date" v-model="start">
                                        <input type="hidden" name="end_date" v-model="end">
                                        <button class="bg-transparent h-full hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                                            Generar reporte  <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dt-header-table bg-white rounded shadow-md mt-2">
                    <details-markings-component :employee="{{ $employee->load('departament','company') }}" 
                                :startdate="'{{ \Carbon\Carbon::now()->startOfWeek() }}'"
                                :enddate="'{{ \Carbon\Carbon::now()->endOfWeek() }}'">

                    </details-markings-component>
                </div>
            </section>
        </div>
    @endforeach
    <div class="flex">
        <div>
            {{ $employees->links('pagination.pagination') }}
        </div>
    </div>
@endsection