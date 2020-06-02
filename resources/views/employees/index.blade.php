@extends('layouts.app')

@section('content')
	@if (session('message'))
		<notification-component :mensaje="'{{ session('message') }}'"></notification-component>
	@endif
	<data-viewer source="/apiemployees" :rol="{{ Auth::user()->role_id }}" title="Lista de empleados" :columns="{{ json_encode($columns) }}"></data-viewer>
@endsection