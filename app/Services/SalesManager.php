<?php namespace App\Services;

use DB;
use Carbon\Carbon;
use App\Services\Service;
use App\Models\Sales\SalesCharacter;
use App\Models\Sales\SaleRaffleTicket;
use App\Models\User\User;

class SalesManager extends Service 
{
    /*
    |--------------------------------------------------------------------------
    | Sales Manager
    |--------------------------------------------------------------------------
    |
    | Handles creation and modification of sale ticket data.
    |
    */

    /**
     * Adds tickets to a raffle sale. 
     * One ticket is added per name in $names, which is a
     * string containing comma-separated names.
     *
     * @param  \App\Models\Sales\Sales $raffle
     * @param  string                    $names
     * @return int
     */
    public function addTickets($characterSale, $names)
    {
        $names = explode(',', $names);
        $count = 0;
        foreach($names as $name)
        {
            $name = trim($name);
            if(strlen($name) == 0) continue;
            if ($user = User::where('name', $name)->first()) $count += $this->addTicket($user, $characterSale);
        }
        return $count;
    }

    /**
     * Adds one or more tickets to a single user for a raffle.
     *
     * @param  \App\Models\User\User     $user
     * @param  \App\Models\Sales\Sales $raffle
     * @param  int                       $count
     * @return int
     */
    public function addTicket($user, $characterSale)
    {
        if (!$user) return 0;
        else if (!$characterSale) return 0;
        else if ($characterSale->is_open == 0) return 0;
        else {
            DB::beginTransaction();
            $data = ["sale_character_id" => $characterSale->id, 'created_at' => Carbon::now(), 'user_id' => $user->id];
            SaleRaffleTicket::create($data);
            DB::commit();
            return 1;
        }
        return 0;
    }

    /**
     * Removes a single ticket.
     *
     * @param  \App\Models\Sales\SalesTicket $ticket
     * @return bool
     */
    public function removeTicket($ticket)
    {
        if (!$ticket) return null;
        else {
            $ticket->delete();
            return true;
        }
        return false;
    }

    /**
     * Rolls a sale consecutively.
     * If the $updateGroup flag is true, winners will be removed
     * from other raffles in the group.
     *
     * @param  \App\Models\Sales\SalesGroup $raffleGroup
     * @param  bool                           $updateGroup
     * @return bool
     */
    public function rollSalesRaffle($sale)
    {
        if(!$sale) return null;
        DB::beginTransaction();
        foreach($sale->characters()->orderBy('order')->get() as $salesCharacter)
        {
            if (!$this->rollSales($salesCharacter)) 
            {
                DB::rollback();
                return false;
            }
        }
        $sale->is_open = 0;
        $sale->save();
        DB::commit();
        return true;
    }

    /**
     * Rolls a single raffle and marks it as completed.
     * If the $updateGroup flag is true, winners will be removed
     * from other raffles in the group.
     *
     * @param  \App\Models\Sales\Sales $raffle
     * @param  bool                      $updateGroup
     * @return bool
     */
    public function rollSales($salesCharacter) 
    {
        if(!$salesCharacter) return null;
        DB::beginTransaction();
        // roll winners
        if($winners = $this->rollWinners($salesCharacter))
        {
            // mark raffle as finished
            $salesCharacter->is_open = 0;
            $salesCharacter->save();

            // updates the sale if necessary
            if(!$this->afterRoll($winners, $salesCharacter->sale, $salesCharacter))
            {
                DB::rollback();
                return false;
            }
            DB::commit();
            return true;
        }
        DB::rollback();
        return false;
    }

    /**
     * Rolls the winners of a raffle.
     *
     * @param  \App\Models\Sales\Sales $raffle
     * @return array
     */
    private function rollWinners($salesCharacter)
    {
        $ticketPool = $salesCharacter->tickets;
        $ticketCount = $ticketPool->count();
        $winners = ['ids' => []];
        for ($i = 0; $i < 1; $i++)
        {
            if($ticketCount == 0) break;

            $num = mt_rand(0, $ticketCount - 1);
            $winner = $ticketPool[$num];

            // save ticket position as ($i + 1)
            $winner->update(['position' => $i + 1]);

            // save the winning ticket's user id
            if(isset($winner->user_id)) $winners['ids'][] = $winner->user_id;

            // remove ticket from the ticket pool after pulled
            $ticketPool->forget($num);
            $ticketPool = $ticketPool->values();

            $ticketCount--;

            // remove tickets for the same user...I'm unsure how this is going to hold up with 3000 tickets,
            foreach($ticketPool as $key=>$ticket)
            {
                if(($ticket->user_id != null && $ticket->user_id == $winner->user_id) || ($ticket->user_id == null && $ticket->alias == $winner->alias)) 
                {
                    $ticketPool->forget($key);
                }

            }
            $ticketPool = $ticketPool->values();
            $ticketCount = $ticketPool->count();
        }
        return $winners;
    }

    /**
     * Rolls the winners of a raffle.
     *
     * @param  array                          $winners
     * @param  \App\Models\Sales\SalesGroup $raffleGroup
     * @param  \App\Models\Sales\Sales      $raffle
     * @return bool
     */
    private function afterRoll($winners, $sale, $salesCharacter)
    {
        // remove any tickets from winners in raffles in the group that aren't completed
        $saleCharacters = $sale->characters()->where('is_open', '!=', 0)->where('id', '!=', $salesCharacter->id)->get();
        foreach($saleCharacters as $r)
        {
            $r->tickets()->where(function($query) use ($winners) { 
                $query->whereIn('user_id', $winners['ids']); 
            })->delete();
        }
        return true;
    }


}
