@extends('layouts.app')

@section('styles')
    <style>
        .lds-dual-ring {
        display: inline-block;
        width: 80px;
        height: 80px;
        }
        .lds-dual-ring:after {
        content: " ";
        display: block;
        width: 64px;
        height: 64px;
        margin: 8px;
        border-radius: 50%;
        border: 6px solid #fff;
        border-color: #fff transparent #fff transparent;
        animation: lds-dual-ring 1.2s linear infinite;
        }
        @keyframes lds-dual-ring {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
        }
    </style>
@endsection

@section('content')
{{-- <div class="lds-dual-ring"></div> --}}
<data-viewer source="/apiemployees" title="Marcaciones" :columns="{{  json_encode($columns) }}" :start="'{{ \Carbon\Carbon::now()->startOfWeek() }}'" :end="'{{ \Carbon\Carbon::now()->endOfWeek() }}'"></data-viewer>
@endsection