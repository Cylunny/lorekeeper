<?php

namespace App\Models\User;

use App\Models\Model;

class ContactAuthorization extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'granted_by_user_id', 'granted_to_user_id'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contact_authorizations';

    /**********************************************************************************************

        RELATIONS

     **********************************************************************************************/

    public function grantedByUser() {
        return $this->belongsTo(User::class, 'granted_by_user_id');
    }

    public function grantedToUser() {
        return $this->belongsTo(User::class, 'granted_to_user_id');
    }

    /**********************************************************************************************

        SCOPES

     **********************************************************************************************/


    /**********************************************************************************************

        ACCESSORS

     **********************************************************************************************/


}
