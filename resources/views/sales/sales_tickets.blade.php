@extends('layouts.app')

@section('title') Sale Raffle Tickets @endsection

@section('content')
{!! breadcrumbs(['Site Sales' => 'sales', $character->sales->title => $character->sales->url, 'Tickets: '. $character->character->slug => ""]) !!}

<h1>Raffle Tickets: <a href="{{ $character->character->url }}">{!! $character->character->slug !!}</a></h1>
@if($character->is_open == 1 && $character->sales->is_open)
    <div class="alert alert-success">This {{ $character->displayType}} is currently open.</div>
@else
    <div class="alert alert-danger">This {{ $character->displayType}} is closed. </div>
    <div class="card mb-3">
        <div class="card-header h3">Winner</div>
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead><th class="col-xs-1 text-center" style="width: 100px;">#</th><th>User</th></thead>
                <tbody>
                    @foreach($character->tickets()->winners()->get() as $winner)
                        <tr>
                            <td class="text-center">{{ $winner->position }}</td>
                            <td class="text-left">{!! $winner->displayHolderName !!}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

@if(Auth::check() && count($tickets))
  <?php $chance = number_format((float)(($userCount/$count)*100), 1, '.', ''); //Change 1 to 0 if you want no decimal place. ?>
  <p class="text-center mb-0">You {{ $character->is_open == 0 ? 'had' : 'have' }} <strong>{{ $userCount }}</strong> out of <strong>{{ $count }} tickets</strong> in this raffle.</p>
  <p class="text-center"> That's a <strong>{{ $chance }}%</strong> chance! </p>
@endif

<div class="text-right">{!! $tickets->render() !!}</div>

  <div class="row ml-md-2">
    <div class="d-flex row flex-wrap col-12 mt-1 pt-1 px-0 ubt-bottom">
      <div class="col-2 col-md-1 font-weight-bold">#</div>
      <div class="col-10 col-md-11 font-weight-bold">User</div>
    </div>
        @foreach($tickets as $count=>$ticket)
          <div class="d-flex row flex-wrap col-12 mt-1 pt-1 px-0 ubt-top">
            <div class="col-2 col-md-1">
              {{ $page * 100 + $count + 1 }}
              @if (Auth::check() && $ticket->user_id && $ticket->user->name == Auth::user()->name)
                <i class="fas fa-ticket-alt ml-2"></i>
              @endif
            </div>
            <div class="col-10 col-md-11">{!! $ticket->displayHolderName !!}</div>
          </div>
        @endforeach
  </div>

<div class="text-right">{!! $tickets->render() !!}</div>

@endsection
