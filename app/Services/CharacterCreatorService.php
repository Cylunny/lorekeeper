<?php

namespace App\Services;

use App\Services\Service;

use DB;
use Config;

use App\Models\User\User;
use App\Models\CharacterCreator\CharacterCreator;
use App\Models\CharacterCreator\LayerGroup;
use App\Models\CharacterCreator\LayerOption;
use App\Models\CharacterCreator\Layer;

class CharacterCreatorService extends Service
{
    /*
    |--------------------------------------------------------------------------
    | CharacterCreatorService 
    |--------------------------------------------------------------------------
    |
    | Handles the creation and editing of character creators.
    |
    */

    /*****************************************************************
     *  
     * CHARACTER CREATORS
     * 
     ******************************************************************/

    /**
     * Creates a creator.
     * @param  array                  $data
     * @return bool|\App\Models\CharacterCreator\CharacterCreator
     */
    public function createCharacterCreator($data)
    {
        DB::beginTransaction();

        try {
            if(isset($data['item_id']) && $data['currency_id']) throw new \Exception ("You can only set either an item or currency cost!");
            $data['parsed_description'] = parse($data['description']);
            if (!isset($data['is_visible'])) $data['is_visible'] = 0;

            $image = null;
            if (isset($data['image']) && $data['image']) {
                $image = $data['image'];
                unset($data['image']);
            }

            $creator = CharacterCreator::create($data);

            if ($image) {
                $creator->image_extension = $image->getClientOriginalExtension();
                $creator->update();
                $this->handleImage($image, $creator->imagePath, $creator->imageFileName, null);
            }

            return $this->commitReturn($creator);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Updates a creator.
     *
     * @param  \App\Models\CharacterCreator\CharacterCreator       $creator
     * @param  array                  $data 
     * @return bool|\App\Models\CharacterCreator\CharacterCreator
     */
    public function updateCharacterCreator($creator, $data)
    {
        DB::beginTransaction();

        try {
            if(isset($data['item_id']) && $data['currency_id']) throw new \Exception ("You can only set either an item or currency cost!");
            $data['parsed_description'] = parse($data['description']);
            if (!isset($data['is_visible'])) $data['is_visible'] = 0;

            $image = null;
            if (isset($data['image']) && $data['image']) {
                if (isset($creator->image_extension)) $old = $creator->imageFileName;
                else $old = null;
                $image = $data['image'];
                unset($data['image']);
            }
            if ($image) {
                $creator->image_extension = $image->getClientOriginalExtension();
                $creator->update();
                $this->handleImage($image, $creator->imagePath, $creator->imageFileName, $old);
            }

            if (isset($data['remove_image'])) {
                if ($creator && isset($creator->image_extension) && $data['remove_image']) {
                    $data['image_extension'] = null;
                    $this->deleteImage($creator->imagePath, $creator->imageFileName);
                }
                unset($data['remove_image']);
            }

            $creator->update($data);

            return $this->commitReturn($creator);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Deletes a creator.
     *
     * @param  \App\Models\CharacterCreator\CharacterCreator  $creator
     * @return bool
     */
    public function deleteCharacterCreator($creator)
    {
        DB::beginTransaction();

        try {
            $creator->delete();

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Sorts layergroup order.
     *
     * @param  array  $data
     * @return bool
     */
    public function sortLayerGroup($data)
    {
        DB::beginTransaction();

        try {
            // explode the sort array and reverse it since the order is inverted
            $sort = array_reverse(explode(',', $data));

            foreach($sort as $key => $s) {
                LayerGroup::where('id', $s)->update(['sort' => $key]);
            }

            return $this->commitReturn(true);
        } catch(\Exception $e) { 
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /*****************************************************************
     *  
     * LAYER GROUPS
     * 
     ******************************************************************/

    /**
     * Creates a layergroup.
     *
     * @param  array $data
     * @return bool|\App\Models\CharacterCreator\LayerGroup
     */
    public function createLayerGroup($data, $creatorId)
    {
        DB::beginTransaction();

        try {
            $data['parsed_description'] = parse($data['description']);
            $data['character_creator_id'] = $creatorId;
            $group = LayerGroup::create($data);
            return $this->commitReturn($group);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Updates a layergroup.
     *
     * @param  \App\Models\CharacterCreator\LayerGroup $group
     * @param  array                  $data 
     * @return bool|\App\Models\CharacterCreator\LayerGroup
     */
    public function updateLayerGroup($group, $data)
    {
        DB::beginTransaction();

        try {
            $data['parsed_description'] = parse($data['description']);
            $group->update($data);
            return $this->commitReturn($group);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

        /**
     * Deletes a creator.
     *
     * @param  \App\Models\CharacterCreato\CharacterCreator\LayerGroup  $group
     * @return bool
     */
    public function deleteLayerGroup($group)
    {
        DB::beginTransaction();

        try {
            $group->delete();
            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }
}
