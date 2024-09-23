<?php

namespace App\Models\CharacterCreator;

use Config;
use DB;
use Carbon\Carbon;
use Notifications;
use App\Models\Model;

class LayerGroup extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'parsed_description', 'sort', 'character_creator_id', 'is_mandatory'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'character_creator_layer_group';

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
        'description' => 'nullable',
    ];

    /**
     * Validation rules for character updating.
     *
     * @var array
     */
    public static $updateRules = [
        'name' => 'required|between:3,100',
        'description' => 'nullable',
    ];

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get the creator this layer group belongs to.
     */
    public function creator()
    {
        return $this->belongsTo('App\Models\CharacterCreator\CharacterCreator', 'character_creator_id');
    }

    /**
     * Get the layer options that this group has
     */
    public function layerOptions()
    {
        return $this->hasMany('App\Models\CharacterCreator\LayerOption', 'layer_group_id');
    }


    /**********************************************************************************************

        SCOPES

    **********************************************************************************************/

   
    /**********************************************************************************************

        ACCESSORS

    **********************************************************************************************/

    /**
     * Gets the height of the images, should be the same for all of them so...
     *
     * @return array
     */
    public function getImageHeightAttribute(){

        return $this->layerOptions()->first()->imageHeight ?? 0;
    }

    /**********************************************************************************************

        OTHER FUNCTIONS

    **********************************************************************************************/

    /**
     * Gets a select ready array of all options.
     *
     * @return array
     */
    public function getOptionSelect(){

        $select = [];
        foreach($this->layerOptions as $option){
            $select[$option->id] = $option->name;
        }
        if(!$this->is_mandatory) {
            $select[0] = 'None'; //add none option of  the group is not mandatory eg wings being optional
            ksort($select); //sort by id so that none is default
        }
        return $select;
    }

}
