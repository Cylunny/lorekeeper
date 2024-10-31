@extends('admin.layout')

@section('admin-title') Species Approval Checklist @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Species Approval Checklists' => 'admin/data/species-approval-checklists', 'Edit Species Approval Checklist' => 'admin/data/species-approval-checklists/edit/'.$species->id ]) !!}

<h1>
    Edit "{{ $species->name }}" Approval Checklist
</h1>

{!! Form::open(['url' => 'admin/data/species-approval-checklists/edit/'.$species->id , 'files' => true]) !!}

<h3>Basic Information</h3>

<div class="form-group">
    {!! Form::label('General Species Checklist (Optional)') !!}
    {!! Form::textarea('description', $checklist->description ?? 'No species approval checklist provided.', ['class' => 'form-control wysiwyg']) !!}
</div>

<h3>Subtype Specifics</h3>
@foreach($species->subtypes as $subtype)

<h5>{{ $subtype->name }}</h5>

<div class="form-group">
    {!! Form::label('Subtype Checklist (Optional)') !!}
    {!! Form::textarea('subtype_descriptions[]', $subtype->checklist->description ?? 'No subtype specific checklist provided.', ['class' => 'form-control wysiwyg']) !!}
</div>
{!! Form::hidden('subtype_ids[]', $subtype->id , ['id' => 'sortableOrder']) !!}

@endforeach

<div class="text-right">
    {!! Form::submit('Edit', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::hidden('species_id', $species->id , ['id' => 'sortableOrder']) !!}

{!! Form::close() !!}



@endsection

