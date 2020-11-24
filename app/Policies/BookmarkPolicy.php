<?php
/**
 * Class BookmarkPolicy
 * @package App\Policies
 * @author Kashif <kash@dreamsites.co.uk>
 * @created 24/09/2020
 */

namespace App\Policies;


use Illuminate\Auth\Access\HandlesAuthorization;
use App\Bookmark;
use App\User;

class BookmarkPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the bookmark.
     *
     * @param User $user
     * @param Bookmark $bookmark
     * @return mixed
     */
    public function view(User $user, Bookmark $bookmark)
    {
        return $bookmark->user_id == $user->id;
    }

    /**
     * Determine whether the user can create bookmarks.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the bookmark.
     *
     * @param User $user
     * @param Bookmark $bookmark
     * @return mixed
     */
    public function update(User $user, Bookmark $bookmark)
    {
        return $bookmark->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the bookmark.
     *
     * @param User $user
     * @param Bookmark $bookmark
     * @return mixed
     */
    public function delete(User $user, Bookmark $bookmark)
    {
        //
    }

    /**
     * Determine whether the user can restore the bookmark.
     *
     * @param User $user
     * @param Bookmark $bookmark
     * @return mixed
     */
    public function restore(User $user, Bookmark $bookmark)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the bookmark.
     *
     * @param User $user
     * @param Bookmark $bookmark
     * @return mixed
     */
    public function forceDelete(User $user, Bookmark $bookmark)
    {
        //
    }
}
