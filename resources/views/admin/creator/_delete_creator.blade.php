@if($creator)
    {!! Form::open(['url' => 'admin/data/creator/delete/'.$creator->id]) !!}

    <p>You are about to delete the character creator <strong>{{ $creator->name }}</strong>. This is not reversible. If you would like to preserve the content while preventing users from accessing the creator, you can use the viewable setting instead to hide it.</p>
    <p>Are you sure you want to delete <strong>{{ $creator->name }}</strong>?</p>

    <div class="text-right">
        {!! Form::submit('Delete Character Creator', ['class' => 'btn btn-danger']) !!}
    </div>

    {!! Form::close() !!}
@else 
    Invalid character creator selected.
@endif