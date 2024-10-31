<?php

namespace App\Models\Species;

use Config;
use App\Models\Model;

class SpeciesApprovalChecklist extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'species_id', 'description', 'parsed_description'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'species_approval_checklists';
    
    
    /**
     * Validation rules for creation.
     *
     * @var array
     */
    public static $createRules = [
        'species_id' => 'required',
        'description' => 'nullable',
    ];
    
    
    /**
     * Validation rules for updating.
     *
     * @var array
     */
    public static $updateRules = [
        'species_id' => 'required',
        'description' => 'nullable',
    ];

    /**********************************************************************************************
    
        RELATIONS

    **********************************************************************************************/

    /**
     * Get the subtypes for this species.
     */
    public function species() 
    {
        return $this->belongsTo('App\Models\Species\Species', 'species_id');
    }


    /**********************************************************************************************
    
        ACCESSORS

    **********************************************************************************************/
   
}
