<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Auth;

use App\Models\Forms\SiteForm;
use App\Services\SiteFormService;
use App\Models\Item\Item;
use App\Models\Currency\Currency;
use App\Models\Loot\LootTable;
use App\Models\Raffle\Raffle;
use App\Http\Controllers\Controller;

class SiteFormController extends Controller {
    /**
     * Shows the site_form index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getIndex() {
        return view('admin.forms.site_form', [
            'forms' => SiteForm::orderBy('updated_at', 'DESC')->paginate(20)
        ]);
    }

    /**
     * Shows the create site_form page. 
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateSiteForm() {
        return view('admin.forms.create_edit_site_form', [
            'form' => new SiteForm,
            'items' => Item::orderBy('name')->pluck('name', 'id'),
            'currencies' => Currency::where('is_user_owned', 1)->orderBy('name')->pluck('name', 'id'),
            'tables' => LootTable::orderBy('name')->pluck('name', 'id'),
            'raffles' => Raffle::where('rolled_at', null)->where('is_active', 1)->orderBy('name')->pluck('name', 'id'),
        ]);
    }

    /**
     * Shows the edit site_form page.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditSiteForm($id) {
        $form = SiteForm::find($id);
        if (!$form) abort(404);
        return view('admin.forms.create_edit_site_form', [
            'form' => $form,
            'items' => Item::orderBy('name')->pluck('name', 'id'),
            'currencies' => Currency::where('is_user_owned', 1)->orderBy('name')->pluck('name', 'id'),
            'tables' => LootTable::orderBy('name')->pluck('name', 'id'),
            'raffles' => Raffle::where('rolled_at', null)->where('is_active', 1)->orderBy('name')->pluck('name', 'id'),
        ]);
    }

    /**
     * Creates or edits a site_form page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Services\SiteFormService  $service
     * @param  int|null                  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditSiteForm(Request $request, SiteFormService $service, $id = null) {
        $id ? $request->validate(SiteForm::$updateRules) : $request->validate(SiteForm::$createRules);
        $data = $request->only([
            'title', 'description', 'start_at', 'end_at', 'is_active', 'is_timed', 'is_anonymous', 'questions', 'options', 'is_mandatory',
            'timeframe', 'is_public', 'is_editable', 'submit', 'edit', 'allow_likes', 'rewardable_type', 'rewardable_id', 'quantity', 'bump', 'is_multichoice'
        ]);
        if ($id && $service->updateSiteForm(SiteForm::find($id), $data, Auth::user())) {
            flash('SiteForm updated successfully.')->success();
        } else if (!$id && $site_form = $service->createSiteForm($data, Auth::user())) {
            flash('SiteForm created successfully.')->success();
            return redirect()->to('admin/forms/edit/' . $site_form->id);
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }

    /**
     * Gets the site_form deletion modal.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeleteSiteForm($id) {
        $form = SiteForm::find($id);
        return view('admin.forms._delete_site_form', [
            'form' => $form,
        ]);
    }

    /**
     * Deletes a site_form page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Services\SiteFormService  $service
     * @param  int                       $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteSiteForm(Request $request, SiteFormService $service, $id) {
        if ($id && $service->deleteSiteForm(SiteForm::find($id))) {
            flash('SiteForm deleted successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->to('admin/forms');
    }


    /**
     * Shows the results of a site form.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getSiteFormResults($id) {
        $form = SiteForm::find($id);
        if (!$form) abort(404);
        return view('admin.forms.site_form_results', [
            'form' => $form
        ]);
    }

    /**
     * Shows the results of a site form by respondent
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getSiteFormResultsAlt($id) {
        $form = SiteForm::find($id);
        if (!$form) abort(404);
        return view('admin.forms.site_form_results_alt', [
            'form' => $form,
            'userAnswers' => $form->userAnswers()->paginate(1)
        ]);
    }
}
