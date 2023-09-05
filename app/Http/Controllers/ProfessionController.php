<?php

namespace App\Http\Controllers;

use Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\SitePage;
use App\Models\Item\Item;
use App\Models\Currency\Currency;
use App\Models\Item\ItemCategory;
use App\Models\User\UserItem;
use App\Models\Profession\ProfessionCategory;
use App\Models\Profession\ProfessionSubcategory;
use App\Models\Profession\Profession;

class ProfessionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Profession Controller
    |--------------------------------------------------------------------------
    |
    | Handles viewing the professions.
    |
    */

    /**
     * Shows the profession index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getIndex()
    {
        $professionPage = SitePage::where('key','professions')->first();
        if(!$professionPage) abort(404);

        $professions = Profession::all();
        $categories = ProfessionCategory::orderBy('sort', 'DESC')->get();

        return view('professions.index', [
            'page' => $professionPage,
            'categories' => $categories,
        ]);
    }

    /**
     * Shows the profession category page and all its subcategories/professions.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCategory($id)
    {
        $category = ProfessionCategory::where('id', $id)->first();
        $categories = ProfessionCategory::orderBy('sort', 'DESC')->get();
        $professionsBySubCategories = Profession::where('category_id', $id)->get()->groupBy('subcategory_id');

        return view('professions.category', [
            'category' => $category,
            'categories' => $categories,
            'professionsBySubCategories' => $professionsBySubCategories
        ]);
    }

}


