@extends('character.creator.layout')

@section('title') Character Creators @endsection

@section('content')
<h1>Character Creators</h1>

<div class="row">
    <div class="col-4">
        <h5>Existing</h5>
        <img class="w-100 bg-dark" src="data:image/png;base64, {{ $image }}"/>
    </div>
    <div class="col-4">
        <h5>Colored</h5>
        <img class="w-100 bg-dark" src="{{ $colored_image }}"/>
    </div>
    <div class="col-4">
        <h5>Merged</h5>
        <img class="w-100 bg-dark" src="{{ $merged_image }}"/>
    </div>
</div>

@endsection


@section('scripts')
@endsection
