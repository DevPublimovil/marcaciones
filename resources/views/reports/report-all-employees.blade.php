<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>sgsfdgs</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Source+Code+Pro:wght@500&display=swap');
        @page{
            margin: 0%;
            padding: 0.5cm;
            font-family: 'Source Code Pro', monospace;
        }
        body{
            padding: 0.5cm
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
        .text-gray{
            color: #4a5568
        }
        table {
            border-collapse: collapse;
        }

        td,th {
            border: 1px solid #4a5568;
            border-top: none;
            border-left: none;
        }
        .bnone{
            border-right: none;
        }
        .btop{
            border-top: 1px solid #4a5568;
        }
        .mt-4{
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <header>
        <table class="table t-header ">
            <thead>
                <th class="text-left bnone">Lista de asistencia {{  Carbon\Carbon::parse($start)->format('d/m/Y') }} - {{ Carbon\Carbon::parse($end)->format('d/m/Y') }}</th>
                <th class="text-right bnone">{{ Carbon\Carbon::now()->format('d/m/Y H:i a') }}</th>
            </thead>
        </table>
    </header>
    <main>
        @foreach ($data as $item)
        <table class="table table-markings mt-4">
            <thead>
                <th class="text-left bnone" style="width:30%">
                   <strong>Código:</strong> {{ $item['employee']->cod_marking}}
                </th>
                <th class="text-center uppercase bnone" style="width:40%">
                    {{ $item['employee']->name_employee . ' ' . $item['employee']->surname_employee }}
                </th>
                <th class="text-right uppercase bnone" style="width:30%">
                    {{ $item['employee']->departament->display_name }} | {{ $item['employee']->company->display_name }}
                </th>
            </thead>
        </table>

        <table class="table mt-4">
            <thead class="btop">
                <tr>
                    <th>Fecha</th>
                    <th>Día</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Horas Trabajadas</th>
                    <th>Horas Extras</th>
                    <th>Minutos Tarde</th>
                    <th class="bnone">Permiso Tomado</th>
                </tr>
            </thead>
            <tbody class="text-center text-gray">
                    @foreach ($item["markings"]["markings"] as $itemdos)
                        <tr>
                            <td>{{ $itemdos['date'] }}</td>
                            <td>{{ $itemdos['day'] }}</td>
                            <td>{{ $itemdos['in'] }}</td>
                            <td>{{ $itemdos['out'] }}</td>
                            <td>{{ $itemdos['hours_worked'] }}</td>
                            <td>{{ $itemdos['extra_hours'] }}</td>
                            <td>{{ $itemdos['late_arrivals'] }}</td>
                            <td class="bnone">{{ $itemdos['permission'] }}</td>
                        </tr>
                    @endforeach
            </tbody>
            <tfoot>
                <th>TOTAL:</th>
                <th></th>
                <th></th>
                <th></th>
                <th>
                    {{$item["markings"]['total_hours_worked'] }}
                </th>
                <th>
                    {{$item["markings"]['total_extra_hours'] }}
                </th>
                <th>
                    {{$item["markings"]['total_late_arrivals'] }}
                </th>
                <th class="bnone"></th>
            </tfoot>
        </table>
        @endforeach
    </main>
    <footer>

    </footer>
</body>
</html>