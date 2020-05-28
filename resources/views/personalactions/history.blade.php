@extends('layouts.app')

@section('content')
    <div class="container-lg mx-auto py-2 w-11/12">
        @if ($user->role->name == 'empleado')
            <status-action-component :user="{{ $user }}"></status-action-component>
        @else
            <history-component></history-component>
        @endif
    </div>
    
@endsection

