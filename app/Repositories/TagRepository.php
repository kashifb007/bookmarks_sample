<?php
/**
 * Class TagRepository
 * @package App\Repositories
 * @author Kashif <kash@dreamsites.co.uk>
 * @created 06/10/2020
 */

namespace App\Repositories;

use App\Bookmark;
use Illuminate\Support\Facades\DB;

class TagRepository
{

    /**
     * @param int|null $userId
     * @param $data
     * @return mixed
     */
    public function searchTagBookmarks(int $userId = null, $data)
    {
        if (isset($data['category_id'], $data['tags'])) {
            $classes = DB::select(
                'call GetBookmarksSearchCategoryMultiTags(?,?,?,?,?,?)',
                array($userId, 'live', $data['search_query'], $data['category_id'], $data['tags'], $data['tags_count'])
            );
        } else {
            $classes = DB::select(
                'call GetBookmarksSearchCategory(?,?,?,?)',
                array($userId, 'live', $data['search_query'], $data['category_id'])
            );
        }

        return Bookmark::hydrate($classes);
    }
}
