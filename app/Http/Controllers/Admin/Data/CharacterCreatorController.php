<?php

namespace App\Http\Controllers\Admin\Data;

use Illuminate\Http\Request;

use Auth;

use App\Models\CharacterCreator\CharacterCreator;
use App\Models\CharacterCreator\LayerGroup;
use App\Models\CharacterCreator\LayerOption;
use App\Models\CharacterCreator\Layer;
use App\Services\CharacterCreatorService;

use App\Models\Item\Item;
use App\Models\Currency\Currency;

use App\Http\Controllers\Controller;

class CharacterCreatorController extends Controller
{

    /**
     * Shows the creator index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getIndex()
    {
        return view('admin.creator.index', [
            'creators' => CharacterCreator::orderBy('created_at', 'DESC')->paginate(20)
        ]);
    }

    /*****************************************************************
     *  
     * CHARACTER CREATOR
     * 
     ******************************************************************/

    /**
     * Shows the create creator page. 
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateCharacterCreator()
    {
        return view('admin.creator.create_edit_creator', [
            'creator' => new CharacterCreator,
            'items' => [null => 'No item'] + Item::all()->pluck('name', 'id')->toArray(),
            'currencies' => [null => 'No Currency'] + Currency::all()->pluck('name', 'id')->toArray()
        ]);
    }

    /**
     * Shows the edit creator page.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditCharacterCreator($id)
    {
        $creator = CharacterCreator::find($id);
        if (!$creator) abort(404);
        return view('admin.creator.create_edit_creator', [
            'creator' => $creator,
            'items' => [null => 'No item'] + Item::all()->pluck('name', 'id')->toArray(),
            'currencies' => [null => 'No Currency'] + Currency::all()->pluck('name', 'id')->toArray()
        ]);
    }

    /**
     * Creates or edits a creator page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Services\CharacterCreatorService  $service
     * @param  int|null                  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditCharacterCreator(Request $request, CharacterCreatorService $service, $id = null)
    {
        $id ? $request->validate(CharacterCreator::$updateRules) : $request->validate(CharacterCreator::$createRules);
        $data = $request->only([
            'name', 'description', 'parsed_description', 'cost', 'item_id', 'currency_id', 'is_visible', 'image', 'remove_image'
        ]);
        if ($id && $service->updateCharacterCreator(CharacterCreator::find($id), $data, Auth::user())) {
            flash('CharacterCreator updated successfully.')->success();
        } else if (!$id && $creator = $service->createCharacterCreator($data, Auth::user())) {
            flash('CharacterCreator created successfully.')->success();
            return redirect()->to('admin/data/creators/edit/' . $creator->id);
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }

    /**
     * Gets the creator deletion modal.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeleteCharacterCreator($id)
    {
        $creator = CharacterCreator::find($id);
        return view('admin.creator._delete_creator', [
            'creator' => $creator,
        ]);
    }

    /**
     * Deletes a creator page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Services\CharacterCreatorService  $service
     * @param  int                       $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteCharacterCreator(Request $request, CharacterCreatorService $service, $id)
    {
        if ($id && $service->deleteCharacterCreator(CharacterCreator::find($id))) {
            flash('CharacterCreator deleted successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->to('admin/data/creators');
    }

    /*****************************************************************
     *  
     * LAYER GROUPS
     * 
     ******************************************************************/

    /**
     * Shows the create layer group page. 
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateLayerGroup($creator_id)
    {
        $creator = CharacterCreator::find($creator_id);
        if (!$creator) abort(404);
        return view('admin.creator.create_edit_layer_group', [
            'creator' => $creator,
            'group' => new LayerGroup,
            'items' => [null => 'No item'] + Item::all()->pluck('name', 'id')->toArray(),
            'currencies' => [null => 'No Currency'] + Currency::all()->pluck('name', 'id')->toArray()
        ]);
    }

    /**
     * Shows the edit layer group page.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditLayerGroup($id)
    {
        $group = LayerGroup::find($id);
        if (!$group) abort(404);
        return view('admin.creator.create_edit_layer_group', [
            'creator' => $group->creator,
            'group' => $group,
            'items' => [null => 'No item'] + Item::all()->pluck('name', 'id')->toArray(),
            'currencies' => [null => 'No Currency'] + Currency::all()->pluck('name', 'id')->toArray()
        ]);
    }

    /**
     * Creates or edits a layer group page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Services\CharacterCreatorService  $service
     * @param  int|null                  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditLayerGroup(Request $request, CharacterCreatorService $service, $id = null)
    {
        $id ? $request->validate(LayerGroup::$updateRules) : $request->validate(LayerGroup::$createRules);
        $data = $request->only([
            'name', 'description'
        ]);
        if (str_contains($request->getPathInfo(), 'edit') && $service->updateLayerGroup(LayerGroup::find($id), $data)) {
            flash('LayerGroup updated successfully.')->success();
        } else if (!str_contains($request->getPathInfo(), 'edit') && $creator = $service->createLayerGroup($data, $id)) {
            flash('LayerGroup created successfully.')->success();
            return redirect()->to('admin/data/creators/layergroup/edit/' . $creator->id);
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }

    /**
     * Gets the layer group deletion modal.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeleteLayerGroup($id)
    {
        $group = LayerGroup::find($id);
        return view('admin.creator._delete_layer_group', [
            'group' => $group,
        ]);
    }

    /**
     * Deletes a layer group page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Services\CharacterCreatorService  $service
     * @param  int                       $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteLayerGroup(Request $request, CharacterCreatorService $service, $id)
    {
        if ($id && $service->deleteCharacterCreator(LayerGroup::find($id))) {
            flash('LayerGroup deleted successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->to('admin/data/creators/{{ $id }}');
    }

    /**
     * Sorts layer groups.
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  App\Services\CharacterCreatorService  $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSortLayerGroup(Request $request, CharacterCreatorService $service)
    {
        if($service->sortLayerGroup($request->get('sort'))) {
            flash('LayerGroup order updated successfully.')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }

    /*****************************************************************
     *  
     * LAYER OPTIONS
     * 
     ******************************************************************/

    /**
     * Shows the create layer option page. 
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateLayerOption($creator_id)
    {
        $creator = CharacterCreator::find($creator_id);
        if (!$creator) abort(404);
        return view('admin.creator.create_edit_layer_group', [
            'creator' => $creator,
            'group' => new LayerGroup,
            'items' => [null => 'No item'] + Item::all()->pluck('name', 'id')->toArray(),
            'currencies' => [null => 'No Currency'] + Currency::all()->pluck('name', 'id')->toArray()
        ]);
    }

    /**
     * Shows the edit layer option page.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditLayerOption($id)
    {
        $group = LayerGroup::find($id);
        if (!$group) abort(404);
        return view('admin.creator.create_edit_layer_group', [
            'creator' => $group->creator,
            'group' => $group,
            'items' => [null => 'No item'] + Item::all()->pluck('name', 'id')->toArray(),
            'currencies' => [null => 'No Currency'] + Currency::all()->pluck('name', 'id')->toArray()
        ]);
    }

    /**
     * Creates or edits a layer option page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Services\CharacterCreatorService  $service
     * @param  int|null                  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditLayerOption(Request $request, CharacterCreatorService $service, $id = null)
    {
        $id ? $request->validate(LayerGroup::$updateRules) : $request->validate(LayerGroup::$createRules);
        $data = $request->only([
            'name', 'description'
        ]);
        if (str_contains($request->getPathInfo(), 'edit') && $service->updateLayerGroup(LayerGroup::find($id), $data)) {
            flash('LayerGroup updated successfully.')->success();
        } else if (!str_contains($request->getPathInfo(), 'edit') && $creator = $service->createLayerGroup($data, $id)) {
            flash('LayerGroup created successfully.')->success();
            return redirect()->to('admin/data/creators/layergroup/edit/' . $creator->id);
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }

    /**
     * Gets the layer option deletion modal.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeleteLayerOption($id)
    {
        $group = LayerGroup::find($id);
        return view('admin.creator._delete_layer_group', [
            'group' => $group,
        ]);
    }

    /**
     * Deletes a layer option page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Services\CharacterCreatorService  $service
     * @param  int                       $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteLayerOption(Request $request, CharacterCreatorService $service, $id)
    {
        if ($id && $service->deleteCharacterCreator(LayerGroup::find($id))) {
            flash('LayerGroup deleted successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->to('admin/data/creators/{{ $id }}');
    }

    /**
     * Sorts layer option.
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  App\Services\CharacterCreatorService  $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSortLayerOption(Request $request, CharacterCreatorService $service)
    {
        if($service->sortLayerGroup($request->get('sort'))) {
            flash('LayerGroup order updated successfully.')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }
}
