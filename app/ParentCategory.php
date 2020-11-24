<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ParentCategory
 * @package App
 * @author Kashif <kash@dreamsites.co.uk>
 * @created 28/09/2020
 *
 * @property int id
 * @property string title
 * @property string description
 * @property string category_status
 * @property string visibility
 * @property int user_id
 */
class ParentCategory extends Model
{
    /**
     * @var string
     */
    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'category_status', 'visibility', 'user_id', 'parent_category_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * one Parent Category has many categories
     */
    public function category()
    {
        return $this->hasMany(Category::class,'parent_category_id', 'id');
    }
}
