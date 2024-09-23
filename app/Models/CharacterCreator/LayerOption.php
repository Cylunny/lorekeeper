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

    /**
     * Get the lowest color layer (base).
     *
     * @return Image
     */
    public function getBaseImageFilePathAttribute(){
        $base = $this->layers()->where('type', 'color')->orderBy('sort')->first();
        return $base->imageFilePath ?? null;
    }

    /**
     * Get the base layer.
     *
     * @return Image
     */
    public function getBaseLayerAttribute(){
        return $this->layers()->where('type', 'color')->orderBy('sort')->first() ?? null;
    }

    /**
     * Get the line layer.
     *
     * @return Image
     */
    public function getLineLayerAttribute(){
        return $this->layers()->where('type', 'lines')->first() ?? null;
    }

    /**
     * Gets the height of the images, should be the same for all of them so...
     *
     * @return array
     */
    public function getImageHeightAttribute(){

        return $this->layers()->first()->imageHeight ?? 0;
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
        if(count($select) > 0) {
            $select[0] = 'None'; //add no marking option if markings are present
            ksort($select); //sort by id so that none is default
        }
        return $select;
    }

    /**
     * Count line layers.
     *
     * @return array
     */
    public function countLineLayers(){

        $lineLayers = $this->layers->filter(function($layer) {
            return $layer->type == 'lines';
        });
        return $lineLayers->count();
    }

}
