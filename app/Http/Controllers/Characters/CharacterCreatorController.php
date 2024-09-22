<?php

namespace App\Http\Controllers\Characters;

use Illuminate\Http\Request;

use DB;
use Auth;
use Route;
use Settings;
use App\Models\User\User;
use App\Models\CharacterCreator\CharacterCreator;
use App\Models\CharacterCreator\LayerGroup;
use App\Models\CharacterCreator\LayerOption;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic as Image;
use App\Services\CharacterCreatorManager;

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
        return view('character.creator.index', [
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

    /**
     * Get the image based on current form input.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getImage($id, Request $request, CharacterCreatorManager $service)
    {
        $creator = CharacterCreator::where('id', $id)->visible()->first();
        if(!$creator) {
            abort(404);
        }
        return view('character.creator._image', [
            'b64Images' => $service->getImages($creator, $request),
        ]);
    }

    
    /**
     * Get the updated marking select based on the current group and chosen option.
     * @param  Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getChoices(Request $request)
    {
        $groupId = $request->input('groupId');
        $optionId = $request->input('optionId');

        return view('character.creator._choices_select', [
            'group' => LayerGroup::find($groupId),
            'option' => LayerOption::find($optionId)
        ]);
    }

    /**
     * Gets the create character modal.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateCharacter($id)
    {
        $creator = CharacterCreator::where('id', $id)->visible()->first();
        $user = Auth::user();
        
        return view('character.creator._create_character_modal', [
            'creator' => $creator,
            'user' => $user
        ]);
    }

    /**
     * Creates a new character from what the user did, if the user is logged in and the
     * Creator allows for character creation.
     * @param  Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function postCreateCharacter($id, Request $request, CharacterCreatorManager $service){
        $creator = CharacterCreator::where('id', $id)->visible()->first();
        $user = Auth::user();
        if ($image = $service->createCharacter($creator, $request, $user)) {
            return view('character.creator.download', [
                'image' => $image,
                'creator' => $creator,
                'creators' => CharacterCreator::visible()->get()
            ]);
        } else {
            $error = $service->errors()->getMessages()['error'][0];
            //yes we are eating the rest of the errors woopiedoo.
            return "<span class='alert alert-danger w-100'>". $error . " Please return and try again. If the issue persists, contact a site admin.</span>";
        }
    }

}
