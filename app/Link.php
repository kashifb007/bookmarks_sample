<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Link
 * @package App
 * @author Kashif <kash@dreamsites.co.uk>
 * @created 26/09/2020
 *
 * @property int id
 * @property string title
 * @property string meta_description
 * @property string url
 * @property string image
 * @property int site_id
 * @property int status_id
 * @property int user_id
 * @property string og_title
 * @property string og_image
 * @property string og_description
 * @property string og_type
 * @property string og_url
 * @property string updated_at
 * @property bool is_site
 */
class Link extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'meta_description', 'url', 'image', 'site_id', 'status_id', 'og_description', 'og_image', 'og_title', 'og_type', 'og_url', 'updated_at', 'user_id', 'is_site'
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
     * Get the site that owns the link.
     * many links to one site
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function bookmarks()
    {
        return $this->belongsTo(Bookmark::class);
    }

}
