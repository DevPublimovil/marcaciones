@extends('layouts.app')

@section('content')
<div class="flex w-11/12 mx-auto">
    <div class="flex-1 text-gray-700 text-left  px-2 py-2 ">
        <p class="left-0 inline-block">Listado de empleados</p>
    </div>
    <div class="flex-1 text-gray-700 text-right  px-4 py-2"></div>
</div>
<section class="w-11/12 mx-auto rounded overflow-hidden shadow-lg bg-white text-xs">
    <div class="px-6 py-4">
        <data-viewer source="/marcaciones-all" title="Employees Data"></data-viewer>
    </div>
</section>
@endsection