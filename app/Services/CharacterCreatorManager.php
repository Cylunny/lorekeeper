<?php

namespace App\Services;

use DB;
use Carbon\Carbon;
use App\Models\CharacterCreator\LayerGroup;
use App\Models\CharacterCreator\LayerOption;
use App\Models\CharacterCreator\Layer;
use Intervention\Image\ImageManagerStatic as Image;

class CharacterCreatorManager extends Service
{
    /*
    |--------------------------------------------------------------------------
    | CharacterCreator Manager
    |--------------------------------------------------------------------------
    |
    | Handles creation and modification of CharacterCreator ticket data.
    |
    */

    /**
     * Build the image html based on form input from the user.
     * $choicesByGroup will format the user input like so:
     * 
     * 1 => array:4 [
     *  "groupId": "1"
     *  "option" => "1"
     *  "colorlayers" => array:1 [
     *    6 => null
     *   ]
     * "marking" => "4"
     * "markingcolor" => "#A67A7A"
     * ]
     *
     * where 1 = sort, groupId = group id, option = option id, color layers is layer Id = hex color, marking = marking id and markingcolor = hex color.
     */
    public function getImages($creator, $request)
    {
        $images = [];
        $choicesByGroup = [];
        // build nestled array for easy request data access
        foreach ($request->all() as $key => $value) {
            $split = explode("_", $key);
            $groupId = $split[0];
            $selectionType = $split[1];
            $group = LayerGroup::find($groupId);
            if(isset($group)){
                $sort = $group->sort;
                $choicesByGroup[$sort]['groupId'] = $groupId;
                if ($selectionType == 'option') $choicesByGroup[$sort]['option'] = $value;
                if ($selectionType == 'marking') $choicesByGroup[$sort]['marking'] = $value;
                if ($selectionType == 'markingcolor') $choicesByGroup[$sort]['markingcolor'] = $value;
                if (is_numeric($selectionType)) $choicesByGroup[$sort]['colorlayers'][$selectionType] = $value;
            }
        };

        //sort by key to keep layer order
        ksort($choicesByGroup);

        //go over the layer choices and build the base64 images
        foreach ($choicesByGroup as $sort => $choices) {

            $option = LayerOption::find($choices['option']);

            // sort color layers and only include those matching the option
            $colorLayers = array_intersect_key($choices['colorlayers'], array_flip($option->layers()->pluck('id')->toArray()));

            // if color layers is empty...we add the base layer only.
            if(count($colorLayers) <= 0){
                $colorUrl = $this->colorize($option->baseImageFilePath, '#FFFFFF');
                $images[] = $colorUrl;
            }
            // get color layers
            foreach ($colorLayers as $layerId => $color) {
                $colorLayer = Layer::find($layerId);
                $colorUrl = $this->colorize($colorLayer->imageFilePath, $color);
                $images[] = $colorUrl;
            }

            // get marking layer and color it then turn it into a b64 image url
            if(isset($choices['marking']) && $option->layers()->pluck('id')->contains($choices['marking'])){
                $markingLayer = Layer::find($choices['marking']);
                $markingUrl = $this->colorize($markingLayer->imageFilePath, $choices['markingcolor']);
                $images[] = $markingUrl;
            }
            // get line image url
            $lineImageUrl = $option->lineImageUrl;
            $images[] = $lineImageUrl;
        }
        return $images;
    }

    /**
     * Colorize image with given path and return it as a base64 image url.
     */
    private function colorize($path, $color)
    {
        // CALC EXPLANATION
        // For the colorize function, 100 means full color, -100 means all color removed (for either R G or B)
        // in RGB 255 means full color, 0 means all color removed (for either R G or B)
        // round(hexdec(substr($color, 1, 2)) gets the decimal value/rgb value of a hex pair 
        // 255 - rbg value gives us the amount we need to subtract from our white base img to get the specified color (since white images are 255,255,255 RGB)
        // subtract amount / 255 gives us the factor of how much we need to remove
        // 100 * factor gives us the value between 0-100 that the colorize function can use, because its normalized
        $rFactor = 100 * ((255 - round(hexdec(substr($color, 1, 2)))) / 255);
        $gFactor = 100 * ((255 - round(hexdec(substr($color, 3, 2)))) / 255);
        $bFactor = 100 * ((255 - round(hexdec(substr($color, 5, 2)))) / 255);

        // Color the Image
        $colored = Image::make($path)->colorize(-$rFactor, -$gFactor, -$bFactor);
        return $colored->encode('data-url');
    }
}
