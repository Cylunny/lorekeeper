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
            'name', 'description', 'parsed_description', 'cost', 'item_id', 'currency_id', 'is_visible', 'image', 'remove_image', 'allow_character_creation'
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
            'name', 'description', 'is_mandatory'
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
        $group = LayerGroup::find($id);
        if ($id && $service->deleteLayerGroup($group)) {
            flash('LayerGroup deleted successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->to('admin/data/creators/edit/' . $group->character_creator_id);
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
        if ($service->sortLayerGroup($request->get('sort'))) {
            flash('LayerGroup order updated successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) flash($error)->error();
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
    public function getCreateLayerOption($group_id)
    {
        $group = LayerGroup::find($group_id);
        if (!$group) abort(404);
        return view('admin.creator.create_edit_layer_option', [
            'creator' => $group->creator,
            'group' => $group,
            'option' => new LayerOption,
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
        $option = LayerOption::find($id);
        if (!$option) abort(404);
        return view('admin.creator.create_edit_layer_option', [
            'creator' => $option->layerGroup->creator,
            'group' => $option->layerGroup,
            'option' => $option,
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
        $id ? $request->validate(LayerOption::$updateRules) : $request->validate(LayerOption::$createRules);
        $data = $request->only([
            'name', 'description'
        ]);
        if (str_contains($request->getPathInfo(), 'edit') && $service->updateLayerOption(LayerOption::find($id), $data)) {
            flash('LayerOption updated successfully.')->success();
        } else if (!str_contains($request->getPathInfo(), 'edit') && $creator = $service->createLayerOption($data, $id)) {
            flash('LayerOption created successfully.')->success();
            return redirect()->to('admin/data/creators/layeroption/edit/' . $creator->id);
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
        $option = LayerOption::find($id);
        return view('admin.creator._delete_layer_option', [
            'option' => $option,
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
        $option = LayerOption::find($id);
        if ($id && $service->deleteLayerOption($option)) {
            flash('LayerOption deleted successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->to('admin/data/creators/layergroup/edit/' . $option->layer_group_id);
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
        if ($service->sortLayerOption($request->get('sort'))) {
            flash('LayerOption order updated successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }


    /*****************************************************************
     *  
     * LAYERS
     * 
     ******************************************************************/

    /**
     * Shows the create layer option page. 
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateLayer($option_id)
    {
        $option = LayerOption::find($option_id);
        if (!$option) abort(404);
        return view('admin.creator._create_edit_layer', [
            'option' => $option,
            'layer' => new Layer,
        ]);
    }

    /**
     * Shows the edit layer option page.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditLayer($id)
    {
        $layer = Layer::find($id);
        if (!$layer) abort(404);
        return view('admin.creator._create_edit_layer', [
            'option' => $layer->option,
            'layer' => $layer,
        ]);
    }

    /**
     * Creates or edits a layer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Services\CharacterCreatorService  $service
     * @param  int|null                  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditLayer(Request $request, CharacterCreatorService $service, $id = null)
    {
        $id ? $request->validate(Layer::$updateRules) : $request->validate(Layer::$createRules);
        $data = $request->only([
            'name', 'image', 'type', 'delete'
        ]);
        if (str_contains($request->getPathInfo(), 'edit') && $service->updateLayer(Layer::find($id), $data)) {
            flash('Layer updated successfully.')->success();
        } else if (!str_contains($request->getPathInfo(), 'edit') && $layer = $service->createLayer($data, $id)) {
            flash('Layer created successfully.')->success();
            return redirect()->to('admin/data/creators/layeroption/edit/' . $layer->layerOption->id);
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }

    /**
     * Sorts layers.
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  App\Services\CharacterCreatorService  $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSortLayer(Request $request, CharacterCreatorService $service)
    {
        if ($service->sortLayer($request->get('sort'))) {
            flash('Layer order updated successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }

}
