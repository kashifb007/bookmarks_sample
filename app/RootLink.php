<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RootLink
 * @package App
 * @author Kashif <kash@dreamsites.co.uk>
 * @created 29/09/2020
 *
 * @property int id
 * @property int site_id
 * @property string title
 * @property string url
 */
class RootLink extends Model
{
    /**
     * @var string
     */
    protected $table = 'links';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'site_id', 'title', 'url'
    ];

    protected function site()
    {
        return $this->hasOne(Site::class, 'link_id', 'site_id');
    }
}
