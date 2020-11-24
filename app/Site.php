<?php

namespace App;

use Composer\Package\Link;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Site
 * @package App
 * @author Kashif <kash@dreamsites.co.uk>
 * @created 26/09/2020
 *
 * @property int id
 * @property string logo
 * @property string logo_sha1sum
 * @property int logo_bytes
 * @property string last_ping
 * @property string last_logo_update
 * @property int status_code
 * @property int status_id
 * @property int link_id
 * @property int perform_scrape
 * @property string title
 * @property string description
 * @property string base_uri
 * @property string updated_at
 *
 */
class Site extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'base_uri', 'logo', 'logo_bytes', 'logo_sha1sum', 'last_logo_update', 'last_ping', 'status_code', 'status_id', 'updated_at', 'link_id', 'perform_scrape'
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
     * Get the links that belong to this Site.
     * site belongs to many links
     */
    public function links()
    {
        return $this->hasMany(Link::class);
    }

    public function rootLink()
    {
        return $this->hasOne(RootLink::class, 'id', 'link_id');
    }

}
