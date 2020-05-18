@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/datatable.css') }}">
@endsection

@section('content')
	<div class="flex w-11/12 mx-auto">
		<div class="flex-1 text-gray-700 text-left  px-2 py-2 ">
			<p class="left-0 inline-block">Listado de empleados</p>
		</div>
		<div class="flex-1 text-gray-700 text-right  px-4 py-2"></div>
	</div>
	<section class="w-11/12 mx-auto rounded overflow-hidden shadow-lg bg-white text-xs">
		<div class="px-6 py-4">
			<table id="employees" class="text-center table-auto" style="width:100%">
				<thead>
				<tr>
					<th></th>
					<th>Nombre</th>
					<th>Apellido</th>
					<th>Correo</th>
					<th>Compañia</th>
					<th>Departamento</th>
					<th>Opcion</th>
				</tr>
				</thead>
			</table>
		</div>
	</section>
@endsection

@section('scripts')
<script>
	$(document).ready( function () {
		$('#employees').DataTable({
			"processing": true,
			"serverSide": true,
			"ajax": '{{route('employees.getall')}}',
			"columns": [
			{ "data": "avatar", "orderable": false, "searchable": false },
			{ "data": "name_employee" },
			{ "data": "surname_employee" },
			{ "data": "user.email" },
			{ "data": "company.display_name", "searchable": false },
			{ "data": "departament.display_name", "searchable": false },
			{ "data": "actions", "orderable": false, "searchable": false},
			]
		});
	} );
</script>
@endsection