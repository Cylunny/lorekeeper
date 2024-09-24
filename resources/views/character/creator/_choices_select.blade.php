
@if($option)
    <div class="form-group row">
    @foreach($option->layers()->where('type', 'color')->orderBy('sort', 'DESC')->get() as $colorlayer)
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
@else
<div class="p-2">No Option selected.</div>
@endif