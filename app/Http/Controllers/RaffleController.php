<?php namespace App\Http\Controllers;

use Auth;
use Request; 
use App\Models\Raffle\RaffleGroup;
use App\Models\Raffle\Raffle;
use App\Models\Raffle\RaffleTicket;
use App\Services\RaffleManager;

class RaffleController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Raffle Controller
    |--------------------------------------------------------------------------
    |
    | Displays raffles and raffle tickets.
    |
    */

    /**
     * Shows the raffle index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getRaffleIndex()
    {
        $raffles = Raffle::query();
        if (Request::get('view') == 'completed') $raffles->where('is_active', 2);
        else $raffles->where('is_active', '=', 1);
        $raffles = $raffles->orderBy('group_id')->orderBy('order')->orderBy('id', 'DESC');

        return view('raffles.index', [
            'raffles' => $raffles->paginate(10)->withQueryString(),
            'groups' => RaffleGroup::whereIn('id', $raffles->pluck('group_id')->toArray())->get()->keyBy('id')
        ]);
    }

    /**
     * Shows tickets for a given raffle.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getRaffleTickets($id)
    {
        $raffle = Raffle::find($id);
        if(!$raffle || !$raffle->is_active) abort(404);
        $userCount = Auth::check() ? $raffle->tickets()->where('user_id', Auth::user()->id)->count() : 0;
        $count = $raffle->tickets()->count();

        return view('raffles.ticket_index', [
            'raffle' => $raffle,
            'tickets' => $raffle->tickets()->with('user')->orderBy('id')->paginate(100),
            'count' => $count,
            'userCount' => $userCount, 
            "page" => Request::get('page') ? Request::get('page') - 1 : 0
        ]);
    }

    /**
     * Claims the one time ticket via join raffle.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getJoinRaffle($id, RaffleManager $service)
    {
        $raffle = Raffle::find($id);
        if(!$raffle || !$raffle->is_active) abort(404);
        $userCount = Auth::check() ? $raffle->tickets()->where('user_id', Auth::user()->id)->count() : 0;
        if($userCount >= 1){
            flash('You already own a raffle ticket for this raffle! You may only claim a free ticket if you do not own any tickets yet.')->error();
            return redirect()->back();
        }
        if ($service->addTicket(Auth::user(), Raffle::find($id))) {
            flash('You have received one free ticket for the raffle "'. $raffle->name .'"!')->success();
        }
        else {
            flash('There was an error while trying to receive your free ticket.')->error();
        }
        return redirect()->back();

    }
}
