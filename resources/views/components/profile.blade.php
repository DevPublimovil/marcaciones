<div>
    <div class="w-full bg-white rounded overflow-hidden shadow-md">
        <div class="w-full h-50 bg-cover p-2" style="background-image: url('/images/bgprofile.jpg')">
            <img class="mx-auto w-20 h-20 rounded-full"  src="{{ asset('/storage/' . $employee->user->avatar) }}" id="imageavatar">
            <p class="font-bold text-white text-center mt-2">
                {{ $employee->name_employee . ' ' . $employee->surname_employee }}
            </p>
        </div>
        <div class="relative px-4 py-4 text-gray-600">
            <p class="text-base mb-2">
                <strong class="text-gray-600">Correo: </strong> {{ $employee->user->email }}
            </p>
            <p class="text-base mb-2">
                <strong class="text-gray-600">Puesto: </strong> {{ $employee->position }}
            </p>
            <p class="text-base mb-2">
                <strong class="text-gray-600">País: </strong> {{ $employee->company->country->name }}
            </p>
            <p class="text-base mb-2">
                <strong class="text-gray-600">Departamento: </strong> {{ $employee->departament->display_name }}
            </p>
            <p class="text-base mb-2">
                <strong class="text-gray-600">Compañia: </strong> {{ $employee->company->display_name }}
            </p>
            <p class="text-base mb-2">
                <strong class="text-gray-600">Tipo de empleado: </strong> {{ $employee->typeemployee->name_type_employee }}
            </p>
            <p class="flex text-primary text-base justify-end items-end">
                <a href="" class="btn-circle">
                    <i class="fa fa-pencil"></i>
                </a>
            </p>
        </div>
    </div>
</div>