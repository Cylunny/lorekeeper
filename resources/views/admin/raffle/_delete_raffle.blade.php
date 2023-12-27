@if($raffle)
    {!! Form::open(['url' => 'admin/raffles/delete/'.$raffle->id]) !!}

    <p>You are about to delete the raffle <strong>{{ $raffle->name }}</strong>. This is not reversible.</p>
    <p>Are you sure you want to delete <strong>{{ $raffle->name }}</strong>?</p>

    <div class="text-right">
        {!! Form::submit('Delete Raffle', ['class' => 'btn btn-danger']) !!}
    </div>

    {!! Form::close() !!}
@else 
    Invalid raffle selected.
@endif