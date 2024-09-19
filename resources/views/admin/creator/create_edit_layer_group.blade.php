@extends('admin.layout')

@section('admin-title') Layer Group @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Character Creator' => 'admin/data/creators', 'Edit Creator' => 'admin/data/creators/edit/'.$creator->id,
($group->id ? 'Edit' : 'Create').' Layer Group' => $group->id ? 'admin/data/creators/layergroup/edit/'.$group->id : 'admin/data/creators/layergroup/create']) !!}

<h1>{{ $group->id ? 'Edit' : 'Create' }} Layer Group @if($creator) (<a href="/admin/data/creators/edit/{{ $creator->id }}">{{ $creator->name }}</a>) @endif
    @if($group->id)
    <a href="#" class="btn btn-danger float-right delete-group-button">Delete Layer Group</a>
    @endif
</h1>
<p>Layer groups are meant to group multiple different options for one intended layer. Such a layer could be: hair, eyes, body, wings etc.</p>

{!! Form::open(['url' => $group->id ? 'admin/data/creators/layergroup/edit/'.$group->id : 'admin/data/creators/layergroup/create/'.$creator->id, 'files' => true]) !!}

<h3>Basic Information</h3>

<div class="form-group">
    {!! Form::label('Name') !!}
    {!! Form::text('name', $group->name, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('Description') !!}
    {!! Form::textarea('description', $group->description, ['class' => 'form-control wysiwyg']) !!}
</div>
<div class="row">
    <div class="col-md">
        <div class="form-group">
            {!! Form::checkbox('is_mandatory', 1, $group->id ? $group->is_mandatory : 1, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
            {!! Form::label('is_mandatory', 'Is Mandatory', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If this is turned off, users do not have to select an option for this group.') !!}
        </div>
    </div>
</div>

<div class="text-right">
    {!! Form::submit($group->id ? 'Edit Layer Group' : 'Create Layer Group', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}

<hr>


@if($group->id)
<h3>Layer Options</h3>
<p>
    Put different options for the layer group here! For example, if the group is hair, you could put different hairstyle options here. The actual file layers will then be within the layer option. Layer Options can be created once the group was saved.
    Layer options that are on top of the list will also display first. Those at the bottom will display last. <b>Make sure to save layer option order at least once!</b>
</p>
@if(!count($group->layerOptions))
<p class="alert alert-secondary">No options found.</p>
@else
<table class="table table-sm group-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Sort</th>
            <th></th>
        </tr>
    </thead>
    <tbody id="sortable" class="sortable">
        @foreach($group->layerOptions()->orderBy('sort', 'DESC')->get() as $option)
        <tr class="sort-item" data-id="{{ $option->id }}">
            <td>
                <a class="fas fa-arrows-alt-v handle mr-3" href="#"></a>
                {{ $option->name }}
            </td>
            <td>
                {{ $option->sort }}
            </td>
            <td class="text-right">
                <a href="{{ url('admin/data/creators/layeroption/edit/'.$option->id) }}" class="btn btn-primary">Edit</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div>
    {!! Form::open(['url' => 'admin/data/creators/layeroption/sort']) !!}
    {!! Form::hidden('sort', '', ['id' => 'sortableOrder']) !!}
    {!! Form::submit('Save Layer Option Order', ['class' => 'btn btn-primary float-right']) !!}
    {!! Form::close() !!}
</div>
@endif
<a href="/admin/data/creators/layeroption/create/{{ $group->id }}" class="btn btn-secondary float-right mr-2">Add Layer Option</a>
@endif


@endsection

@section('scripts')
@parent
<script>
    $(document).ready(function() {
        $('.delete-group-button').on('click', function(e) {
            e.preventDefault();
            loadModal("{{ url('admin/data/creators/layergroup/delete') }}/{{ $group->id }}", 'Delete Layer Group');
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