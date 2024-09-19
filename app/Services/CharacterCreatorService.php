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
            if (isset($data['item_id']) && $data['currency_id']) throw new \Exception("You can only set either an item or currency cost!");
            $data['parsed_description'] = parse($data['description']);
            if (!isset($data['is_visible'])) $data['is_visible'] = 0;
            if (!isset($data['allow_character_creation'])) $data['allow_character_creation'] = 0;

            $image = null;
            if (isset($data['image']) && $data['image']) {
                $image = $data['image'];
                unset($data['image']);
            }

            $creator = CharacterCreator::create($data);

            if ($image) {
                $creator->image_extension = uniqid('', true) .'.' . $image->getClientOriginalExtension();
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
            if (isset($data['item_id']) && $data['currency_id']) throw new \Exception("You can only set either an item or currency cost!");
            $data['parsed_description'] = parse($data['description']);
            if (!isset($data['is_visible'])) $data['is_visible'] = 0;
            if (!isset($data['allow_character_creation'])) $data['allow_character_creation'] = 0;

            $image = null;
            if (isset($data['image']) && $data['image']) {
                if (isset($creator->image_extension)) $old = $creator->imageFileName;
                else $old = null;
                $image = $data['image'];
                unset($data['image']);
            }
            if ($image) {
                $creator->image_extension = uniqid('', true) .'.' . $image->getClientOriginalExtension();
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

            foreach ($creator->layerGroups as $group) {
                $this->deleteLayerGroup($group);
            }
            $creator->delete();
            return $this->commitReturn(true);
        } catch (\Exception $e) {
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
            if (!isset($data['is_mandatory'])) $data['is_mandatory'] = 0;

            //set sort to next highest or else we may run intro trouble later if sort 0 is given twice
            $creator = CharacterCreator::find($creatorId);
            $data['sort'] = $creator->layerGroups()->count();

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
            if (!isset($data['is_mandatory'])) $data['is_mandatory'] = 0;
            $group->update($data);
            return $this->commitReturn($group);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Deletes a layergroup.
     *
     * @param  \App\Models\CharacterCreato\CharacterCreator\LayerGroup  $group
     * @return bool
     */
    public function deleteLayerGroup($group)
    {
        DB::beginTransaction();

        try {
            foreach ($group->layerOptions as $option) {
                $this->deleteLayerOption($option);
            }
            $group->delete();
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

            foreach ($sort as $key => $s) {
                LayerGroup::where('id', $s)->update(['sort' => $key]);
            }

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /*****************************************************************
     *  
     * LAYER OPTIONS
     * 
     ******************************************************************/

    /**
     * Creates a layeroption.
     *
     * @param  array $data
     * @return bool|\App\Models\CharacterCreator\LayerOption
     */
    public function createLayerOption($data, $groupId)
    {
        DB::beginTransaction();

        try {
            $data['parsed_description'] = parse($data['description']);
            $data['layer_group_id'] = $groupId;

            //set sort to next highest or else we may run intro trouble later if sort 0 is given twice
            $group = LayerGroup::find($groupId);
            $data['sort'] = $group->layerOptions()->count();

            $option = LayerOption::create($data);
            return $this->commitReturn($option);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Updates a layeroption.
     *
     * @param  \App\Models\CharacterCreator\LayerOption $option
     * @param  array                  $data 
     * @return bool|\App\Models\CharacterCreator\LayerOption
     */
    public function updateLayerOption($option, $data)
    {
        DB::beginTransaction();

        try {
            $data['parsed_description'] = parse($data['description']);
            $option->update($data);
            return $this->commitReturn($option);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Deletes a layeroption.
     *
     * @param  \App\Models\CharacterCreato\CharacterCreator\LayerOption  $option
     * @return bool
     */
    public function deleteLayerOption($option)
    {
        DB::beginTransaction();

        try {
            foreach ($option->layers as $layer) {
                $this->deleteLayer($layer);
            }
            $option->delete();
            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Sorts layeroption order.
     *
     * @param  array  $data
     * @return bool
     */
    public function sortLayerOption($data)
    {
        DB::beginTransaction();

        try {
            // explode the sort array and reverse it since the order is inverted
            $sort = array_reverse(explode(',', $data));

            foreach ($sort as $key => $s) {
                LayerOption::where('id', $s)->update(['sort' => $key]);
            }

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /*****************************************************************
     *  
     * LAYERS
     * 
     ******************************************************************/

    /**
     * Creates a layer.
     *
     * @param  array $data
     * @return bool|\App\Models\CharacterCreator\Layer
     */
    public function createLayer($data, $optionId)
    {
        DB::beginTransaction();

        try {
            $data['layer_option_id'] = $optionId;
            $image = null;
            if (isset($data['image']) && $data['image']) {
                $image = $data['image'];
                unset($data['image']);
            }
            // temp name in case none was given
            if(!isset($data['name'])) $data['name'] = "unnamed";

            //set sort to next highest or else we may run intro trouble later if sort 0 is given twice
            $option = LayerOption::find($optionId);
            $data['sort'] = $option->layers()->count();

            $layer = Layer::create($data);

            if ($image) {
                $layer->image_extension = uniqid('', true) .'.' . $image->getClientOriginalExtension();
                if($data['name'] == "tempname") $layer->name = $image->getClientOriginalName();
                $layer->update();
                $this->handleImage($image, $layer->imagePath, $layer->imageFileName, null);
            }
            if($data['type'] == 'lines' && $layer->layerOption->countLineLayers() > 1) throw new \Exception("There can only be one line layer per layer option.");

            return $this->commitReturn($layer);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Updates a layer.
     *
     * @param  \App\Models\CharacterCreator\Layer $layer
     * @param  array                  $data 
     * @return bool|\App\Models\CharacterCreator\Layer
     */
    public function updateLayer($layer, $data)
    {
        DB::beginTransaction();

        try {

            $image = null;
            if (isset($data['image']) && $data['image']) {
                if (isset($layer->image_extension)) $old = $layer->imageFileName;
                else $old = null;
                $image = $data['image'];
                unset($data['image']);
            }
            if ($image) {
                $layer->image_extension = uniqid('', true) .'.' . $image->getClientOriginalExtension();
                $layer->update();
                $this->handleImage($image, $layer->imagePath, $layer->imageFileName, $old);
            }

            if(!isset($data['delete']) && $data['type'] == 'lines' && $layer->layerOption->countLineLayers() >= 1){
                throw new \Exception("There can only be one line layer per layer option.");
            } 

            if(isset($data['delete'])){
                $this->deleteLayer($layer);
            } else {
                $layer->update($data);
            }

            return $this->commitReturn($layer);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Deletes a layer.
     *
     * @param  \App\Models\CharacterCreato\CharacterCreator\Layer  $group
     * @return bool
     */
    public function deleteLayer($layer)
    {
        DB::beginTransaction();

        try {
            $this->deleteImage($layer->imagePath, $layer->imageFileName);
            $layer->delete();
            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Sorts layer order.
     *
     * @param  array  $data
     * @return bool
     */
    public function sortLayer($data)
    {
        DB::beginTransaction();

        try {
            // explode the sort array and reverse it since the order is inverted
            $sort = array_reverse(explode(',', $data));

            foreach ($sort as $key => $s) {
                Layer::where('id', $s)->update(['sort' => $key]);
            }

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }
}
