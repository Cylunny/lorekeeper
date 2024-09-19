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
        @if($creator->allow_character_creation == 1)
            <div class="card-footer text-muted text-right">
                @if($creator->cost > 0) 
                <p><i>This will create the character and <b>remove {{ $creator->cost }} {!! isset($creator->currency) ? $creator->currency->displayName : $creator->item->displayName !!} from your account!</b></i></p> 
                @endif
                <a href="#" class="btn btn-warning float-right create-character-button">Create Character</a>
            </div>
        @endif
    </div>
</div>