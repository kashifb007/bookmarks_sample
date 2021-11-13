<?php

namespace Mybookmarks;

use Illuminate\Database\Eloquent\Model;

class BookmarkCategory extends Model
{
   	protected $fillable = [
        'bookmark_id', 'category_id',
    ];

}
