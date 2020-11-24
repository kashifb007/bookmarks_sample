<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Subscription
 * @package App
 * @author Kashif <kash@dreamsites.co.uk>
 * @created 01/10/2020
 *
 * @property int id
 * @property string email
 * @property int user_id
 * @property int tier_id
 */
class Subscription extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'user_id', 'tier_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created' => 'datetime',
        'modified' => 'datetime',
    ];

    /**
     * Get the User associated with this Coupon
     */
    public function user()
    {
        return $this->hasOne(User::class);
    }
}
