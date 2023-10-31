@if($title)
    {!! Form::open(['url' => 'admin/data/character-titles/delete/'.$title->id]) !!}

    <p>You are about to delete the title <strong>{{ $title->name }}</strong>. This is not reversible. If characters that have this title exist, you will not be able to delete this title.</p>
    <p>Are you sure you want to delete <strong>{{ $title->name }}</strong>?</p>

    <div class="text-right">
        {!! Form::submit('Delete Title', ['class' => 'btn btn-danger']) !!}
    </div>

    {!! Form::close() !!}
@else
    Invalid title selected.
@endif
