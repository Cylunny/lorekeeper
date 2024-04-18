<?php

namespace App\Models\Character;

use App\Models\Model;

class CharacterAuthorization extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'character_id'
    ];
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'character_authorizations';
    
    /**
     * Validation rules for creation.
     *
     * @var array
     */
    public static $createRules = [
        'character_id' => 'required',
        'user_id' => 'required'
    ];
    
    /**
     * Validation rules for updating.
     *
     * @var array
     */
    public static $updateRules = [
        'character_id' => 'required',
        'user_id' => 'required'
    ];

    /**********************************************************************************************
    
        SCOPES

    **********************************************************************************************/


    /**********************************************************************************************
    
        RELATIONS

    **********************************************************************************************/

    /**
     * Get the character the record belongs to.
     */
    public function character() 
    {
        return $this->belongsTo('App\Models\Character\Character');
    }
    
    /**
     * Get the user the record belongs to.
     */
    public function user() 
    {
        return $this->belongsTo('App\Models\User\User');
    }
}
