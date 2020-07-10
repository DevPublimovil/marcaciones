<div>
    <div class="w-full bg-white rounded overflow-hidden shadow-md">
        <div class="w-full h-50 bg-cover p-2" >
            <img class="mx-auto w-20 h-20 rounded-full"  src="{{ $user->avatar }}" id="imageavatar">
            <p class="font-bold text-blue-800 text-center mt-2">
                {{ $user->employee->fullname }}
            </p>
        </div>
        <div class="relative px-4 py-4 text-gray-600 text-sm">
            <p class="mb-2">
                <strong class="text-blue-800"><i class="fa fa-envelope" aria-hidden="true"></i> </strong> <span class="text-xs">{{ $user->email }}</span>
            </p>
            <p class="mb-2">
                <strong class="text-blue-800"><i class="fa fa-briefcase" aria-hidden="true"></i></strong> {{ $user->employee->position }}
            </p>
            <p class="mb-2">
                <strong class="text-blue-800"><i class="fa fa-flag" aria-hidden="true"></i> </strong> {{ $user->employee->company->country->name }}
            </p>
            <p class="mb-2">
                <strong class="text-blue-800"><i class="fa fa-users" aria-hidden="true"></i></strong> {{ $user->employee->departament->display_name ?? ''}}
            </p>
            <p class="mb-2">
                <strong class="text-blue-800"><i class="fa fa-building" aria-hidden="true"></i></strong> {{ $user->employee->company->display_name ?? ''}}
            </p>
            <p class="flex text-primary justify-end items-end">
                <a href="{{ route('employees.edit', Auth::id()) }}" class="btn-circle">
                    <i class="fa fa-pencil"></i>
                </a>
            </p>
        </div>
    </div>
</div>
