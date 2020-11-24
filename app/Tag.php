<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag
 * @package App
 * @author Kashif <kash@dreamsites.co.uk>
 * @created 22/09/2020
 * @property int id
 * @property string title
 * @property string tag_status
 * @property int user_id
 */
class Tag extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'user_id', 'tag_status'
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

    //many tags to many bookmarks
    public function bookmarks()
    {
        return $this->belongsToMany(Bookmark::class)->withTimestamps();
    }

}
