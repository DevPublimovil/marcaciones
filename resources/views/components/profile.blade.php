<div>
    <div class="w-full bg-white rounded overflow-hidden shadow-md">
        <div class="w-full h-50 bg-cover p-2" >
            <img class="mx-auto w-20 h-20 rounded-full"  src="{{ asset('/storage/' . $employee->user->avatar) }}" id="imageavatar">
            <p class="font-bold text-blue-800 text-center mt-2">
                {{ $employee->name_employee . ' ' . $employee->surname_employee }}
            </p>
        </div>
        <div class="relative px-4 py-4 text-gray-600">
            <p class="text-base mb-2">
                <strong class="text-blue-800"><i class="fa fa-envelope" aria-hidden="true"></i> </strong> {{ $employee->user->email }}
            </p>
            <p class="text-base mb-2">
                <strong class="text-blue-800"><i class="fa fa-briefcase" aria-hidden="true"></i></strong> {{ $employee->position }}
            </p>
            <p class="text-base mb-2">
                <strong class="text-blue-800"><i class="fa fa-flag" aria-hidden="true"></i> </strong> {{ $employee->company->country->name }}
            </p>
            <p class="text-base mb-2">
                <strong class="text-blue-800"><i class="fa fa-users" aria-hidden="true"></i></strong> {{ $employee->departament->display_name }}
            </p>
            <p class="text-base mb-2">
                <strong class="text-blue-800"><i class="fa fa-building" aria-hidden="true"></i></strong> {{ $employee->company->display_name }}
            </p>
            <p class="flex text-primary text-base justify-end items-end">
                <a href="" class="btn-circle">
                    <i class="fa fa-pencil"></i>
                </a>
            </p>
        </div>
    </div>
</div>