@if($group)
    {!! Form::open(['url' => 'admin/data/groups/layergroup/delete/'.$group->id]) !!}

    <p>You are about to delete the layer group <strong>{{ $group->name }}</strong>. This is not reversible.</p>
    <p>Are you sure you want to delete <strong>{{ $group->name }}</strong>?</p>

    <div class="text-right">
        {!! Form::submit('Delete Layer Group', ['class' => 'btn btn-danger']) !!}
    </div>

    {!! Form::close() !!}
@else 
    Invalid layer group selected.
@endif