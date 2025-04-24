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
        'adoption_id', 'currency_id', 'days', 'amount', 'species_id'
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
     * Get the currency to change to.
     */
    public function currency() 
    {
        return $this->belongsTo('App\Models\Currency\Currency');
    }

    /**
     * Get the species this price applies to.
     */
    public function species() 
    {
        return $this->belongsTo('App\Models\Species\Species');
    }
    
    
    /**
     * Get the adoption that holds this price.
     */
    public function adoption() 
    {
        return $this->belongsTo('App\Models\Adoption\Adoption');
    }
    
    /**********************************************************************************************
    
        SCOPE

    **********************************************************************************************/


}
