<?php

namespace App\Http\Controllers;

use App\Bookmark;
use App\Category;
use App\RootLink;
use App\Services\BookmarkService;
use App\Site;
use App\Tag;
use Illuminate\Http\Request;

class SitesController extends Controller
{

    /**
     * @var BookmarkService
     */
    private $bookmarkService;

    public function __construct(BookmarkService $bookmarkService)
    {
        $this->bookmarkService = $bookmarkService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $output = '';
        $site_id = $request->site_id;

        if ($request->ajax()) {
            if (empty($request->search)) {
                $bookmarks = Bookmark::where(function ($query) use ($site_id) {
                    $query->whereUserId(auth()->id())->whereBookmarkStatus('live')->whereHas('Link', function ($q) use ($site_id) {
                        $q->whereSiteId($site_id);
                    });
                })->orderBy('created_at', 'DESC')->get();
            } else {
                $data['site_id'] = $site_id;
                $data['search_query'] = $request->search;
                $data['status'] = ['live'];
                $bookmarks = $this->bookmarkService->fetchBookmarks(auth()->id(), $data);
            }

            if ($bookmarks) {
                $data['status'] = 'live';
                $output = $this->bookmarkService->getMediaData($bookmarks, $data);
            }
        }
        return Response($output);
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Show the Bookmarks in this Category
     *
     * @param int $id
     * @return void
     */
    public function show($id)
    {
        $site = Site::find($id);

        $models = Bookmark::where(function ($query) use ($id) {
            $query->whereUserId(auth()->id())->whereBookmarkStatus('live')->whereHas('Link', function ($q) use ($id) {
                $q->whereSiteId($id);
            });
        })->orderBy('created_at', 'DESC')->get();

        $categories = Category::whereUserId(auth()->id())->orderBy('title')->get();

        $tags = Tag::whereUserId(auth()->id())->orderBy('title')->get();

        $sites = [];

        foreach ($models as $model) {
            $sites[] = $model->link->site;
        }

        $category_tree = [];
        $category_names_tree = [];

        foreach ($categories as $category) {
            $category_tree[$category->id] = $category->parent_category_id;
            $category_names_tree[$category->id] = $category->title;
        }

        $result = $this->bookmarkService->parseTree($category_tree, null);
        $this->bookmarkService->printSelectTree($result, $category_names_tree);
        $treeData = $this->bookmarkService->getSelectData();

        return view('sites.view', [
            'title' => generateString(0),
            'create_text' => generateString(1),
            'create_route' => generateString(2),
            'url' => generateString(3),
            'categories' => $categories,
            'tags' => $tags,
            'site' => $site,
            'sites' => array_unique($sites),
            'treeData' => $treeData,
            'fullWidth' => true,
            'showSites' => true,
            'showFilter' => true
        ]);
    }
}
