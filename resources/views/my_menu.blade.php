<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    @foreach($items as $menu_item)
        <li class="nav-item rounded w-full mx-auto h-10 hover:bg-blue-500">
            <a href="{{ $menu_item->link() }}" class="nav-link">
            <i class="text-white nav-icon fa {{ $menu_item->icon_class }}"></i>
            <p class="text-white">
                {{ $menu_item->title }}
            </p>
            </a>
        </li>
    @endforeach
</ul>