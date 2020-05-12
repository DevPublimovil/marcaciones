@extends('layouts.app')

@section('content')
{{-- <form action="{{ route('logout') }}" method="POST">
@csrf
<button type="submit">salir</button>
</form>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div> --}}

<div class="flex flex-wrap justify-between mx-2 py-2">
    <div class="lg:w-2/6 md:w-2/6 w-0">
        <x-profile/>
    </div>
    <div class="pl-4 lg:w-4/6 md:w-4/6 sm:w-full w-full">
        
    </div>
</div>
@endsection
