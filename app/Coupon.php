<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Coupon
 * @package App
 * @author Kashif <kash@dreamsites.co.uk>
 * @created 27/09/2020
 *
 * @property int id
 * @property string coupon_code
 * @property int user_id
 * @property int tier_id
 * @property string date_used
 *
 */
class Coupon extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'coupon_code', 'user_id', 'tier_id', 'date_used', 'id'
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

    /**
     * Get the Tier associated with this Coupon
     */
    public function tier()
    {
        return $this->hasOne(Tier::class);
    }

}
