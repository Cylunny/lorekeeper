<?php namespace App\Services;

use App\Services\Service;

use DB;
use Config;
use Validator;

use App\Models\User\User;
use App\Models\Sales\Sales;
use App\Models\Sales\SalesCharacter;
use App\Models\Character\Character;

class SalesService extends Service
{
    /*
    |--------------------------------------------------------------------------
    | Sales Service
    |--------------------------------------------------------------------------
    |
    | Handles the creation and editing of Sales posts.
    |
    */

    /**
     * Creates a Sales post.
     *
     * @param  array                  $data
     * @param  \App\Models\User\User  $user
     * @return bool|\App\Models\Sales\Sales
     */
    public function createSales($data, $user)
    {
        DB::beginTransaction();

        try {
            $data['parsed_text'] = parse($data['text']);
            $data['user_id'] = $user->id;
            if(!isset($data['is_visible'])) $data['is_visible'] = 0;
            if(!isset($data['is_open'])) $data['is_open'] = 0;

            $sales = Sales::create($data);

            // The character identification comes in both the slug field and as character IDs
            // First, check if the characters are accessible to begin with.
            if(isset($data['slug'])) {
                $characters = Character::myo(0)->visible()->whereIn('slug', $data['slug'])->get();
                if(count($characters) != count($data['slug'])) throw new \Exception("One or more of the selected characters do not exist.");
            }
            else $characters = [];

            // Process entered character data
            if(isset($data['slug'])) $this->processCharacters($sales, $data);

            if($sales->is_visible) $this->alertUsers();

            return $this->commitReturn($sales);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Updates a Sales post.
     *
     * @param  \App\Models\Sales\Sales       $Sales
     * @param  array                  $data
     * @param  \App\Models\User\User  $user
     * @return bool|\App\Models\Sales\Sales
     */
    public function updateSales($sales, $data, $user)
    {
        DB::beginTransaction();

        try {
            $data['parsed_text'] = parse($data['text']);
            $data['user_id'] = $user->id;
            if(!isset($data['is_visible'])) $data['is_visible'] = 0;
            if(!isset($data['is_open'])) $data['is_open'] = 0;

            if(isset($data['bump']) && $data['is_visible'] == 1 && $data['bump'] == 1) $this->alertUsers();

            // The character identification comes in both the slug field and as character IDs
            // First, check if the characters are accessible to begin with.
            if(isset($data['slug'])){
                $characters = Character::myo(0)->visible()->whereIn('slug', $data['slug'])->get();
                if(count($characters) != count($data['slug'])) throw new \Exception("One or more of the selected characters do not exist.");
                $this->processCharacters($sales, $data);
                // Remove existing attached characters, whose slug is not in the data
                $sales->characters()->whereNotIn('character_id', $characters->pluck('id'))->delete();
            } else {
                // no slug set = remove all sales characters
                $sales->characters()->delete();
            }

            $sales->update($data);

            return $this->commitReturn($sales);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Processes sales data entered for characters.
     *
     * @param  App\Models\Sales\Sales                   $sales
     * @param  array                                    $data
     * @return bool
     */
    private function processCharacters($sales, $data)
    {
        foreach($data['slug'] as $key=>$slug) {

            $character = Character::myo(0)->visible()->where('slug', $slug)->first();
            $salesCharacter = $sales->characters()->where('character_id', $character->id)->first();

            // Assemble data
            $charData[$key] = [];
            $charData[$key]['type'] = $data['sale_type'][$key];
            switch($charData[$key]['type']) {
                case 'flatsale':
                    $charData[$key]['price'] = $data['price'][$key];
                    break;
                case 'auction':
                    $charData[$key]['starting_bid'] = $data['starting_bid'][$key];
                    $charData[$key]['min_increment'] = $data['min_increment'][$key];
                    if(isset($data['autobuy'][$key])) $charData[$key]['autobuy'] = $data['autobuy'][$key];
                    if(isset($data['end_point'][$key])) $charData[$key]['end_point'] = $data['end_point'][$key];
                    break;
                case 'ota':
                    if(isset($data['autobuy'][$key])) $charData[$key]['autobuy'] = $data['autobuy'][$key];
                    if(isset($data['end_point'][$key])) $charData[$key]['end_point'] = $data['end_point'][$key];
					if(isset($data['minimum'][$key])) $charData[$key]['minimum'] = $data['minimum'][$key];
                    break;
                case 'xta':
                    if(isset($data['autobuy'][$key])) $charData[$key]['autobuy'] = $data['autobuy'][$key];
                    if(isset($data['end_point'][$key])) $charData[$key]['end_point'] = $data['end_point'][$key];
					if(isset($data['minimum'][$key])) $charData[$key]['minimum'] = $data['minimum'][$key];
                    break;
                case 'flaffle':
                    $charData[$key]['price'] = $data['price'][$key];
                    break;
                case 'pwyw':
                    if(isset($data['minimum'][$key])) $charData[$key]['minimum'] = $data['minimum'][$key];
                    break;
            }

            // Validate data
            $validator = Validator::make($charData[$key], SalesCharacter::$rules);
            if($validator->fails()) throw new \Exception($validator->errors()->first());

            if(isset($salesCharacter)){
                // update existing salescharacter
                $salesCharacter->update([
                    'character_id' => $character->id,
                    'sales_id' => $sales->id,
                    'type' => $charData[$key]['type'],
                    'data' => json_encode($charData[$key]),
                    'description' => isset($data['description'][$key]) ? $data['description'][$key] : null,
                    'link' => isset($data['link'][$key]) ? $data['link'][$key] : null,
                    'is_open' => isset($data['character_is_open'][$character->slug]) ? $data['character_is_open'][$character->slug] : ($data['new_entry'][$key] ? 1 : 0)
                ]);
            } else {
                // create new salescharacter
                SalesCharacter::create([
                    'character_id' => $character->id,
                    'sales_id' => $sales->id,
                    'type' => $charData[$key]['type'],
                    'data' => json_encode($charData[$key]),
                    'description' => isset($data['description'][$key]) ? $data['description'][$key] : null,
                    'link' => isset($data['link'][$key]) ? $data['link'][$key] : null,
                    'is_open' => isset($data['character_is_open'][$character->slug]) ? $data['character_is_open'][$character->slug] : ($data['new_entry'][$key] ? 1 : 0)
                ]);
            }


        }
    }

    /**
     * Deletes a Sales post.
     *
     * @param  \App\Models\Sales\Sales  $Sales
     * @return bool
     */
    public function deleteSales($sales)
    {
        DB::beginTransaction();

        try {
            $sales->delete();

            return $this->commitReturn(true);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Updates queued Sales posts to be visible and alert users when
     * they should be posted.
     *
     * @return bool
     */
    public function updateQueue()
    {
        $count = Sales::shouldBeVisible()->count();
        if($count) {
            DB::beginTransaction();

            try {
                Sales::shouldBeVisible()->update(['is_visible' => 1]);
                $this->alertUsers();

                return $this->commitReturn(true);
            } catch(\Exception $e) {
                $this->setError('error', $e->getMessage());
            }
            return $this->rollbackReturn(false);
        }
    }

    /**
     * Updates the unread Sales flag for all users so that
     * the new Sales notification is displayed.
     *
     * @return bool
     */
    private function alertUsers()
    {
        User::query()->update(['is_sales_unread' => 1]);
        return true;
    }

    /**
     * Rolls a sale consecutively. Each user may only win once.
     */
    public function rollSales($sale)
    {
        if(!$sale) return null;
        DB::beginTransaction();

        try {
            foreach($sale->characters()->whereIn('type', ['raffle', 'flaffle'])->get() as $salesCharacter)
            {
                $winners = $this->rollWinners($salesCharacter);
                // mark raffle as finished
                $salesCharacter->is_open = 0;
                $salesCharacter->save();

                // remove any tickets from winners in raffles in the group that aren't completed
                $saleCharacters = $sale->characters()->where('is_open', '!=', 0)->where('id', '!=', $salesCharacter->id)->get();
                foreach($saleCharacters as $r)
                {
                    $r->tickets()->where(function($query) use ($winners) { 
                        $query->whereIn('user_id', $winners); 
                    })->delete();
                }
            }
            $sale->is_open = 0;
            $sale->save();

            return $this->commitReturn(true);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
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
        $winners = [];
        for ($i = 0; $i < 1; $i++)
        {
            if($ticketCount == 0) break;

            $num = mt_rand(0, $ticketCount - 1);
            $winner = $ticketPool[$num];

            // save ticket as winning ticket
            $winner->update(['winner' => 1]);

            // save the winning ticket's user id
            if(isset($winner->user_id)) $winners[] = $winner->user_id;

            // remove ticket from the ticket pool after pulled
            $ticketPool->forget($num);
            $ticketPool = $ticketPool->values();

            $ticketCount--;

            // remove tickets for the same user...I'm unsure how this is going to hold up with 3000 tickets,
            foreach($ticketPool as $key=>$ticket)
            {
                if(($ticket->user_id != null && $ticket->user_id == $winner->user_id)) 
                {
                    $ticketPool->forget($key);
                }

            }
            $ticketPool = $ticketPool->values();
            $ticketCount = $ticketPool->count();
        }
        return $winners;
    }

}
