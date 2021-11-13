<?php

namespace Mybookmarks;

use Illuminate\Database\Eloquent\Model;

class BookmarkTag extends Model
{
    protected $fillable = [
        'bookmark_id', 'tag_id',
    ];    
}
