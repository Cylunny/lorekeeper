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