<?php

namespace App\Http\Controllers;

use App\Bookmark;
use App\Category;
use App\Services\BookmarkService;
use App\Services\TagService;
use App\Tag;
use App\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
#use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{

    /**
     * @var BookmarkService
     */
    private $bookmarkService;

    /**
     * @var TagService
     */
    private $tagService;

    public function __construct(BookmarkService $bookmarkService, TagService $tagService)
    {
        $this->bookmarkService = $bookmarkService;
        $this->tagService = $tagService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|Response
     */
    public function index(string $username)
    {
        $user = User::whereRaw('users.username = ?', [$username])->get();

        foreach ($user as $u) {
            $id = $u->id;
        }

        $models = Bookmark::whereRaw('bookmarks.user_id = ?', [$id])->whereRaw('bookmarks.visibility LIKE \'public\'')->whereRaw('bookmarks.bookmark_status LIKE \'live\'')->orderBy('created_at', 'DESC')->get();

        // ONLY PUBLIC CATEGORIES
        $categories = Category::whereRaw('categories.user_id = ?', [$id])->whereRaw('categories.visibility LIKE \'public\'')->whereRaw('categories.category_status LIKE \'live\'')->orderBy('title')->get();

        $category_tree = [];
        $category_names_tree = [];

        // ONLY PUBLIC CATEGORIES
        foreach ($categories as $category) {
            $category_tree[$category->id] = $category->parent_category_id;
            $category_names_tree[$category->id] = $category->title;
        }

        $result = $this->bookmarkService->parseTree($category_tree, null);
        $this->bookmarkService->printSelectTree($result, $category_names_tree, $username);
        $treeData = $this->bookmarkService->getSelectData();

        return view('public.index', [
            'title' => generateString(0),
            'url' => generateString(3),
            'categories' => $categories,
            'user_id' => $id,
            'username' => $username,
            'treeData' => $treeData,
            'fullWidth' => true,
            'showSites' => false,
            'showFilter' => true,
        ]);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function showTags(Request $request)
    {
        $user = User::whereRaw('users.username = ?', [$request->get('username')])->get();

        foreach ($user as $u) {
            $id = $u->id;
        }

        $category = Category::findOrFail($request->get('category'));

        $models = $category->bookmarks()->orderBy('created_at', 'DESC')->get();

        // PUBLIC CATEGORIES ONLY
        $categories = Category::whereRaw('categories.user_id = ?', [$id])->whereRaw('categories.visibility LIKE \'public\'')->whereRaw('categories.category_status LIKE \'live\'')->orderBy('title')->get();

        $theTagArr = [];

        foreach ($models as $bookmark) {
            $theTags = $bookmark->Tags;
            foreach ($theTags as $thisTag) {
                $theTagArr[] = $thisTag->id;
            }
        }

        $catTags = Tag::whereIn('id', $theTagArr)->orderBy('title')->whereTagStatus('live')->get();

        $showImport = auth()->id() != $id;

        $category_tree = [];
        $category_names_tree = [];

        foreach ($categories as $cat) {
            $category_tree[$cat->id] = $cat->parent_category_id;
            $category_names_tree[$cat->id] = $cat->title;
        }

        $result = $this->bookmarkService->parseTree($category_tree, null);
        $this->bookmarkService->printSelectTree($result, $category_names_tree, $u->username);
        $treeData = $this->bookmarkService->getSelectData();

        $tagz = explode(',', $request->catTags);
        $tagz = array_diff($tagz, [0]);
        $tagz_string = implode(',', $tagz);

        return view('public.view', [
            'models' => $models,
            'title' => generateString(0),
            'url' => generateString(3),
            'categories' => $categories,
            'category_id' => $request->get('category'),
            'catz' => $category,
            'username' => $u->username,
            'user_id' => $id,
            'catTags' => $catTags,
            'tagz' => $tagz_string,
            'showImport' => $showImport,
            'treeData' => $treeData,
            'fullWidth' => true,
            'showSites' => false,
            'showFilter' => true,
        ]);

    }

    /**
     * Show the Bookmarks in this Category
     * FIRST Entry with Username, Category, NO TAGS
     *
     * @param string $username
     * @param int $category_id
     * @param string $tags
     * @return void
     */
    public function category(string $username, int $category_id)
    {
        $user = User::whereRaw('users.username = ?', [$username])->get();

        foreach ($user as $u) {
            $id = $u->id;
        }

        $category = Category::find($category_id);

        $models = $category->bookmarks()->orderBy('created_at', 'DESC')->get();

        // ONLY PUBLIC CATEGORIES
        $categories = Category::whereRaw('categories.user_id = ?', [$id])->whereRaw('categories.visibility LIKE \'public\'')->whereRaw('categories.category_status LIKE \'live\'')->orderBy('title')->get();

        $theTagArr = [];

        foreach ($models as $bookmark) {
            $theTags = $bookmark->Tags;
            foreach ($theTags as $thisTag) {
                $theTagArr[] = $thisTag->id;
            }
        }

        //These are the Tags IN the SELECTED Category
        $catTags = Tag::whereIn('id', $theTagArr)->orderBy('title')->whereTagStatus('live')->get();

        $showImport = auth()->id() != $id;

        $category_tree = [];
        $category_names_tree = [];

        // ONLY PUBLIC CATEGORIES
        foreach ($categories as $cat) {
            $category_tree[$cat->id] = $cat->parent_category_id;
            $category_names_tree[$cat->id] = $cat->title;
        }

        $result = $this->bookmarkService->parseTree($category_tree, null);
        $this->bookmarkService->printSelectTree($result, $category_names_tree, $username);
        $treeData = $this->bookmarkService->getSelectData();

        return view('public.view', [
            'models' => $models,
            'title' => generateString(0),
            'url' => generateString(3),
            'categories' => $categories,
            'category_id' => $category_id,
            'catz' => $category,
            'username' => $username,
            'user_id' => $id,
            'catTags' => $catTags,
            'showImport' => $showImport,
            'treeData' => $treeData,
            'fullWidth' => true,
            'tagz' => 0,
            'showSites' => false,
            'showFilter' => true,
            'tags' => [],
            'sites' => []
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function contact()
    {
        $uri = generateString(4);

        if ($uri === 'instructions') {
            return view('public.instructions', [
                'title' => 'Instructions',
            ]);
        }

        return view('public.contact', [
            'title' => 'Contact Us',
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $output = '';
        $tags = $request->tags;

        if ($request->ajax()) {
            if (empty($request->search)) {

                if (!empty($request->category_id)) {
                    $category = Category::findOrFail($request->category_id);
                    // Empty Search Query
                    if ($tags == 0) {
                        $bookmarks = $category->bookmarks()
                            ->whereUserId($request->user_id)
                            ->whereBookmarkStatus('live')
                            ->orderBy('created_at', 'DESC')->get();
                    } else {
                        $data['tags'] = $tags;
                        $data['search_query'] = '';
                        $data['category_id'] = $request->category_id;
                        $data['tags_count'] = count(explode(',', $tags));
                        $bookmarks = $this->tagService->fetchBookmarks(auth()->id(), $data);
                    }

                } else {
                    $bookmarks = Bookmark::whereRaw('bookmarks.user_id = ?', [$request->user_id])
                        ->whereRaw('bookmarks.visibility LIKE \'public\'')
                        ->whereRaw('bookmarks.bookmark_status LIKE \'live\'')
                        ->orderBy('created_at', 'DESC')->get();
                }

            } else {
                // We have a search query
                $data['search_query'] = $request->search;
                if ($tags != 0) {
                    $data['tags'] = $tags;
                }
                if (!empty($request->category_id)) {
                    $data['category_id'] = $request->category_id;
                }

                $data['status'] = ['live'];
                // We have a search query
                $data['tags_count'] = count(explode(',', $tags));
                $bookmarks = $this->tagService->fetchBookmarks($request->user_id, $data);
            }
        }

        if ($bookmarks) {
            $data['status'] = 'live';
            $output = $this->bookmarkService->getMediaData($bookmarks, $data);
        }

        return Response($output);
    }


}
