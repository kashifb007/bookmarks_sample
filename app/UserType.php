<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    protected $table = 'user_type';

    public function user()
    {
        return $this->hasOne(User::class, 'user_type_id', 'id')->withDefault();
    }
}
