<?php

namespace App\Models\Species;

use Config;
use App\Models\Model;

class SubtypeApprovalChecklist extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subtype_id', 'description', 'parsed_description'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subtype_approval_checklists';
    
    
    /**
     * Validation rules for creation.
     *
     * @var array
     */
    public static $createRules = [
        'subtype_id' => 'required',
        'description' => 'nullable',
    ];
    
    
    /**
     * Validation rules for updating.
     *
     * @var array
     */
    public static $updateRules = [
        'subtype_id' => 'required',
        'description' => 'nullable',
    ];
    

    /**********************************************************************************************
    
        RELATIONS

    **********************************************************************************************/
    
    /**
     * Get the species the subtype belongs to.
     */
    public function subtype() 
    {
        return $this->belongsTo('App\Models\Species\Subtype', 'subtype_id');
    }

    /**********************************************************************************************
    
        ACCESSORS

    **********************************************************************************************/


}
