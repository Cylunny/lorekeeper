<?php

namespace App\Http\Controllers\Admin\Data;

use Illuminate\Http\Request;
use Auth;

use App\Models\Character\CharacterTitle;
use App\Models\Rarity;

use App\Services\CharacterTitleService;

use App\Http\Controllers\Controller;

class CharacterTitleController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Admin / Character Title Controller
    |--------------------------------------------------------------------------
    |
    | Handles creation/editing of character titles.
    |
    */

    /**
     * Shows the title index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getIndex()
    {
        return view('admin.character_titles.titles', [
            'titles' => CharacterTitle::orderBy('sort', 'DESC')->get()
        ]);
    }

    /**
     * Shows the create title page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateTitle()
    {
        return view('admin.character_titles.create_edit_title', [
            'title' => new CharacterTitle,
            'rarities' => Rarity::orderBy('sort', 'DESC')->pluck('name', 'id')->toArray()
        ]);
    }

    /**
     * Shows the edit title page.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditTitle($id)
    {
        $title = CharacterTitle::find($id);
        if(!$title) abort(404);
        return view('admin.character_titles.create_edit_title', [
            'title' => $title,
            'rarities' => Rarity::orderBy('sort', 'DESC')->pluck('name', 'id')->toArray()
        ]);
    }

    /**
     * Creates or edits a title.
     *
     * @param  \Illuminate\Http\Request    $request
     * @param  App\Services\CharacterTitleService  $service
     * @param  int|null                    $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditTitle(Request $request, CharacterTitleService $service, $id = null)
    {
        $id ? $request->validate(CharacterTitle::$updateRules) : $request->validate(CharacterTitle::$createRules);
        $data = $request->only([
            'title', 'short_title', 'rarity_id', 'description', 'image', 'remove_image'
        ]);
        if($id && $service->updateTitle(CharacterTitle::find($id), $data, Auth::user())) {
            flash('Title updated successfully.')->success();
        }
        else if (!$id && $title = $service->createTitle($data, Auth::user())) {
            flash('Title created successfully.')->success();
            return redirect()->to('admin/data/character-titles/edit/'.$title->id);
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }

    /**
     * Gets the title deletion modal.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeleteTitle($id)
    {
        $title = CharacterTitle::find($id);
        return view('admin.character_titles._delete_title', [
            'title' => $title,
        ]);
    }

    /**
     * Deletes a title.
     *
     * @param  \Illuminate\Http\Request    $request
     * @param  App\Services\CharacterTitleService  $service
     * @param  int                         $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteTitle(Request $request, CharacterTitleService $service, $id)
    {
        if($id && $service->deleteTitle(CharacterTitle::find($id))) {
            flash('Title deleted successfully.')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->to('admin/data/character-titles');
    }

    /**
     * Sorts character titles.
     *
     * @param  \Illuminate\Http\Request    $request
     * @param  App\Services\CharacterTitleService  $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSortTitle(Request $request, CharacterTitleService $service)
    {
        if($service->sortTitle($request->get('sort'))) {
            flash('Title order updated successfully.')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }
}
