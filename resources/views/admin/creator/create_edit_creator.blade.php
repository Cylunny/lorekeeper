@extends('admin.layout')

@section('admin-title') Character Creator @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Character Creator' => 'admin/data/creators', ($creator->id ? 'Edit' : 'Create').' Creator' => $creator->id ? 'admin/data/creators/edit/'.$creator->id : 'admin/data/creators/create']) !!}

<h1>{{ $creator->id ? 'Edit' : 'Create' }} Character Creator
    @if($creator->id)
    <a href="#" class="btn btn-danger float-right delete-creator-button">Delete Character Creator</a>
    @endif
</h1>

{!! Form::open(['url' => $creator->id ? 'admin/data/creators/edit/'.$creator->id : 'admin/data/creators/create', 'files' => true]) !!}

<h3>Basic Information</h3>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Name') !!}
            {!! Form::text('name', $creator->name, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Cost') !!}
            {!! Form::text('cost', $creator->cost ?? 0, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Currency (Optional)') !!}
            {!! Form::select('currency_id', $currencies, $creator->currency_id, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Item (Optional)') !!}
            {!! Form::select('item_id', $items, $creator->item_id, ['class' => 'form-control']) !!}
        </div>
    </div>
    <p class="p-3">Set either an item or currency that the user must own in order to create a character with this creator. The amount is set under cost. Leave cost at 0 and items/currency empty to make it free.</p>
</div>

<div class="card mb-3 p-4">
    <div class="row">
        @if($creator->image_extension)
        <div class="col-6">
            <a href="{{$creator->imageUrl}}" data-lightbox="entry" data-title="{{ $creator->name }}"><img src="{{$creator->imageUrl}}" class="mw-100 mr-3" style="max-height:400px"></a>
        </div>
        @endif
        <div class="col">
            {!! Form::label('Image (Optional)') !!} {!! add_help('This image is used on the creator page as a header, as well as the index.') !!}
            <div>{!! Form::file('image') !!}</div>
            <div class="text-muted">Recommended size: None (Choose a standard size for all character creator images.)</div>
            @if(isset($creator->image_extension))
            <div class="form-check">
                {!! Form::checkbox('remove_image', 1, false, ['class' => 'form-check-input', 'data-toggle' => 'toggle', 'data-off' => 'Leave Header Image As-Is', 'data-on' => 'Remove Current Header Image']) !!}
            </div>
            @endif
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label('Description') !!}
    {!! Form::textarea('description', $creator->description, ['class' => 'form-control wysiwyg']) !!}
</div>

<div class="row">
    <div class="col-md">
        <div class="form-group">
            {!! Form::checkbox('is_visible', 1, $creator->id ? $creator->is_visible : 1, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
            {!! Form::label('is_visible', 'Is Viewable', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If this is turned off, the creator will not be visible.') !!}
        </div>
    </div>
</div>

<div class="text-right">
    {!! Form::submit($creator->id ? 'Edit Character Creator' : 'Create Character Creator', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}

<hr>

<h3>Layer Groups</h3>
<p>
    Layer groups are meant to group multiple different options for one intended layer. Such a layer could be: hair, eyes, body, wings etc. Layer groups can be created after the creator was saved.
    Make sure to sort them with the layers that should be on top being on top of the list, and those at the bottom at the bottom. <b>Also make sure to save layer group order at least once!</b>
</p>

@if($creator->id)
@if(!count($creator->layerGroups))
<p>No groups found.</p>
@else
<table class="table table-sm group-table">
    <thead>
        <tr>
            <th>Name</th>
            <th></th>
        </tr>
    </thead>
    <tbody id="sortable" class="sortable">
        @foreach($creator->layerGroups()->orderBy('sort', 'DESC')->get() as $group)
        <tr class="sort-item" data-id="{{ $group->id }}">
            <td>
                <a class="fas fa-arrows-alt-v handle mr-3" href="#"></a>
                {{ $group->name }}
            </td>
            <td class="text-right">
                <a href="{{ url('admin/data/creators/layergroup/edit/'.$group->id) }}" class="btn btn-primary">Edit</a>
            </td>
        </tr>
        @endforeach
    </tbody>

</table>
<div>
    {!! Form::open(['url' => 'admin/data/creators/layergroup/sort']) !!}
    {!! Form::hidden('sort', '', ['id' => 'sortableOrder']) !!}
    {!! Form::submit('Save Layer Group Order', ['class' => 'btn btn-primary float-right']) !!}
    {!! Form::close() !!}
</div>
@endif
<a href="/admin/data/creators/layergroup/create/{{ $creator->id }}" class="btn btn-secondary float-right add-layer-group-button">Add Layer Group</a>
@endif


@endsection

@section('scripts')
@parent
<script>
    $(document).ready(function() {
        $('.delete-creator-button').on('click', function(e) {
            e.preventDefault();
            loadModal("{{ url('admin/data/creators/delete') }}/{{ $creator->id }}", 'Delete Character Creator');
        });
        $('.handle').on('click', function(e) {
            e.preventDefault();
        });
        $("#sortable").sortable({
            items: '.sort-item',
            handle: ".handle",
            placeholder: "sortable-placeholder",
            stop: function(event, ui) {
                $('#sortableOrder').val($(this).sortable("toArray", {
                    attribute: "data-id"
                }));
            },
            create: function() {
                $('#sortableOrder').val($(this).sortable("toArray", {
                    attribute: "data-id"
                }));
            }
        });
        $("#sortable").disableSelection();
    });
</script>
@endsection