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