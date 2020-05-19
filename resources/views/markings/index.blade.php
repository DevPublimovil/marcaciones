@extends('layouts.app')

@section('content')
<data-viewer source="/marcaciones-all" title="Marcaciones Semanales" :columns="{{  json_encode($columns) }}"></data-viewer>
@endsection