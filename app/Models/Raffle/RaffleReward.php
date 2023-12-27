<?php

namespace App\Models\Raffle;

use Config;
use App\Models\Model;

class RaffleReward extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'raffle_id', 'rewardable_type', 'rewardable_id', 'quantity'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'raffle_rewards';
    
    /**
     * Validation rules for creation.
     *
     * @var array
     */
    public static $createRules = [
        'rewardable_type' => 'required',
        'rewardable_id' => 'required',
        'quantity' => 'required|integer|min:1',
    ];
    
    /**
     * Validation rules for updating.
     *
     * @var array
     */
    public static $updateRules = [
        'rewardable_type' => 'required',
        'rewardable_id' => 'required',
        'quantity' => 'required|integer|min:1',
    ];

    /**********************************************************************************************
    
        RELATIONS

    **********************************************************************************************/
    
    /**
     * Get the reward attached to the raffle reward.
     */
    public function reward() 
    {
        switch ($this->rewardable_type)
        {
            case 'Item':
                return $this->belongsTo('App\Models\Item\Item', 'rewardable_id');
                break;
            case 'Currency':
                return $this->belongsTo('App\Models\Currency\Currency', 'rewardable_id');
                break;
            case 'LootTable':
                return $this->belongsTo('App\Models\Loot\LootTable', 'rewardable_id');
                break;
            case 'Raffle':
                return $this->belongsTo('App\Models\Raffle\Raffle', 'rewardable_id');
                break;
            case 'Character':
                return $this->belongsTo('App\Models\Character\Character', 'rewardable_id');
                break;
        }
        return null;
    }

        /*
     * Gets the display image for the reward.
     */
    public function getRewardImageAttribute() {
        switch ($this->rewardable_type)
        {
            case 'Item':
                return (isset($this->reward()->first()->imageUrl)) ? $this->reward()->first()->imageUrl : null;
                break;
            case 'Currency':
                return (isset($this->reward()->first()->currencyImageUrl)) ? $this->reward()->first()->currencyImageUrl : null;
            case 'Character':
                return (isset($this->reward()->first()->image)) ? $this->reward()->first()->image->thumbnailUrl : null;
                break;
        }
        return null;
    }
}
