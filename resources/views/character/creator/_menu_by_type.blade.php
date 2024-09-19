
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
                        @php $option = $group->layerOptions()->first(); @endphp
                        @if($option->layers()->where('type', 'color')->count() > 0)
                            <h5>{{ $group->name }}</h5>

                            <div id="{{ $group->id . '_choices' }}">
                                <div class="form-group row">
                                @foreach($option->layers()->where('type', 'color')->get() as $colorlayer)
                                        <div class="input-group cp col-xl-6 col-12">
                                            {!! Form::text($group->id . '_' . $colorlayer->id .'_color', '#ffffff', ['class' => 'form-control creator-colorpicker']) !!}
                                            <span class="input-group-append">
                                                <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                            </span>
                                        </div>
                                @endforeach
                                </div>

                                @if(count($option->getMarkingSelect()) > 0)
                                    Markings
                                    <div class="row">
                                        <div class="form-group col-xl-6 col-12">
                                            {!! Form::select($group->id . '_marking', $option->getMarkingSelect(), null, ['class' => 'form-control creator-select']) !!}
                                        </div>
                                        <div class="form-group col-xl-6 col-12">
                                            <div class="input-group cp">
                                                    {!! Form::text($group->id .'_markingcolor', '#ffffff', ['class' => 'form-control creator-colorpicker']) !!}
                                                    <span class="input-group-append">
                                                        <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                                    </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
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