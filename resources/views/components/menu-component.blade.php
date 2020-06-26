<div class="menu-component">
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar bg-blue-600 text-white elevation-4">
        <!-- Brand Logo -->
        <a href="/home" class="brand-link">
        {{-- <img src="" alt="Logo" class="brand-image rounded-full"
            style="opacity: .8"> --}}
        <span class="brand-text font-weight-light">CLOCKBOT</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar mt-6">

            <!-- Sidebar Menu -->
            <nav class="mt-2 text-xs md:text-base">
                    @if ($user->role->name == 'rrhh')
                        {{menu('rrhh', 'my_menu')}}
                    @elseif($user->role->name == 'empleado')
                        {{menu('employee', 'my_menu')}}
                    @elseif($user->role->name == 'gerente')
                        {{menu('manager', 'my_menu')}}
                    @elseif($user->role->name == 'subjefe')
                        {{menu('subjefe', 'my_menu')}}
                    @endif
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
</div>