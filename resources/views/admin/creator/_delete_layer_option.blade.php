@if($option)
    {!! Form::open(['url' => 'admin/data/creators/layeroption/delete/'.$option->id]) !!}

    <p>You are about to delete the layer option <strong>{{ $option->name }}</strong>. This is not reversible.</p>
    <p>Are you sure you want to delete <strong>{{ $option->name }}</strong>?</p>

    <div class="text-right">
        {!! Form::submit('Delete Layer Group', ['class' => 'btn btn-danger']) !!}
    </div>

    {!! Form::close() !!}
@else 
    Invalid layer option selected.
@endif