<?php namespace App\Services;

use App\Services\Service;

use DB;
use Config;

use App\Models\Character\CharacterTitle;
use App\Models\Rarity;
use App\Models\Character\Character;
use App\Models\Character\CharacterImage;

class CharacterTitleService extends Service
{
    /*
    |--------------------------------------------------------------------------
    | Title Service
    |--------------------------------------------------------------------------
    |
    | Handles the creation and editing of character titles.
    |
    */

    /**
     * Creates a new title.
     *
     * @param  array                  $data
     * @param  \App\Models\User\User  $user
     * @return bool|\App\Models\Title
     */
    public function createTitle($data, $user)
    {
        DB::beginTransaction();

        try {
            $data = $this->populateData($data);

            $image = null;
            if(isset($data['image']) && $data['image']) {
                $data['has_image'] = 1;
                $image = $data['image'];
                unset($data['image']);
            }
            else $data['has_image'] = 0;

            $title = CharacterTitle::create($data);

            if ($image) $this->handleImage($image, $title->titleImagePath, $title->titleImageFileName);

            return $this->commitReturn($title);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Updates a title.
     *
     * @param  \App\Models\Title     $title
     * @param  array                  $data
     * @param  \App\Models\User\User  $user
     * @return bool|\App\Models\Title
     */
    public function updateTitle($title, $data, $user)
    {
        DB::beginTransaction();

        try {
            // More specific validation
            if(CharacterTitle::where('title', $data['title'])->where('id', '!=', $title->id)->exists()) throw new \Exception("The title has already been taken.");
            if(CharacterTitle::where('short_title', $data['short_title'])->where('id', '!=', $title->id)->exists()) throw new \Exception("The short title has already been taken.");

            $data = $this->populateData($data, $title);

            $image = null;
            if(isset($data['image']) && $data['image']) {
                $data['has_image'] = 1;
                $image = $data['image'];
                unset($data['image']);
            }

            $title->update($data);

            if ($title) $this->handleImage($image, $title->titleImagePath, $title->titleImageFileName);

            return $this->commitReturn($title);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Processes user input for creating/updating a title.
     *
     * @param  array               $data
     * @param  \App\Models\Title  $title
     * @return array
     */
    private function populateData($data, $title = null)
    {
        if(isset($data['description']) && $data['description']) $data['parsed_description'] = parse($data['description']);
        else $data['parsed_description'] = null;

        if(isset($data['remove_image']))
        {
            if($title && $title->has_image && $data['remove_image'])
            {
                $data['has_image'] = 0;
                $this->deleteImage($title->titleImagePath, $title->titleImageFileName);
            }
            unset($data['remove_image']);
        }
        if(!isset($data['is_active'])) $data['is_active'] = 0;
        if(!isset($data['is_user_selectable'])) $data['is_user_selectable'] = 0;
        if(isset($data['item_id']) && $data['item_id'] == 'none') $data['item_id'] = null;

        return $data;
    }

    /**
     * Deletes a title.
     *
     * @param  \App\Models\Title  $title
     * @return bool
     */
    public function deleteTitle($title)
    {
        DB::beginTransaction();

        try {
            // Check first if characters with this title exist
            if(CharacterImage::where('title_id', $title->id)->exists() || Character::where('title_id', $title->id)->exists()) throw new \Exception("A character or character image with this title exists. Please change its title first.");

            // if the title should be deleted also delete the corresponding usertitles
            if($title->usertitles->count() > 0) $title->usertitles->delete();

            if($title->has_image) $this->deleteImage($title->titleImagePath, $title->titleImageFileName);
            $title->delete();

            return $this->commitReturn(true);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Sorts title order.
     *
     * @param  array  $data
     * @return bool
     */
    public function sortTitle($data)
    {
        DB::beginTransaction();

        try {
            // explode the sort array and reverse it since the order is inverted
            $sort = array_reverse(explode(',', $data));

            foreach($sort as $key => $s) {
                CharacterTitle::where('id', $s)->update(['sort' => $key]);
            }

            return $this->commitReturn(true);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }
}
