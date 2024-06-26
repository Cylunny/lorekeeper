@extends('character.creator.layout')

@section('title') Character Creator @endsection

@section('content')
<h1>{{ $creator->name }}</h1>

<!--- The main "image" stacked together, without input we always show the first layer of each group.--->
<div class="row">

    <!--- Layered Images! --->
    <div class="col-lg-7 col-12 bg-secondary rounded">
        <div id="creator-container" class="creator-container">
            @php $isBaseSet = false; @endphp
            @foreach($creator->layerGroups()->orderBy('sort', 'ASC')->get() as $group)
                @if($group->layerOptions()->count() > 0)
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
    @if(Settings::get('creator_sort_by_group'))
    <div class="col-lg-5 col-12">
        <div class="card w-100 h-100">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    @foreach($creator->layerGroups()->orderBy('sort', 'DESC')->get() as $group)
                    <li class="nav-item">
                        <a class="nav-link {{ ($loop->index == 0) ? 'active' : '' }}" id="group-nav-{{ $group->id }}" data-toggle="tab" href="#group-{{ $group->id }}" role="tab">{{ $group->name }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="card-body tab-content">
            @foreach($creator->layerGroups()->orderBy('sort', 'DESC')->get() as $group)
            <div class="tab-pane fade {{ ($loop->index == 0) ? 'show active' : '' }}" id="group-{{ $group->id }}">
                <h3>{{ $group->name }}</h3>
                Base <i>(Changing the base will reset colors!)</i>
                <div class="form-group">
                    {!! Form::select($group->id .'_option', $group->getOptionSelect(), null, ['class' => 'form-control creator-select base-select']) !!}
                </div>
                @php $option = $group->layerOptions()->first(); @endphp

                <div id="{{ $group->id . '_choices' }}">
                    @foreach($option->layers()->where('type', 'color')->get() as $colorlayer)
                        <div class="form-group">
                            <div class="input-group cp">
                                {!! Form::text($group->id . '_' . $colorlayer->id .'_color', '#ffffff', ['class' => 'form-control creator-colorpicker']) !!}
                                <span class="input-group-append">
                                    <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                </span>
                            </div>
                        </div>
                    @endforeach

                    @if(count($option->getMarkingSelect()) > 0)
                        Markings
                        <div class="form-group">
                            {!! Form::select($group->id . '_marking', $option->getMarkingSelect(), null, ['class' => 'form-control creator-select']) !!}
                        </div>
                        <div class="form-group">
                            <div class="input-group cp">
                                    {!! Form::text($group->id .'_markingcolor', '#ffffff', ['class' => 'form-control creator-colorpicker']) !!}
                                    <span class="input-group-append">
                                        <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                    </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
            </div>
            <div class="card-footer text-muted">
                @if($creator->cost > 0) <i>This will create the character and remove the payment from your account!</i> @endif
                <a href="#" class="btn btn-warning float-right delete-creator-button">Create Character</a>
            </div>
        </div>
    </div>

    @else 

    <div class="col-lg-5 col-12">
        <div class="card w-100 h-100">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" id="nav-base" data-toggle="tab" href="#base-tab" role="tab">Base</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="nav-color" data-toggle="tab" href="#color-tab" role="tab">Color</a>
                    </li>
                </ul>
            </div>
            <div class="card-body tab-content">
                <div class="tab-pane fade show active" id="base-tab">
                    @foreach($creator->layerGroups()->orderBy('sort', 'DESC')->get() as $group)
                    <h5>{{ $group->name }}</h5>
                    <div class="form-group">
                        {!! Form::select($group->id .'_option', $group->getOptionSelect(), null, ['class' => 'form-control creator-select base-select']) !!}
                    </div>
                    @endforeach
                </div>
                <div class="tab-pane fade" id="color-tab">
                    @foreach($creator->layerGroups()->orderBy('sort', 'DESC')->get() as $group)

                    <h5>{{ $group->name }}</h5>
                    @php $option = $group->layerOptions()->first(); @endphp

                    <div id="{{ $group->id . '_choices' }}">
                        @foreach($option->layers()->where('type', 'color')->get() as $colorlayer)
                            <div class="form-group">
                                <div class="input-group cp">
                                    {!! Form::text($group->id . '_' . $colorlayer->id .'_color', '#ffffff', ['class' => 'form-control creator-colorpicker']) !!}
                                    <span class="input-group-append">
                                        <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                    </span>
                                </div>
                            </div>
                        @endforeach

                        @if(count($option->getMarkingSelect()) > 0)
                            Markings
                            <div class="form-group">
                                {!! Form::select($group->id . '_marking', $option->getMarkingSelect(), null, ['class' => 'form-control creator-select']) !!}
                            </div>
                            <div class="form-group">
                                <div class="input-group cp">
                                        {!! Form::text($group->id .'_markingcolor', '#ffffff', ['class' => 'form-control creator-colorpicker']) !!}
                                        <span class="input-group-append">
                                            <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                        </span>
                                </div>
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="card-footer text-muted">
                @if($creator->cost > 0) <i>This will create the character and remove the payment from your account!</i> @endif
                <a href="#" class="btn btn-warning float-right delete-creator-button">Create Character</a>
            </div>
        </div>
    </div>

    @endif
</div>


@endsection


@section('scripts')
@include('character.creator._creator_js')

@endsection