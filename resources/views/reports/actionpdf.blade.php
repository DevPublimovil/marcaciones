<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        @page{
            margin: 0%;
            padding: 0%;
            text-transform: uppercase;
        }

        header{
            padding-top: 0.5cm;
            padding-left: 0.5cm;
            padding-right: 0.5cm;
        }

        main{
            padding-top: 0.5cm;
            padding-left: 0.5cm;
            padding-right: 0.5cm;
        }

        footer{
        }

        .negrita{
            font-weight: bold;
        }
        table{
            border-collapse: collapse;
            color: #212529;
        }
        .table{
            width: 100%;
            padding: 0%;
            text-align: center;
            margin-bottom: 0.50rem
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
        }
        
        .column{
            border: 1px solid black;
            padding: 0%;
        }

        .text-left{
            text-align: left;
        }
        .text-right{
            text-align: right;
        }
        .text-center{
            text-align: center;
        }

        .acciones td{
            width: 33.3333333333%;
            padding: 3px;
        }
        .first-item{
            padding-left: 4px
        }
        .text-sm{
            font-size: 12px
        }
        .firma{
            position: relative;
        }
        .image_firma{
            width: 100%;
            height: 80px;
        },
        .uppercase{
            text-transform: uppercase
        }
    </style>
</head>
<body>
    <header>
        <table class="table negrita uppercase">
            <tr>
                <td class="column">{{ $action->employee->company->display_name }}, S.A. DE C.V.</td>
                <td class="column"><u>ACCION DE PERSONAL</u></td>
                <td class="column">RECURSOS HUMANOS</td>
            </tr>
        </table>
    </header>
    <main>
        <table class="table text-left">
            <thead>
                <tr>
                    <th colspan="2" class="text-center column">
                        I. DATOS GENERALES
                    </th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <tr>
                    <td class="column"><strong>Fecha:</strong> {{\Carbon\Carbon::parse($action->created_at)->isoFormat('LLLL')}}</td>
                    <td class="column"><strong>Codigo:</strong> {{ $action->employee->cod_marking }}</td>
                </tr>
                <tr>
                    <td class="column" colspan="2"><strong>Nombre del empleado:</strong> {{$action->employee->name_employee . ' ' . $action->employee->surname_employee}}</td>
                    {{-- <td class="column"><strong>Sueldo:</strong> ${{$action->employee->salary}}</td> --}}
                </tr>
                <tr>
                    <td class="column" colspan="2"><strong>Unidad a la que pertence:</strong> {{$action->employee->departament->display_name}}</td>
                </tr>
                <tr>
                    <td class="column" colspan="2"><strong>Nombre del puesto:</strong> {{$action->employee->position}}</td>
                </tr>
            </tbody>
        </table>

        <table class="table text-left acciones">
            <thead>
                <tr>
                    <th colspan="3" class="column">
                        II. ACCIONES DE PERSONAL
                    </th>
                </tr>
            </thead>
            <tbody class="column text-sm">
                @foreach ($personal_actions as $item)
                    <tr >
                        <td colspan="3"> - {{$item->typeactions->name_type_action}}</td>
                    </tr>
                @endforeach
                @if ($action->other_action)
                    <tr>
                        <td colspan="3"> - {{ $action->other_action }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <table class="table text-sm" style="table-layout:fixed">
            <tr>
                <td class="text-left column" style="padding:5px">
                    <p><strong>Descripcion:</strong> {{$action->description}}</p>
                </td>
                <td class="text-center column firma">
                    @if ($action->employee->user->firm)
                        <img src="{{public_path('storage/'.$action->employee->user->firm)}}" class="image_firma" alt="">
                    @endif
                    Empleado
                </td>
            </tr>
        </table>

        <table class="table text-center text-sm" style="table-layout:fixed">
            <tr>
                <td>
                    <div style="position: relative;">
                        <div style="position: absolute; left: 40px; top: 40px;">
                            @if ($action->check_gte == 1 && $action->employee->jefe->firm)
                                <img src="{{public_path('storage/'.$action->employee->jefe->firm)}}" class="image_firma" alt="">
                            @endif
                        </div>
                        <div style="position: absolute; left: 0px; top: 80px;"><p style="padding:0%;border-bottom:1px solid black;margin: 25px 0px 0px 0px;"></p></div>
                      </div>
                    {{-- @if ($action->check_gte == 1 && $action->employee->jefe->firm)
                        <img src="{{public_path('storage/'.$action->employee->jefe->firm)}}" class="image_firma" alt="">
                    @endif
                    <p style="padding:0%;border-bottom:1px solid black;margin: 25px 0px 0px 0px;">
                    {{ $action->employee->jefe->name }}</p>
                    <p style="padding:0%;margin:0%">Jefe inmediato</p> --}}
                </td>
                <td class="text-center firma">
                    @if ($action->check_gte == 1 && $action->employee->jefe->firm)
                        <img src="{{public_path('storage/'.$action->employee->jefe->firm)}}" class="image_firma" alt="">
                    @endif
                </td>
            </tr>
            {{-- <tr>
                <td>
                    <p style="padding:0%;border-bottom:1px solid black;margin: 25px 0px 0px 0px;">Rene cisneros</p>
                    <p style="padding:0%;margin:0%">Gerente de IT</p>
                </td>
                <td class="text-center firma">
                    <img src="{{public_path('storage/'.$action->employee->firma)}}" class="image_firma" alt="">
                </td>
            </tr> --}}
            {{-- <tr>
                <td>
                    <p style="padding:0%;border-bottom:1px solid black;margin: 25px 0px 0px 0px;">Rene cisneros</p>
                    <p style="padding:0%;margin:0%">Gerente de IT</p>
                </td>
                <td class="text-center firma">
                    <img src="{{public_path('storage/'.$action->employee->firma)}}" class="image_firma" alt="">
                </td>
            </tr> --}}
            <tr>
                <td>
                    <p style="padding:0%;border-bottom:1px solid black;margin: 25px 0px 0px 0px;">{{$rh->name}}</p>
                    <p style="padding:0%;margin:0%">Recursos Humano</p>
                </td>
                <td class="text-center firma">
                    @if ($action->check_rh == 1 && $rh->firm)
                    <img src="{{public_path('storage/'.$rh->firm)}}" class="image_firma" alt="">
                    @endif
                </td>
            </tr>
        </table>
    </main>
</body>
</html>