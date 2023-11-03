<?php namespace App\Services;

use App\Services\Service;

use Carbon\Carbon;

use DB;
use Config;

use App\Models\User\UserTitle;
use App\Models\Character\CharacterTitle;


class CharacterTitleManager extends Service
{
    /*
    |--------------------------------------------------------------------------
    | Character Title Manager
    |--------------------------------------------------------------------------
    |
    | Handles granting titles to users.
    |
    */

        /**
     * Credits title to a user or character.
     *
     * @param  \App\Models\User\User                        $sender
     * @param  \App\Models\User\User                        $recipient
     * @param  \App\Models\Character\Character              $character
     * @param  string                                       $type 
     * @param  string                                       $data
     * @param  \App\Models\Title                            $title
     * @param  int                                          $quantity
     * @return  bool
     */
    public function creditTitle($recipient, $title) {
        DB::beginTransaction();

        try {
            if (is_numeric($title)) $title = CharacterTitle::find($title);

            if ($recipient->titles->contains($title)) {
                flash($recipient->name . " already has the title " . $title->displayName, 'warning');
                return $this->commitReturn(false);
            }

            $record = UserTitle::where('user_id', $recipient->id)->where('title_id', $title->id)->first();
            if ($record) {
                // Laravel doesn't support composite primary keys, so directly updating the DB row here
                DB::table('user_titles')->where('user_id', $recipient->id)->where('title_id', $title->id);
            } else {
                $record = UserTitle::create(['user_id' => $recipient->id, 'title_id' => $title->id]);
            }

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }


    
}
