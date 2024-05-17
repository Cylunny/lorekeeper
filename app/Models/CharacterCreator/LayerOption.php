<?php

namespace App\Models\CharacterCreator;

use App\Models\Model;
use Intervention\Image\ImageManagerStatic as Image;

class LayerOption extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'parsed_description', 'sort', 'layer_group_id'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'character_creator_layer_option';

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
     * Get the group this layer group belongs to.
     */
    public function layerGroup()
    {
        return $this->belongsTo('App\Models\CharacterCreator\LayerGroup', 'layer_group_id');
    }

    /**
     * Get the layers this option consists of.
     */
    public function layers()
    {
        return $this->hasMany('App\Models\CharacterCreator\Layer', 'layer_option_id');
    }

    /**********************************************************************************************

        SCOPES

    **********************************************************************************************/

   
    /**********************************************************************************************

        ACCESSORS

    **********************************************************************************************/
    /**
     * Merges layers of this option into one image.
     *
     * @return Image
     */
    public function getLineImageUrlAttribute(){
        $line = $this->layers()->where('type', 'lines')->first();
        return $line->imageUrl ?? null;
    }

    /**********************************************************************************************

        OTHER FUNCTIONS

    **********************************************************************************************/

    /**
     * Merges layers of this option into one image.
     *
     * @return Image
     */
    public function merge(){

        $layers = $this->layers()->orderBy('sort', 'ASC')->get();
        $merged = Image::make($layers->first()->imageUrl);
        foreach($layers as $layer){
            $merged->insert($layer->imageUrl);
        }
        return $merged;
    }

    /**
     * Gets a select ready array of all marking options.
     *
     * @return array
     */
    public function getMarkingSelect(){

        $select = [];
        foreach($this->layers()->where('type', 'detail')->get() as $layer){
            $select[$layer->id] = $layer->name;
        }
        return $select;
    }


}
