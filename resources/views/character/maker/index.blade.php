@extends('character.maker.layout')

@section('maker-title') Character Maker @endsection

@section('maker-content')
<h1>Character Maker</h1>

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
