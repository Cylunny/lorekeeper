<?php namespace App\Models\Sales;

use App\Models\Model;
use DB;

class SaleRaffleTicket extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'sale_character_id', 'winner', 'created_at'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales_raffle_tickets';

    /**
     * Dates on the model to convert to Carbon instances.
     *
     * @var array
     */
    protected $dates = ['created_at'];


    /**********************************************************************************************
    
        RELATIONS

    **********************************************************************************************/

    /**
     * Get the raffle this ticket is for.
     */
    public function saleCharacter()
    {
        return $this->belongsTo('App\Models\Sales\SalesCharacter', 'sale_character_id');
    }

    /**
     * Get the user who owns the raffle ticket.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User\User');
    }

    /**********************************************************************************************
    
        SCOPES

    **********************************************************************************************/
    
    /**
     * Scope a query to only include the winning tickets in order of drawing.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWinners($query)
    {
        $query->where('winner', 1);
    }

    /**********************************************************************************************
    
        OTHER FUNCTIONS

    **********************************************************************************************/
    
    /**
     * Display the ticket holder's name. 
     * If the owner is not a registered user on the site, this displays the ticket holder's dA name.
     *
     * @return string
     */
    public function getDisplayHolderNameAttribute()
    {
        if ($this->user_id) return $this->user->displayName;
        return '<a href="http://'.$this->alias.'.deviantart.com">'.$this->alias.'@dA</a>';
    }

}
