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
}
