<?php

namespace App\Models\CharacterCreator;

use Config;
use DB;
use Carbon\Carbon;
use Notifications;
use App\Models\Model;
use Illuminate\Support\Str;

use App\Models\User\User;
use App\Models\User\UserCharacterLog;

use App\Models\Character\CharacterCategory;
use App\Models\Character\CharacterTransfer;
use App\Models\Character\CharacterBookmark;

use App\Models\Character\CharacterCurrency;
use App\Models\Currency\Currency;
use App\Models\Currency\CurrencyLog;

use App\Models\Character\CharacterItem;
use App\Models\Item\Item;
use App\Models\Item\ItemLog;

use App\Models\Submission\Submission;
use App\Models\Submission\SubmissionCharacter;
use Illuminate\Database\Eloquent\SoftDeletes;

class CharacterCreator extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'parsed_description', 'cost', 'item_id', 'currency_id', 'is_visible', 'image_extension'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'character_creators';

    /**
     * Whether the model contains timestamps to be saved and updated.
     *
     * @var string
     */
    public $timestamps = true;

    /**
     * Validation rules for character creation.
     *
     * @var array
     */
    public static $createRules = [
        'name' => 'required|unique:character_creators|between:3,100',
        'currency_id' => 'nullable',
        'item_id' => 'nullable',
        'cost' => 'required', // can be 0 by default
        'description' => 'nullable',
        'image' => 'mimes:png,gif,jpg,jpeg'
    ];

    /**
     * Validation rules for character updating.
     *
     * @var array
     */
    public static $updateRules = [
        'name' => 'required|between:3,100',
        'currency_id' => 'nullable',
        'item_id' => 'nullable',
        'cost' => 'required', // can be 0 by default
        'description' => 'nullable',
        'image' => 'mimes:png,gif,jpg,jpeg'
    ];

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get the currency needed to use this maker.
     */
    public function currency()
    {
        return $this->belongsTo('App\Models\Currency\Currency', 'currency_id');
    }

    /**
     * Get the item needed to use this maker.
     */
    public function item()
    {
        return $this->belongsTo('App\Models\Item\Item', 'item_id');
    }


    /**
     * Get the layer groups that belong to this creator
     */
    public function layerGroups()
    {
        return $this->hasMany('App\Models\CharacterCreator\LayerGroup');
    }


    /**********************************************************************************************

        SCOPES

    **********************************************************************************************/


    /**
     * Scope a query to only include visible makers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', 1);
    }

   
    /**********************************************************************************************

        ACCESSORS

    **********************************************************************************************/

    /**
     * Gets the character's page's URL.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return url('maker/'.$this->slug);

    }

    /**
     * Gets the character's code.
     * If this is a MYO slot, it will return the MYO slot's name.
     *
     * @return string
     */
    public function getSlugAttribute()
    {
        return $this->id . '.' . Str::slug($this->name);
    }

     /**
     * Gets the file directory containing the model's image.
     *
     * @return string
     */
    public function getImageDirectoryAttribute()
    {
        return 'images/data/character_creators';
    }

    /**
     * Gets the path to the file directory containing the model's image.
     *
     * @return string
     */
    public function getImagePathAttribute()
    {
        return public_path($this->imageDirectory);
    }

    /**
     * Gets the URL of the model's image.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image_extension) return null;
        return asset($this->imageDirectory . '/' . $this->imageFileName);
    }

    /**
     * Gets the file name of the model's image.
     *
     * @return string
     */
    public function getImageFileNameAttribute()
    {
        return $this->id . '-creator-.' . $this->image_extension;
    }


    /**********************************************************************************************

        OTHER FUNCTIONS

    **********************************************************************************************/


}
