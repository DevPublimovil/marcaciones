@extends('layouts.app')

@section('content')
    <personal-action-component :types="{{ $typeactions }}" :user="{{ $user}}"></personal-action-component>
@endsection