<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 * @package App
 * @author Kashif <kash@dreamsites.co.uk>
 * @created 22/09/2020
 * @property int id
 * @property string title
 * @property string description
 * @property string category_status
 * @property string visibility
 * @property int user_id
 * @property int parent_category_id
 */
class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'category_status', 'visibility', 'user_id', 'parent_category_id'
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

    //many categories to many bookmarks
    public function bookmarks()
    {
        return $this->belongsToMany(Bookmark::class)->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * One Category can only have One Parent Category
     */
    public function parentCategory()
    {
        return $this->hasOne(ParentCategory::class, 'id', 'parent_category_id')->withDefault();
    }

}
