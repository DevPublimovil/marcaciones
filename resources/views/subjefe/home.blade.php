@extends('layouts.app')

@section('content')
@if (session('message'))
    <notification-component :mensaje="'{{ session('message') }}'"></notification-component>
@endif

<div class="py-2">
    <bad-progress :employee="{{ $employee }}" v-if="showBar"></bad-progress>
    <div class="flex flex-wrap justify-between mx-2 py-2">
        <div class="lg:w-1/4 md:w-1/4 w-0">
            <x-profile :employee="$employee"/>
        </div>
        <div class="pl-4 lg:w-3/4 md:w-3/4 sm:w-full w-full">
            <my-markings :employee="{{ $employee }}"/>
        </div>
    </div>
</div>
@endsection

