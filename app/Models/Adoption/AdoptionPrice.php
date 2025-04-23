<?php

namespace App\Models\Adoption;

use App\Models\Model;

class AdoptionPrice extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'adoption_id', 'currency_id', 'days', 'amount'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'adoption_prices';

    /**********************************************************************************************
    
        RELATIONS

    **********************************************************************************************/
    
    /**
     * Get the character being stocked.
     */
    public function currency() 
    {
        return $this->belongsTo('App\Models\Currency\Currency');
    }
    
    /**
     * Get the adoption that holds this character.
     */
    public function adoption() 
    {
        return $this->belongsTo('App\Models\Adoption\Adoption');
    }
    
    /**********************************************************************************************
    
        SCOPE

    **********************************************************************************************/


}
