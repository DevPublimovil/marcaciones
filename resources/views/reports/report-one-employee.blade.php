<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$employee->name_employee . ' ' . $employee->surname_employee }}</title>
    <style>
        @page{
            margin: 0%;
            padding: 0%;
        }
        .table{
            width: 100%
        }
        .t-header{
            
        }

        .text-left{
            text-align: left;
        }

        .text-right{
            text-align: right
        }
        .text-center{
            text-align: center
        }
        .uppercase{
            text-transform: uppercase
        }
    </style>
</head>
<body>
    <header>
        <table class="table t-header">
            <thead>
                <th class="text-left">Lista de asistencia se mamanal</th>
                <th class="text-right">{{ Carbon\Carbon::now()->format('d/m/Y H:i a') }}</th>
            </thead>
        </table>
    </header>
    <main>
        <table class="table">
            <thead>
                <th class="text-left" style="width:30%">
                    {{ $employee->cod_marking}}
                </th>
                <th class="text-center uppercase" style="width:40%">
                    {{ $employee->name_employee . ' ' . $employee->surname_employee }}
                </th>
                <th class="text-right uppercase" style="width:30%">
                    {{ $employee->departament->display_name }} | {{ $employee->company->display_name }}
                </th>
            </thead>
        </table>

        <table class="table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Día</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Horas Trabajadas</th>
                    <th>Horas Extras</th>
                    <th>Minutos Tarde</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($markings['markings'] as $marking)
                    <tr>
                        <td>{{ $marking['date'] }}</td>
                        <td>{{ $marking['day'] }}</td>
                        <td>{{ $marking['in'] }}</td>
                        <td>{{ $marking['out'] }}</td>
                        <td>{{ $marking['hours_worked'] }}</td>
                        <td>{{ $marking['extra_hours'] }}</td>
                        <td>{{ $marking['late_arrivals'] }}</td>
                       {{--  <td>{{ $markings['total_hours_worked'] }}</td>
                        <td>{{ $markings['total_extra_hours'] }}</td>
                        <td>{{ $markings['total_late_arrivals'] }}</td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
    <footer>

    </footer>
</body>
</html>