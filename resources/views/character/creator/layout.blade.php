@extends('layouts.app')

@section('title') 
    @yield('title')
@endsection

@section('sidebar')
    @include('character.creator._sidebar')
@endsection

@section('content')
    @yield('content')
@endsection

@section('scripts')
@parent
@endsection