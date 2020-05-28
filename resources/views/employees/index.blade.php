@extends('layouts.app')

@section('content')
	<data-viewer source="/apiemployees" title="Lista de empleados" :columns="{{ json_encode($columns) }}"></data-viewer>
@endsection