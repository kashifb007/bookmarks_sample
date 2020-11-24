<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Bookmark
 * @package App
 * @author Kashif <kash@dreamsites.co.uk>
 * @created 22/09/2020
 * @property int id
 * @property string title
 * @property string description
 * @property int link_id
 * @property int sort_order
 * @property int user_id
 * @property int category_id
 * @property string bookmark_status
 * @property string visibility
 */
class Bookmark extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'link_id', 'sort_order', 'user_id', 'bookmark_status', 'visibility', 'category_id'
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
     * Get the user that owns the bookmark.
     * belongs to one user
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    /**
     * Get the tags for the bookmark.
     * many bookmarks to many tags
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    /**
     * Get the categories for the bookmark.
     * many bookmarks to many categories
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    /**
     * Get the link for this bookmark
     */
    public function link()
    {
        return $this->hasOne(Link::class, 'id', 'link_id')->withDefault();
    }
}
