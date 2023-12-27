@extends('layouts.app')

@section('title') Raffles @endsection

@section('content')
{!! breadcrumbs(['Raffles' => 'raffles']) !!}
<h1>Raffles</h1>
<p>Click on the name of a raffle to view the tickets, and in the case of completed raffles, the winners. Raffles in a group with a title will be rolled consecutively starting from the top, and will not draw duplicate winners.</p>
<ul class="nav nav-tabs mb-3">
    <li class="nav-item"><a href="{{ url()->current() }}" class="nav-link {{ Request::get('view') ? '' : 'active' }}">Current Raffles</a></li>
    <li class="nav-item"><a href="{{ url()->current() }}?view=completed" class="nav-link {{ Request::get('view') == 'completed' ? 'active' : '' }}">Completed Raffles</a></li>
</ul>

@if(count($raffles))
    <?php $prevGroup = null; ?>
    <ul class="list-group mb-3">
    {!! $raffles->render() !!}

    @foreach($raffles as $raffle)

        @if($prevGroup != $raffle->group_id)
            </ul>
            @if($prevGroup)
                </div>
            @endif
            <div class="card mb-3">
                <div class="card-header h3">{{ $groups[$raffle->group_id]->name }}</div>
                <ul class="list-group list-group-flush">
        @endif

        <li class="list-group-item">
            <div class="card">
                <div class="card-header"><h5 class="row m-0"><a class="col-lg-10 col-12 p-0" href="{{ url('raffles/view/'.$raffle->id) }}">{{ $raffle->name }}</a><a class="col-lg-2 col-12 btn btn-sm bg-light border ml-auto mr-0" href="{{ url('raffles/view/'.$raffle->id) }}"><i class="fas fa-ticket-alt"></i> Tickets</a></h5></div>
                @if($raffle->parsed_description || $raffle->rewards->count() >= 1)
                <div class="card-body">
                    @if($raffle->parsed_description)
                        {!! $raffle->parsed_description !!} 
                        <hr>
                    @endif
                    @if($raffle->rewards->count() >= 1)
                        A total of {{ $raffle->winner_count}} winner(s) will receive the following rewards:
                        <div class="row justify-content-center">
                        @foreach($raffle->rewards as $reward)
                            <div class="col-lg-4 col-12 mt-3">
                                @if($reward->rewardImage)<div class="row justify-content-center"><img class="border rounded" src="{{ $reward->rewardImage }}" alt="{{ $reward->reward()->first()->name }}" style="max-width:100%;" /></div>@endif
                                <div class="row justify-content-center"><span class="mr-1">{{ $reward->quantity }}</span> {!! $reward->reward()->first()->displayName !!}</div>
                            </div>
                        @endforeach
                        </div>
                    @endif
                    @if($raffle->has_join_button && !$raffle->rolled_at)
                        @if(Auth::user())
                        <a href="/raffles/join/{{$raffle->id}}" class="btn btn-primary float-right @if($raffle->tickets()->where('user_id', Auth::user()->id)->count() >= 1) disabled @endif">Join Raffle</a>
                        @else
                        <div class="float-right"><i>You must be logged in to join the raffle.</i></div>
                        @endif
                    @endif
                </div>
                @endif
            </div>
        </li>
        <?php $prevGroup = $raffle->group_id; ?>
    @endforeach
    {!! $raffles->render() !!}

@else 
    <p>No raffles found.</p>
@endif
@endsection
@section('scripts')
@parent
@endsection