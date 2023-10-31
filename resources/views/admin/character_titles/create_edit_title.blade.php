@extends('admin.layout')

@section('admin-title') Character Titles @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Character Titles' => 'admin/data/character-titles', ($title->id ? 'Edit' : 'Create').' Title' => $title->id ? 'admin/data/character-titles/edit/'.$title->id : 'admin/data/character-titles/create']) !!}

<h1>{{ $title->id ? 'Edit' : 'Create' }} Title
    @if($title->id)
        <a href="#" class="btn btn-danger float-right delete-title-button">Delete Title</a>
    @endif
</h1>

{!! Form::open(['url' => $title->id ? 'admin/data/character-titles/edit/'.$title->id : 'admin/data/character-titles/create', 'files' => true]) !!}

<h3>Basic Information</h3>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Title') !!}
            {!! Form::text('title', $title->title, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Short Title (Optional)') !!} {!! add_help('Will be used in place of the full title for display alongside character name, etc. if set.') !!}
            {!! Form::text('short_title', $title->short_title, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label('Rarity (Optional)') !!}
    {!! Form::select('rarity_id', $rarities, $title->rarity_id, ['class' => 'form-control', 'placeholder' => 'Select a Rarity']) !!}
</div>

<div class="form-group">
    {!! Form::label('World Page Image (Optional)') !!} {!! add_help('This image is used only on the world information pages.') !!}
    <div>{!! Form::file('image') !!}</div>
    <div class="text-muted">Recommended size: 200px x 200px</div>
    @if($title->has_image)
        <div class="form-check">
            {!! Form::checkbox('remove_image', 1, false, ['class' => 'form-check-input']) !!}
            {!! Form::label('remove_image', 'Remove current image', ['class' => 'form-check-label']) !!}
        </div>
    @endif
</div>

<div class="form-group">
    {!! Form::label('Description (Optional)') !!}
    {!! Form::textarea('description', $title->description, ['class' => 'form-control wysiwyg']) !!}
</div>

<div class="text-right">
    {!! Form::submit($title->id ? 'Edit' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}

@if($title->id)
    <h3>Preview</h3>
    <div class="card mb-3">
        <div class="card-body">
            @include('world._title_entry', ['imageUrl' => $title->titleImageUrl, 'name' => $title->displayNameFull, 'description' => $title->parsed_description, 'searchCharactersUrl' => $title->searchCharactersUrl])
        </div>
    </div>
@endif

@endsection

@section('scripts')
@parent
<script>
$( document ).ready(function() {
    $('.delete-title-button').on('click', function(e) {
        e.preventDefault();
        loadModal("{{ url('admin/data/character-titles/delete') }}/{{ $title->id }}", 'Delete Title');
    });
});

</script>
@endsection
