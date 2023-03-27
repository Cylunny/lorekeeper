<div class="card p-3 mb-2">
    <h3>Staff Profile</h3>
    {!! Form::open(['url' => 'account/staff-profile']) !!}
        <div class="form-group">
            {!! Form::label('text', 'Staff Profile Text') !!} {!! add_help('This is the short profile that will display on the team page. This is a text-only field, meaning no HTML.') !!}
            <p class="small float-right">Maximumn of 250 characters.</p>
            {!! Form::textarea('text', Auth::user()->staffProfile ? Auth::user()->staffProfile->text : null, ['class' => 'form-control', 'maxlength' => 250]) !!}
        </div>
        <div class="text-right">
            {!! Form::submit('Edit', ['class' => 'btn btn-primary']) !!}
        </div>
    {!! Form::close() !!}
</div>