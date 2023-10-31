@extends('admin.layout')

@section('admin-title') Character Titles @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Character Titles' => 'admin/data/character-titles']) !!}

<h1>Character Titles</h1>

<p>This is a list of titles that can be applied to characters. Titles are optional, and pre-set titles may be created here for ease of use, or custom titles can be given to individual characters when editing their traits, etc.</p>

<div class="text-right mb-3"><a class="btn btn-primary" href="{{ url('admin/data/character-titles/create') }}"><i class="fas fa-plus"></i> Create New Title</a></div>
@if(!count($titles))
    <p>No titles found.</p>
@else
    <table class="table table-sm title-table">
        <tbody id="sortable" class="sortable">
            @foreach($titles as $title)
                <tr class="sort-item" data-id="{{ $title->id }}">
                    <td>
                        <a class="fas fa-arrows-alt-v handle mr-3" href="#"></a>
                        {!! $title->displayName !!}
                    </td>
                    <td class="text-right">
                        <a href="{{ url('admin/data/character-titles/edit/'.$title->id) }}" class="btn btn-primary">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    <div class="mb-4">
        {!! Form::open(['url' => 'admin/data/character-titles/sort']) !!}
        {!! Form::hidden('sort', '', ['id' => 'sortableOrder']) !!}
        {!! Form::submit('Save Order', ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
    </div>
@endif

@endsection

@section('scripts')
@parent
<script>

$( document ).ready(function() {
    $('.handle').on('click', function(e) {
        e.preventDefault();
    });
    $( "#sortable" ).sortable({
        items: '.sort-item',
        handle: ".handle",
        placeholder: "sortable-placeholder",
        stop: function( event, ui ) {
            $('#sortableOrder').val($(this).sortable("toArray", {attribute:"data-id"}));
        },
        create: function() {
            $('#sortableOrder').val($(this).sortable("toArray", {attribute:"data-id"}));
        }
    });
    $( "#sortable" ).disableSelection();
});
</script>
@endsection
