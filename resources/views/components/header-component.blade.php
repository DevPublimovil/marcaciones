<div class="header">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- navbar left items -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
            </li>
            @if(Auth::user()->role_id == 3)
                <li>
                    <span class="nav-link">
                        <form action="{{ route('home.company') }}" method="POST" class="p-0 m-0" id="form-change-company">
                            @csrf
                            <select class="cursor-pointer" name="company"  onchange="event.preventDefault();document.getElementById('form-change-company').submit();">
                                @foreach ($user->companiesResources as $resource)
                                    <option value="{{$resource->company->id}}" @if ($resource->company->id == $user->appcompany->company_id) selected @endif>{{$resource->company->display_name}}</option>
                                @endforeach
                            </select>
                        </form>
                    </span>
                </li>
            @endif
        </ul>
        <ul class="navbar-nav ml-auto">
            <header-component :info="{{json_encode($user)}}"></header-component>
        </ul>
    </nav>
</div>
