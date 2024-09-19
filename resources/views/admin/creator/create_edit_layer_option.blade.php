@extends('admin.layout')

@section('admin-title') Layer Option @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Character Creator' => 'admin/data/creators', 'Edit Creator' => 'admin/data/creators/edit/'.$creator->id,
($group->id ? 'Edit' : 'Create').' Layer Group' => $group->id ? 'admin/data/creators/layergroup/edit/'.$group->id : 'admin/data/creators/layergroup/create',
($option->id ? 'Edit' : 'Create').' Layer Option' => $option->id ? 'admin/data/creators/layeroption/edit/'.$option->id : 'admin/data/creators/layeroption/create']) !!}

<h1>{{ $option->id ? 'Edit' : 'Create' }} Layer Option @if($creator && $group) (<a href="/admin/data/creators/edit/{{ $creator->id }}">{{ $creator->name }}</a>: <a href="/admin/data/creators/layergroup/edit/{{ $group->id }}">{{ $group->name }}</a>) @endif
    @if($option->id)
    <a href="#" class="btn btn-danger float-right delete-option-button">Delete Layer Option</a>
    @endif
</h1>
<p>
    A layer option for a layer group such as hair could be something like hairstyle 1. You will be able to upload image files for the different layers that create hairstyle 1 here!
</p>

{!! Form::open(['url' => $option->id ? 'admin/data/creators/layeroption/edit/'.$option->id : 'admin/data/creators/layeroption/create/'.$group->id, 'files' => true]) !!}

<h3>Basic Information</h3>

<div class="form-group">
    {!! Form::label('Name') !!}
    {!! Form::text('name', $option->name, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('Description') !!}
    {!! Form::textarea('description', $option->description, ['class' => 'form-control wysiwyg']) !!}
</div>

<div class="text-right">
    {!! Form::submit($option->id ? 'Edit Layer Option' : 'Create Layer Option', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}

<hr>


@if($option->id)
<h3>Layers</h3>
<p>
    This is where you can upload the images for your layer option. In order to allow users to color in the different parts later, a layer option should consist of at least two layers: A line layer on top, and a base color layer at the bottom.
    You can, however, add multiple color or detail layers as well.</b>
</p>
<ul>
    <li><b>Line Layers</b> should be your transparent linework.</li>
    <li><b>Color Layers</b> should be the base color matching the lines. You can make multiple color layers, such as a seperate one for sclera and iris of an eye.These will each have their own color that can be set and are not a choice.</li>
    <li><b>Detail Layers</b> are intended for use cases such as adding markings on top of a base layer. Users can only pick one out of all detail layers. The picked layer can be colored.</li>

</ul>


@if(!$option->layers->count() > 0)
    <p class="alert alert-secondary">No layers found.</p>
@else
    <table class="table table-sm group-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Image</th>
                <th>Sort</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="sortable" class="sortable">
            @foreach($option->layers()->orderBy('sort', 'DESC')->get() as $layer)
            <tr class="sort-item" data-id="{{ $layer->id }}">

                <td class="text-left">
                    <a class="fas fa-arrows-alt-v handle mr-3" href="#"></a>
                    {{ $layer->name }}
                </td>
                <td>
                    {{ $layer->type }}
                </td>
                <td>
                    <a href="{{ $layer->imageUrl }}" data-lightbox="entry" data-title="{{ $layer->name }}">
                        <img src="{{ $layer->imageUrl }}" class="bg-secondary rounded image" style="max-height:100px;" data-toggle="tooltip" title="Click to view larger size" alt="{{ $layer->name }}" />
                    </a>
                </td>
                <td>
                    {{ $layer->sort }}
                </td>
                <td class="text-right">
                    <a href="#" data-id="{{$layer->id}}" class="btn btn-primary edit-layer">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {!! Form::open(['url' => 'admin/data/creators/layer/sort']) !!}
        {!! Form::hidden('sort', '', ['id' => 'sortableOrder']) !!}
        {!! Form::submit('Save Layer Order', ['class' => 'btn btn-primary float-right']) !!}
        {!! Form::close() !!}
    </div>
    @endif
    <a href="#" class="btn btn-secondary float-right mr-2" id="add-layer">Add Layer</a>
    <hr>

    <h3>Preview</h3>
    This is how the creator will stack these layers!

    <div class="creator-container bg-secondary rounded">
        @foreach($option->layers()->orderBy('sort', 'ASC')->get() as $layer)
            @if($loop->index == 0)
            <img src="{{ $layer->imageUrl }}" class="creator-base" style="max-width:100%;" />
            @else
            <img src="{{ $layer->imageUrl }}" class="creator-layer" style="max-width:100%;" />
            @endif
        @endforeach
    </div>

@endif


@endsection

@section('scripts')
@parent
<script>
    $(document).ready(function() {
        $('.delete-option-button').on('click', function(e) {
            e.preventDefault();
            loadModal("{{ url('admin/data/creators/layeroption/delete') }}/{{ $option->id }}", 'Delete Layer Option');
        });

        $('#add-layer').on('click', function(e) {
            e.preventDefault();
            loadModal("{{ url('admin/data/creators/layer/create') }}/{{ $option->id }}", 'Create Layer');
        });

        $('.edit-layer').on('click', function(e) {
            e.preventDefault();
            loadModal("{{ url('admin/data/creators/layer/edit') }}/" + $(this).data('id'), 'Edit Layer');
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