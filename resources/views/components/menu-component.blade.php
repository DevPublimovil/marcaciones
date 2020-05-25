<div class="menu-component">
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar bg-blue-600 text-white elevation-4">
        <!-- Brand Logo -->
        <a href="/home" class="brand-link">
        <img src="" alt="Logo" class="brand-image rounded-full"
            style="opacity: .8">
        <span class="brand-text font-weight-light">ICLOCK</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar mt-6">

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                    with font-awesome or any other icon font library -->
                    @if ($user->role->name == 'rrhh')
                        @foreach (config('iclock.menu-rh') as $item)
                            <li class="nav-item rounded w-full mx-auto h-10 hover:bg-blue-500">
                                <a href="{{$item['route']}}" class="nav-link">
                                <i class="text-white nav-icon fa {{$item['icon']}}"></i>
                                <p class="text-white">
                                    {{ $item['name'] }}
                                </p>
                                </a>
                            </li>
                        @endforeach
                    @elseif($user->role->name == 'empleado')
                        @foreach (config('iclock.menu-employee') as $item)
                            <li class="nav-item rounded w-full mx-auto h-10 hover:bg-blue-500">
                                <a href="{{$item['route']}}" class="nav-link">
                                <i class="text-white nav-icon fa {{$item['icon']}}"></i>
                                <p class="text-white">
                                    {{ $item['name'] }}
                                </p>
                                </a>
                            </li>
                        @endforeach
                    @elseif($user->role->name == 'gerente')
                        @foreach (config('iclock.menu-gte') as $item)
                            <li class="nav-item rounded w-full mx-auto h-10 hover:bg-blue-500">
                                <a href="{{$item['route']}}" class="nav-link">
                                <i class="text-white nav-icon fa {{$item['icon']}}"></i>
                                <p class="text-white">
                                    {{ $item['name'] }}
                                </p>
                                </a>
                            </li>
                        @endforeach
                    @endif
                    
                    {{-- <li class="nav-item rounded w-full mx-auto h-10 hover:bg-blue-500">
                        <a href="/actions" class="nav-link">
                        <i class="text-white nav-icon fa fa-history"></i>
                        <p class="text-white">
                            Historial
                        </p>
                        </a>
                    </li>
                    <li class="nav-item rounded w-full mx-auto h-10 hover:bg-blue-500">
                        <a href="/marcaciones/index" class="nav-link">
                        <i class="text-white nav-icon fa fa-check-circle-o"></i>
                        <p class="text-white">
                            Asistencia
                        </p>
                        </a>
                    </li>
                    <li class="nav-item rounded w-full mx-auto h-10 hover:bg-blue-500">
                        <a href="/employees" class="nav-link">
                        <i class="text-white nav-icon fa fa-users"></i>
                        <p class="text-white">
                            Empleados
                        </p>
                        </a>
                    </li>
                    <li class="nav-item rounded w-full mx-auto h-10 hover:bg-blue-500">
                        <a href="/reports" class="nav-link">
                        <i class="text-white nav-icon fa fa-id-card" aria-hidden="true"></i>
                        <p class="text-white">
                            Tarjeta
                        </p>
                        </a>
                    </li> --}}
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
</div>