@extends('world.layout')

@section('title') Character Titles @endsection

@section('content')
{!! breadcrumbs(['World' => 'world', 'Character Titles' => 'world/character-titles']) !!}
<h1>Character Titles</h1>

<div>
    {!! Form::open(['method' => 'GET', 'class' => 'form-inline justify-content-end']) !!}
        <div class="form-group mr-3 mb-3">
            {!! Form::text('title', Request::get('title'), ['class' => 'form-control']) !!}
        </div>
        <div class="form-group mr-3 mb-3">
            {!! Form::select('rarity_id', $rarities, Request::get('rarity_id'), ['class' => 'form-control']) !!}
        </div>
        <div class="form-group mb-3">
            {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
        </div>
    {!! Form::close() !!}
</div>

{!! $titles->render() !!}
@foreach($titles as $title)
    <div class="card mb-3">
        <div class="card-body">
        @include('world._title_entry', ['imageUrl' => $title->titleImageUrl, 'name' => $title->displayNameFull, 'description' => $title->parsed_description, 'searchCharactersUrl' => $title->searchCharactersUrl])
        </div>
    </div>
@endforeach
{!! $titles->render() !!}

<div class="text-center mt-4 small text-muted">{{ $titles->total() }} result{{ $titles->total() == 1 ? '' : 's' }} found.</div>

@endsection
