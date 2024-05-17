@extends('character.creator.layout')

@section('title') Character Creator @endsection

@section('content')
<h1>{{ $creator->name }}</h1>

<!--- The main "image" stacked together, without input we always show the first layer of each group.--->
<div class="row">

    <!--- Layered Images! --->
    <div class="col-lg-7 col-12">
        <div class="creator-container bg-secondary rounded">
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
    <div class="col-lg-5 col-12">
        <div class="card w-100 h-100">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    @foreach($creator->layerGroups()->orderBy('sort', 'ASC')->get() as $group)
                    <li class="nav-item">
                        <a class="nav-link {{ ($loop->index == 0) ? 'active' : '' }}" id="group-nav-{{ $group->id }}" data-toggle="tab" href="#group-{{ $group->id }}" role="tab">{{ $group->name }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="card-body tab-content">
            @foreach($creator->layerGroups()->orderBy('sort', 'ASC')->get() as $group)
            <div class="tab-pane fade {{ ($loop->index == 0) ? 'show active' : '' }}" id="group-{{ $group->id }}">
                {!! Form::open(['url' => '']) !!}
                <h3>{{ $group->name }}</h3>
                Base
                <div class="form-group">
                    {!! Form::select('type', $group->getOptionSelect(), null, ['class' => 'form-control']) !!}
                </div>
                @php $option = $group->layerOptions()->first(); @endphp

                @foreach($option->layers()->where('type', 'color')->get() as $colorlayer)
                    <div class="form-group">
                        <div class="input-group cp">
                            {!! Form::text('color', null, ['class' => 'form-control']) !!}
                            <span class="input-group-append">
                                <span class="input-group-text colorpicker-input-addon"><i></i></span>
                            </span>
                        </div>
                    </div>
                @endforeach

                @if(count($option->getMarkingSelect()) > 0)
                Markings
                <div class="form-group">
                    {!! Form::select('type', $option->getMarkingSelect(), null, ['class' => 'form-control']) !!}
                 </div>
                 <div class="form-group">
                        <div class="input-group cp">
                            {!! Form::text('color', null, ['class' => 'form-control']) !!}
                            <span class="input-group-append">
                                <span class="input-group-text colorpicker-input-addon"><i></i></span>
                            </span>
                        </div>
                    </div>
                @endif
                {!! Form::submit('Update '.$group->name , ['class' => 'btn btn-primary float-right']) !!}
                {!! Form::close() !!}
            </div>
            @endforeach
            </div>
            <div class="card-footer text-muted">
                @if($creator->cost > 0) <i>This will create the character and remove the payment from your account!</i> @endif
                <a href="#" class="btn btn-warning float-right delete-creator-button">Create Character</a>
            </div>


        </div>
    </div>
</div>


@endsection


@section('scripts')
@endsection