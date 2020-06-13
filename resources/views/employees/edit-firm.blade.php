@extends('layouts.app')

@section('content')
    <signature-component :user="'{{$user->id}}'"></signature-component>
@endsection
