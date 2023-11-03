<?php

namespace App\Models\User;

use App\Models\Model;

class UserTitle extends Model {
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'title_id', 'user_id'
  ];

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'user_titles';

  /**********************************************************************************************
    
        RELATIONS
   **********************************************************************************************/

  /**
   * Get the user who owns the title.
   */
  public function user() {
    return $this->belongsTo('App\Models\User\User');
  }

  /**
   * Get the title associated with this user.
   */
  public function title() {
    return $this->belongsTo('App\Models\Character\CharacterTitle', 'title_id');
  }

  /**********************************************************************************************
    
        ACCESSORS
   **********************************************************************************************/

  /**
   * Gets the stack's asset type for asset management.
   *
   * @return string
   */
  public function getAssetTypeAttribute() {
    return 'user_title';
  }
}
