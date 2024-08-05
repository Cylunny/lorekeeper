<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DB;

use App\Http\Controllers\Controller;
use App\Models\SiteDesign;
use App\Services\SiteDesignService;

class SiteDesignController extends Controller
{
    /**
     * Shows the site design admin page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getIndex()
    {
        $fonts = [
            'Lato' => 'Lato',
            'Roboto Condensed, serif' => 'Roboto Condensed, serif',
            'Lora' => 'Lora',
            'Arvo' => 'Arvo',
            'Wellfleet' => 'Wellfleet',
            'Raleway' => 'Raleway',
            'Black Ops One' => 'Black Ops One',
            'Orbitron' => 'Orbitron',
            'Concert One' => 'Concert One',
            'Silkscreen' => 'Silkscreen',
            'Special Elite' => 'Special Elite',
            'Gloria Hallelujah' => 'Gloria Hallelujah',
            'Tangerine' => 'Tangerine',
            'Bad Script' => 'Bad Script',
        ];

        return view('admin.settings.design', [
            'fonts' => $fonts,
            'design' => SiteDesign::all()->first()
        ]);
    }

    /**
     * Edits the site design.
     *
     * @param  \Illuminate\Http\Request       $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEditDesign(Request $request, SiteDesignService $service)
    {
        $design = SiteDesign::all()->first();
        $data = $request->only([
            'design', 
            'heading_font_family', 'heading_letter_spacing', 'heading_text_transform', 'heading_font_weight',
            'navigation_font_family', 'navigation_letter_spacing', 'navigation_text_transform', 'navigation_font_weight',
            'sidebar_font_family', 'sidebar_letter_spacing', 'sidebar_text_transform', 'sidebar_font_weight',
            'body_font_family', 'body_letter_spacing', 'body_text_transform', 'body_font_weight'
        ]);
        if($design && $service->updateDesign($design, $data)) {
            flash('Site Design updated successfully.')->success();
        }
        else if (!$design && $news = $service->createDesign($data)) {
            flash('Site Design updated successfully.')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }
}
