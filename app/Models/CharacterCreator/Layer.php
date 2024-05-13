<?php

namespace App\Models\CharacterCreator;

use Config;
use DB;
use Carbon\Carbon;
use Notifications;
use App\Models\Model;

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

class Layer extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'sort', 'layer_option_id', 'image_extension'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'character_creator_layer';

    /**
     * Whether the model contains timestamps to be saved and updated.
     *
     * @var string
     */
    public $timestamps = false;

    /**
     * Validation rules for character creation.
     *
     * @var array
     */
    public static $createRules = [
        'name' => 'required|between:3,100',
        'layer_option_id' => 'required',
        'sort' => 'required',
        'image' => 'mimes:png,gif'
    ];

    /**
     * Validation rules for character updating.
     *
     * @var array
     */
    public static $updateRules = [
        'name' => 'required|between:3,100',
        'layer_option_id' => 'required',
        'sort' => 'required',
        'image' => 'mimes:png,gif'
    ];

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get the group this layer group belongs to.
     */
    public function layerOption()
    {
        return $this->belongsTo('App\Models\CharacterCreator\LayerOption', 'layer_option_id');
    }


    /**********************************************************************************************

        SCOPES

    **********************************************************************************************/

   
    /**********************************************************************************************

        ACCESSORS

    **********************************************************************************************/


    /**********************************************************************************************

        OTHER FUNCTIONS

    **********************************************************************************************/


}
