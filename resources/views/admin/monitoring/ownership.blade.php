@extends('admin.layout')

@section('admin-title') Ownership Monitoring @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Ownership Monitoring' => 'admin/monitoring/ownership']) !!}

<h1>Ownership Monitoring</h1>
<p>On this page you can check the ownership of specific items by user or character.</p>

{!! Form::open(['method' => 'GET', 'class' => '']) !!}
<div class="form-inline">
    <div class="form-group ml-3 mb-3 col-10">
        @include('admin.monitoring._monitoring_select')
    </div>
    <div class="form-group ml-3 mb-3">
        {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
    </div>
</div>
{!! Form::close() !!}
@include('admin.monitoring._monitoring_select_row')

<hr>

@if($object)
<h4>{!! $object->displayName ?? $object->name !!} - Ownership Statistics</h4>

@php
$loopOver = ($sort == 'current') ? $currentlyOwnedByUserId : $alltimeOwnedByUserId;
@endphp
<table class="table table-sm">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">User</th>
            <th scope="col">Currently Owned Number</th>
            <th scope="col">All Time Owned Number</th>
        </tr>
    </thead>
    <tbody>
        @foreach($loopOver as $userId => $objects)
        <tr>
            <th scope="row">{{ $loop->index + 1}}</th>
            <td>{!! $usersByUserId[$userId]->first()->displayName ?? 'Error: Unknown User' !!}</td>
            <td>{{ $currentlyOwnedByUserId[$userId] ?? 0 }}</td>
            <td>{{ $alltimeOwnedByUserId[$userId] ?? 0 }}</td>
        </tr>
        <tr>
            @endforeach
    </tbody>
</table>
@endif

@endsection
@section('scripts')
@parent
@include('js._monitoring_js')
@endsection