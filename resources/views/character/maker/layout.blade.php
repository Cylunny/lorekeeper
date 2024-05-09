@extends('layouts.app')

@section('title') 
    @yield('maker-title')
@endsection

@section('sidebar')
    @include('character.maker._sidebar')
@endsection

@section('content')
    @yield('maker-content')
@endsection

@section('scripts')
@parent
@endsection