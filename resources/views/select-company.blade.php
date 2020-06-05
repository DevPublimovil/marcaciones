@extends('layouts.public')

@section('content')
    <div class="container">
        <h2 class="text-2xl font-bold text-white">ICLOCK</h2>
        <div class="mb-8"></div>
            <div class="flex justify-center items-center align-middle">
                <form action="{{ route('home.company') }}" method="POST">
                    @csrf
                   <h3 class="text-left text-white">Compañia</h3>
                   <div class="text-left">
                    <select class="form-select cursor-pointer w-full" name="company" id="company">
                        @foreach($companies as $company)
                            <option value="{{$company->company->id }}" @if ($company->company->id == $user->appcompany->company_id) selected @endif>{{$company->company->display_name }}</option>
                        @endforeach
                    </select>
                   </div>
                   <button type="submit" class="w-1/2 mt-8 bg-blue-800 hover:bg-blue-900 h-10 rounded-lg m-2 outline-none border border-blue-600 text-white uppercase" type="submit">{{ __('Aceptar') }}</button>
                </form>
            </div>
    </div>
@endsection