<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * @package App
 * @author Kashif <kash@dreamsites.co.uk>
 * @created 22/09/2020
 *
 * @property int id
 * @property string firstname
 * @property string surname
 * @property string username
 * @property string password
 * @property bool remember
 * @property string remember_token
 * @property string twitter_username
 * @property int user_type
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'surname', 'email', 'password', 'username', 'remember', 'user_type', 'id', 'twitter_username'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created' => 'datetime',
        'modified' => 'datetime',
    ];

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function userType()
    {
        return $this->hasOne(UserType::class, 'id', 'user_type_id')->withDefault();
    }

}
