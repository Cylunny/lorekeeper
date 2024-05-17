<?php

namespace App\Http\Controllers\Characters;

use Illuminate\Http\Request;

use DB;
use Auth;
use Route;
use Settings;
use App\Models\User\User;
use App\Models\CharacterCreator\CharacterCreator;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic as Image;

class CharacterCreatorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Character Creator Controller
    |--------------------------------------------------------------------------
    |
    | Handles routes related to the character creator.
    | for stuff to work, composer require intervention/image has to be used.
    |
    */

    /**
     * Shows the character creator index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getIndex()
    {
        $path = 'images/data/character_creator/body_clr.png';
        $color = '#F35ED1';

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
        $merged = Image::make($path)->colorize(-$rFactor, -$gFactor, -$bFactor)->insert('images/data/character_creator/eyes_lin.png');
        return view('character.creator.index', [
            'image' => base64_encode(file_get_contents($path)),
            'colored_image' => $colored->encode('data-url'),
            'merged_image' => $merged->encode('data-url'),
            'creators' => CharacterCreator::visible()->get()
        ]);
    }


    /**
     * Shows a specific creator.
     *
     * @param  string  $slug
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCharacterCreator($id, $slug = null)
    {
        $creator = CharacterCreator::where('id', $id)->visible()->first();

        if(!$creator) abort(404);
        return view('character.creator.creator', [
            'creator' => $creator,
            'creators' => CharacterCreator::visible()->get()
        ]);
    }

}
