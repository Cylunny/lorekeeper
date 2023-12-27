@if(!$raffle->id)
    <p>
        Enter basic information about this raffle. Tickets can be added after the raffle is created.
    </p>
@endif
{!! Form::open(['url' => 'admin/raffles/edit/raffle/'.($raffle->id ? : '')]) !!}
    <div class="form-group">
        {!! Form::label('name', 'Raffle Name') !!} {!! add_help('This is the name of the raffle. Naming it something after what is being raffled is suggested (does not have to be unique).') !!}
        {!! Form::text('name', $raffle->name, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
    {!! Form::label('Description (Optional)') !!} {!! add_help('This is a full description of the raffle that shows up on the raffle page.') !!}
    {!! Form::textarea('description', $raffle->description, ['class' => 'form-control wysiwyg']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('winner_count', 'Number of Winners to Draw') !!}
        {!! Form::text('winner_count', $raffle->winner_count, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('group_id', 'Raffle Group') !!} {!! add_help('Raffle groups must be created before you can select them here.') !!}
        {!! Form::select('group_id', $groups, $raffle->group_id, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('order', 'Raffle Order') !!} {!! add_help('Enter a number. If a group of raffles is rolled, raffles will be drawn in ascending order.') !!}
        {!! Form::text('order', $raffle->order ? : 0, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group row">
        <label class="control-label col-6">
            {!! Form::checkbox('is_active', 1, $raffle->is_active, ['class' => 'form-check-input mr-2', 'data-toggle' => 'toggle']) !!}
            {!! Form::label('is_displayed', 'Active (visible to users)', ['class' => 'form-check-label ml-3']) !!}
        </label>
        <label class="control-label col-6">
            {!! Form::checkbox('has_join_button', 1, $raffle->has_join_button, ['class' => 'form-check-input mr-2', 'data-toggle' => 'toggle']) !!} 
            {!! Form::label('has_join_button', 'Add join button', ['class' => 'form-check-label ml-3']) !!} {!! add_help('Adds a button that automatically grants each user a ticket to this raffle once.') !!}
        </label>
    </div>
    <h3>Rewards (Optional)</h3>
    <p>All rewards are credited to the user(s) that is/are rolled as the winner(s) of the raffle. Keep in mind that characters can only be owned by one user, so character raffles should only have one winner (otherwise it ends up with the last winner drawn).</p>
    <p>You can add loot tables containing any kind of currencies (both user- and character-attached), but be sure to keep track of which are being distributed! Character-only currencies cannot be given to users.</p>
    @include('widgets._loot_select', ['loots' => $raffle->rewards, 'showLootTables' => true, 'showRaffles' => true])

    <div class="text-right">
        {!! Form::submit('Confirm', ['class' => 'btn btn-primary']) !!}
    </div>
    {!! Form::close() !!}

    @include('widgets._loot_select_row', ['items' => $items, 'currencies' => $currencies, 'tables' => $tables, 'raffles' => $raffles, 'showLootTables' => true, 'showRaffles' => true])


{!! Form::close() !!}

@include('js._loot_js', ['showLootTables' => true, 'showRaffles' => true])
