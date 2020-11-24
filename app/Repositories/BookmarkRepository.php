<?php
/**
 * Class BookmarkRepository
 * @package App\Repositories
 * @author Kashif <kash@dreamsites.co.uk>
 * @created 06/10/2020
 */

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Bookmark;

class BookmarkRepository
{

    /**
     * @param int|null $userId
     * @param array|string[] $status
     * @return mixed
     */
    public function fetchBookmarks(int $userId = null, array $status = ['live'])
    {
        return Bookmark::whereUserId($userId)->whereBookmarkStatus($status)->orderBy('created_at', 'DESC')->distinct()->get();
    }

    public function searchBookmarks(int $userId = null, $data)
    {
        $status = implode(',', $data['status']);

        if (isset($data['category_id'], $data['tag_id'])) {
            $classes = DB::select(
                'call GetBookmarksSearchCategoryTag(?,?,?,?,?)',
                array($userId, $status, $data['search_query'], $data['category_id'], $data['tag_id'])
            );
        } elseif (isset($data['category_id'])) {
            $classes = DB::select(
                'call GetBookmarksSearchCategory(?,?,?,?)',
                array($userId, $status, $data['search_query'], $data['category_id'])
            );
        } elseif (isset($data['tag_id'])) {
            $classes = DB::select(
                'call GetBookmarksSearchTag(?,?,?,?)',
                array($userId, $status, $data['search_query'], $data['tag_id'])
            );
        } elseif (isset($data['site_id'])) {
            $classes = DB::select(
                'call GetBookmarksSearchSite(?,?,?,?)',
                array($userId, $status, $data['search_query'], $data['site_id'])
            );
        }
        else {
            $data = ['show_trash' => $status, 'search_query' => $data['search_query']];
            $classes = DB::select(
                'call GetBookmarksSearch(?,?,?)',
                array($userId, $status, $data['search_query'])
            );
        }

        $collection = Bookmark::hydrate($classes);

        return $collection;
    }
}
