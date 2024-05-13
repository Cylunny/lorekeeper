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

<div class="text-right">
    {!! Form::submit($group->id ? 'Edit' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>

<hr>

<h3>Layer Options</h3>
<p>
    Put different options for the layer group here! For example, if the group is hair, you could put different hairstyle options here. The actual file layers will then be within the layer option. Layer Options can be created once the group was saved.
    Layer options that are on top of the list will also display first. Those at the bottom will display last. <b>Make sure to save layer option order at least once!</b>
</p>
@if($group->id)
<a href="/admin/data/creators/layeroption/create/{{ $group->id }}" class="btn btn-secondary float-right">Add Layer Option</a>
@endif

{!! Form::close() !!}

@endsection

@section('scripts')
@parent
<script>
    $(document).ready(function() {
        $('.delete-group-button').on('click', function(e) {
            e.preventDefault();
            loadModal("{{ url('admin/data/creators/layergroup/delete') }}/{{ $group->id }}", 'Delete Layer Group');
        });
    });
</script>
@endsection