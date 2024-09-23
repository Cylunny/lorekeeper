<?php

namespace App\Models\CharacterCreator;

use Config;
use DB;
use App\Models\Model;
use Intervention\Image\ImageManagerStatic as Image;

class Layer extends Model
{

    /**
     * The attributes that are mass assignable.
     * Layer types are: lines, color, detail. 
     * @var array
     */
    protected $fillable = [
        'name', 'sort', 'layer_option_id', 'image_extension', 'type'
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
        'image' => 'mimes:png,gif'
    ];

    /**
     * Validation rules for character updating.
     *
     * @var array
     */
    public static $updateRules = [
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

    /**
     * Gets the file directory containing the model's image.
     *
     * @return string
     */
    public function getImageDirectoryAttribute()
    {
        return 'images/data/character_creators/layers';
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
     * Gets the PATH of the model's image.
     *
     * @return string
     */
    public function getImageFilePathAttribute()
    {
        if (!$this->image_extension) return null;
        return $this->imageDirectory .'/'. $this->imageFileName;
    }

    /**
     * Gets the file name of the model's image.
     *
     * @return string
     */
    public function getImageFileNameAttribute()
    {
        return $this->id . '-layer-' . $this->image_extension;
    }

    /**
     * Gets the height of the images, should be the same for all of them so...
     *
     * @return array
     */
    public function getImageHeightAttribute(){
        return Image::make($this->imageUrl)->height() ?? 0;
    }

    /**********************************************************************************************

        OTHER FUNCTIONS

    **********************************************************************************************/


}
