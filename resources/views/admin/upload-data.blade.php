@extends('voyager::master')

@section('page_title', 'Carga de datos')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_header')
    <h1 class="page-title">
        <i class="icon voyager-upload"></i>
        Carga de datos
    </h1>
@stop

@section('content')
<div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h5>Agregar empleados</h5>
                                <form action="{{ route('admin.upload') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="model" value="employee">
                                    <div class="input-group" style="margin-bottom:25px;">
                                        <input type="file" name="file" class="form-control" placeholder="Username" aria-describedby="basic-addon1" required accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                    </div>
                                    <p class="text-center"><button type="submit" class="btn btn-primary">Cargar datos</button></p>
                                </form>
                            </div>
                            <div class="col-md-4" style="border-left: 1px solid gray">
                                <h5>Agregar Gerentes</h5>
                                <form action="{{ route('admin.upload') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="model" value="manager">
                                    <div class="input-group" style="margin-bottom:25px;">
                                        <input type="file" name="file" class="form-control" placeholder="Username" aria-describedby="basic-addon1" required accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                    </div>
                                    <p class="text-center"><button type="submit" class="btn btn-primary">Cargar datos</button></p>
                                </form>
                            </div>
                            {{-- <div class="col-md-4" style="border-left: 1px solid gray">
                                <h5>Agregar Horarios</h5>
                                <form action="{{ route('admin.upload') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="model" value="timestables">
                                    <div class="input-group" style="margin-bottom:25px;">
                                        <input type="file" name="file" class="form-control" placeholder="Username" aria-describedby="basic-addon1">
                                    </div>
                                    <div class="input-group" style="margin-bottom:25px;">
                                        <label>Recursos humanos</label>
                                        <select class="form-control" style="width: 100%" name="rrhh">
                                            @foreach ($users as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <p class="text-center"><button type="submit" class="btn btn-primary">Cargar datos</button></p>
                                </form>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection