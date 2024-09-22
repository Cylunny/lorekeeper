@extends('character.creator.layout')

@section('title') Character Creator @endsection

@section('content')
<h1>{{ $creator->name }}</h1>
<div class="parsed-text">
    {!! $creator->parsed_description !!}
</div>


<!--- The main "image" stacked together, without input we always show the first layer of each group.--->
<div class="row" id="creator">

    <!--- Layered Images! --->
    <div class="col-lg-7 col-12 rounded" style="background-color:grey;">
        <div id="creator-container" class="creator-container">
            @foreach($creator->layerGroups()->orderBy('sort', 'ASC')->get() as $group)
                <div id="group-{{ $group->id }}"><img src="#" class="creator-layer hide" style="max-width:100%;"/></div>
            @endforeach
        </div>
    </div>

    <!--- Menu! --->

    @include('character.creator._menu_by_type')



</div>


@endsection


@section('scripts')
@include('character.creator._creator_js')
<script>
    $('.create-character-button').on('click', function(e) {
        e.preventDefault();
        loadModal("{{ url('character-creator') }}/{{ $creator->id }}/create", 'Create Character');
    });
</script>
@endsection