{!! Form::open(['url' => $layer->id ? 'admin/data/creators/layer/edit/'.$layer->id : 'admin/data/creators/layer/create/'.$option->id, 'files' => true]) !!}

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Name (Optional)') !!}
            {!! Form::text('name', $layer->name, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Type') !!}
            {!! Form::select('type', ['lines' => 'Lines', 'color' => 'Color', 'detail' => 'Detail'], $layer->type, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>

<h5>Image</h5>
@if($layer->image_extension)
<div class="row m-auto w-100 bg-secondary rounded">
    <a href="{{$layer->imageUrl}}" data-lightbox="entry" data-title="{{ $layer->name }}" class="mw-100 m-auto"><img src="{{$layer->imageUrl}}" class="mw-100 m-auto" style="max-height:400px"></a>
</div>
@endif

<div class="row p-3">
    <p>Layer images must be transparent PNG files, or layering will not work. Layers of type line can be lines of any color. Layers of type color or detail must be colored in white (#FFFFFF / RGB(255,255,255)).</p>

    <div class="card p-3 w-100">
        {!! Form::label('Image') !!}
        <div>{!! Form::file('image') !!}</div>
        <div class="text-muted">Recommended size: None, but you must make sure your files actually layer on top of each other perfectly.</div>
    </div>
</div>

@if ($layer->id)
<div class="form-check">
    {!! Form::checkbox('delete', 1, false, ['class' => 'form-check-input']) !!}
    {!! Form::label('delete', 'Delete Layer', ['class' => 'form-check-label']) !!}
</div>
@endif

<div class="text-right">
    {!! Form::submit($layer->id ? 'Edit Layer' : 'Create Layer', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}