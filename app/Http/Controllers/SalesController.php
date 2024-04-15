<?php

namespace App\Http\Controllers;

use Auth;

use App\Http\Controllers\Controller;
use App\Services\SalesManager;
use App\Models\Sales\Sales;
use App\Models\Sales\SalesCharacter;
use Exception;
use Request; 

class SalesController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | sales Controller
    |--------------------------------------------------------------------------
    |
    | Displays sales posts and updates the user's sales read status.
    |
    */

    /**
     * Shows the sales index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getIndex()
    {
        if(Auth::check() && Auth::user()->is_sales_unread) Auth::user()->update(['is_sales_unread' => 0]);
        return view('sales.index', ['saleses' => Sales::visible()->orderBy('id', 'DESC')->paginate(10)]);
    }

    /**
     * Shows a sales post.
     *
     * @param  int          $id
     * @param  string|null  $slug
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getSales($id, $slug = null)
    {
        $sales = Sales::where('id', $id)->where('is_visible', 1)->first();
        if(!$sales) abort(404);
        return view('sales.sales', [
            'sales' => $sales,
            'user' => Auth::user()
        ]);
    }

    /**
     * Post for entering a sale raffle.
     *
     * @param  int          $id
     * @param  string|null  $slug
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function postEnterSaleRaffle($id, SalesManager $service)
    {
        $saleCharacter = SalesCharacter::where('id', $id)->where('is_open', 1)->whereIn('type', ['raffle', 'flaffle'])->first();
        if(!$saleCharacter) throw new \Exception ("Character for sale could not be found.");
        if($saleCharacter->is_open == 0) throw new \Exception ("Character raffle has already ended.");

        $userCount = Auth::check() ? $saleCharacter->tickets()->where('user_id', Auth::user()->id)->count() : 0;
        if($userCount >= 1){
            flash('You already entered this raffle!')->error();
            return redirect()->back();
        }
        if ($service->addTicket(Auth::user(), $saleCharacter)) {
            flash('You entered the raffle for '.$saleCharacter->character->slug . ' successfully!')->success();
        }
        else {
            flash('There was an error while trying to receive your ticket.')->error();
        }

        return redirect()->back();
    }

    
    /**
     * Post for retracting a sale raffle entry.
     *
     * @param  int          $id
     * @param  string|null  $slug
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function postRetractSaleRaffle($id, SalesManager $service)
    {
        $saleCharacter = SalesCharacter::where('id', $id)->where('is_open', 1)->whereIn('type', ['raffle', 'flaffle'])->first();
        if(!$saleCharacter) throw new \Exception ("Character for sale could not be found.");
        if($saleCharacter->is_open == 0) throw new \Exception ("Character raffle has already ended.");

        $userCount = Auth::check() ? $saleCharacter->tickets()->where('user_id', Auth::user()->id)->count() : 0;
        if($userCount <= 0){
            flash('You have not entered this raffle!')->error();
            return redirect()->back();
        }
        $ticket = $saleCharacter->tickets()->where('user_id', Auth::user()->id)->first();
        if ($service->removeTicket($ticket)) {
            flash('You withdrew from the raffle for '.$saleCharacter->character->slug . ' successfully!')->success();
        }
        else {
            flash('There was an error while trying to withdraw your ticket.')->error();
        }

        return redirect()->back();
    }

    /**
     * View tickets of a sale raffle.
     *
     * @param  int          $id
     * @param  string|null  $slug
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getSaleRaffleTickets($id)
    {
        $saleCharacter = SalesCharacter::where('id', $id)->where('is_open', 1)->whereIn('type', ['raffle', 'flaffle'])->first();
        if(!$saleCharacter) throw new \Exception ("Character for sale could not be found.");

        return view('sales.sales_tickets', [
            'character' => $saleCharacter,
            'tickets' => $saleCharacter->tickets()->with('user')->orderBy('id')->paginate(100),
            'count' => $saleCharacter->tickets()->count(),
            'userCount' =>  Auth::check() ? $saleCharacter->tickets()->where('user_id', Auth::user()->id)->count() : 0,
            'page' => Request::get('page') ? Request::get('page') - 1 : 0
        ]);
    }
    
}
