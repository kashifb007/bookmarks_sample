<?php
/**
 * Class CategoryRepository
 * @package App\Repositories
 * @author Kashif <kash@dreamsites.co.uk>
 * @created 06/10/2020
 */

namespace App\Repositories;

use App\Category;

class CategoryRepository
{

    /**
     * @param int|null $userId
     * @param array $status
     * @return mixed
     */
    public function fetchCategories(int $userId = null, array $status = ['live'])
    {
        $categories = Category::whereUserId($userId)->whereIn('category_status', $status)->orderBy('title')->get();
        return $categories;
    }

    /**
     * @param int|null $userId
     * @param $data
     * @return mixed
     */
    public function fetchCategoryBookmarks(int $userId = null, $data)
    {
//        if (isset($data['category_id'], $data['tags'])) {
//            $classes = DB::select(
//                'call GetBookmarksSearchCategoryMultiTags(?,?,?,?,?,?)',
//                array($userId, 'live', $data['search_query'], $data['category_id'], $data['tags'], $data['tags_count'])
//            );
//        }
//
//        return Bookmark::hydrate($classes);
    }

}
