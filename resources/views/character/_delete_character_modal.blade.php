{!! Form::open(['/myo/'.$character->id.'/delete']) !!}
    <p>This will delete the entire MYO and its images. <strong>This data will not be retrievable.</strong> </p>
    <p>Are you sure you want to do this?</p>

    <div class="text-right">
        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
    </div>
{!! Form::close() !!}