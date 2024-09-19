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
            @php $isBaseSet = false; @endphp
            @foreach($creator->layerGroups()->orderBy('sort', 'ASC')->get() as $group)
                @if($group->layerOptions()->count() > 0 && $group->is_mandatory)
                    @foreach($group->layerOptions[0]->layers()->orderBy('sort', 'ASC')->get() as $layer)
                        @if(!$isBaseSet)
                            <img src="{{ $layer->imageUrl }}" class="creator-base" style="max-width:100%;" data-id="{{ $layer->id }}"/>
                            @php $isBaseSet = true; @endphp
                        @else
                            <img src="{{ $layer->imageUrl }}" class="creator-layer" style="max-width:100%;" data-id="{{ $layer->id }}"/>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>
    </div>

    <!--- Menu! --->
    @if(Settings::get('creator_sort_by_group') && Settings::get('creator_sort_by_group') == 1)
        @include('character.creator._menu_by_group')
    @else 
        @include('character.creator._menu_by_type')
    @endif


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