@extends('character.creator.layout')

@section('title') Character Creators @endsection

@section('content')
<h1>Character Creators</h1>

<div class="row">
    @foreach($creators as $creator)
        <div class="col-md-3 col-6 mb-3 text-center">
            <div class="">
                <a href="{{ $creator->url }}"><img src="{{ $creator->imageUrl }}" alt="{{ $creator->name }}" style="max-width:300px;" /></a>
            </div>
            <div class="mt-1">
                <a href="{{ $creator->url }}" class="h5 mb-0">{{ $creator->name }}</a>
            </div>
        </div>
    @endforeach
</div>


@endsection


@section('scripts')
@endsection
