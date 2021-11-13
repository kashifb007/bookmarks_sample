<?php

namespace App\Http\Controllers;

use App\Bookmark;
use App\Category;
use App\Services\CategoryService;
use App\Tag;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use App\Services\BookmarkService;
use Validator;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BookmarksController
 * @package App\Http\Controllers
 * @author Kashif <kash@dreamsites.co.uk>
 * @created 22/09/2020
 */
class BookmarksController extends Controller
{

    /**
     * @var BookmarkService
     */
    private $bookmarkService;

    /**
     * @var CategoryService
     */
    private $categoryService;

    public function __construct(BookmarkService $bookmarkService, CategoryService $categoryService)
    {
        $this->bookmarkService = $bookmarkService;
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|Response
     */
    public function index()
    {
        $data['status'] = ['live'];
        $models = $this->bookmarkService->fetchBookmarks(auth()->id(), $data);
        $categories = $this->categoryService->fetchCategories(auth()->id(), array('live', 'base'));
        $tags = Tag::whereUserId(auth()->id())->whereTagStatus('live')->orderBy('title')->get();

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

        return view('bookmarks.index', [
            'title' => generateString(0),
            'categories' => $categories,
            'tags' => $tags,
            'sites' => array_unique($sites),
            'treeData' => $treeData,
            'fullWidth' => true,
            'showSites' => true,
            'showFilter' => true
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $output = '';
        $data['status'] = $request->show_trash == 1 ? ['trash'] : ['live'];

        if ($request->ajax()) {
            if (empty($request->search)) {
                $bookmarks = $this->bookmarkService->fetchBookmarks(auth()->id(), $data);
            } else {
                $data['search_query'] = $request->search;
                $bookmarks = $this->bookmarkService->fetchBookmarks(auth()->id(), $data);
            }

            if ($bookmarks) {
                $output = $this->bookmarkService->getMediaData($bookmarks, $data);
            }
        }
        return Response($output);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|Response
     */
    public function showDeleted()
    {
        $models = Bookmark::whereUserId(auth()->id())->whereBookmarkStatus('trash')->orderBy('created_at', 'DESC')->get();
        $categories = $this->categoryService->fetchCategories(auth()->id(), array('live', 'base'));
        $tags = Tag::whereUserId(auth()->id())->whereTagStatus('live')->orderBy('title')->get();

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

        return view('bookmarks.index', [
            'models' => $models,
            'title' => generateString(0),
            'create_text' => generateString(1),
            'create_route' => generateString(2),
            'url' => generateString(3),
            'categories' => $categories,
            'tags' => $tags,
            'sites' => array_unique($sites),
            'treeData' => $treeData,
            'fullWidth' => true
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $categories = $this->categoryService->fetchCategories(auth()->id(), array('live', 'base'));
        $tags = Tag::whereUserId(auth()->id())->whereTagStatus('live')->orderBy('title')->get();

        $category_tree = [];
        $category_names_tree = [];

        foreach ($categories as $category) {
            $category_tree[$category->id] = $category->parent_category_id;
            $category_names_tree[$category->id] = $category->title;
        }

        $result = $this->bookmarkService->parseTree($category_tree, null);
        $this->bookmarkService->printSelectTree($result, $category_names_tree, '', true);
        $treeData = $this->bookmarkService->getSelectData();

        return view('bookmarks.create', [
            'title' => generateString(0),
            'create_text' => generateString(1),
            'create_route' => generateString(2),
            'categoriesCount' => $categories->count(),
            'categories' => $categories,
            'tagsCount' => $tags->count(),
            'tags' => $tags,
            'treeData' => $treeData
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $data = $request->all();
        if (!filter_var($request->get('url'), FILTER_VALIDATE_URL)) {
            unset($data['url']);
        }

        if (isset($data['categories'])) {
            $cat_array = explode(',', $data['categories']);
            unset($data['categories']);
            $data['categories'] = [];
            foreach ($cat_array as $cat_arr) {
                $data['categories'][] = $cat_arr;
            }
        } else {
            $data['categories'] = [];
        }

        if (isset($data['tags'])) {
            $tag_array = explode(',', $data['tags']);
            unset($data['tags']);
            $data['tags'] = [];
            foreach ($tag_array as $tag_arr) {
                $data['tags'][] = $tag_arr;
            }
        } else {
            $data['tags'] = [];
        }

        if (!empty($request->get('the_category'))) {
            $newCategory = Category::create([
                'title' => $request->get('the_category'),
                'user_id' => auth()->id()
            ]);
            $data['categories'][] = $newCategory->id;
        }

        if (!empty($request->get('the_tag'))) {
            $newTag = Tag::create([
                'title' => $request->get('the_tag'),
                'user_id' => auth()->id()
            ]);
            $data['tags'][] = $newTag->id;
        }

        $messages = [
            'url.required' => 'A valid URL is required.',
            'url.min' => 'URL must be at least 5 characters.',
            'sort_order.numeric' => 'Sort order must be numeric.',
        ];

        $validData = [
            'url' => 'required|string|min:5|max:2000',
            'sort_order' => 'nullable|numeric',
            'categories' => 'array',
            'tags' => 'array',
            'visibility' => 'string'
        ];

        if (!empty($request->get('title'))) {
            $data['title'] = $request->get('title');
            $validData['title'] = 'string|min:1|max:2000';
        }

        if (!empty($request->get('description'))) {
            $data['description'] = $request->get('description');
            $validData['description'] = 'string|min:1|max:2000';
        }

        $validator = Validator::make($data, $validData, $messages);

        if ($validator->fails()) {
            return redirect('bookmarks/create')
                ->withErrors($validator)
                ->withInput();
        }

        $bookmark = $this->bookmarkService->updateBookmark($validator->validated(), true);

        if ($bookmark instanceof Bookmark) {
            return redirect('/bookmarks');
        } else {
            return redirect()->route('bookmarks.create')
                ->withErrors($bookmark)
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $bookmark = Bookmark::find($id);
        abort_if(\Gate::denies('update', $bookmark), 403);
        $categories = $this->categoryService->fetchCategories(auth()->id(), array('live', 'base'));
        $tags = Tag::whereUserId(auth()->id())->whereTagStatus('live')->orderBy('title')->get();

        $selectedCats = [];

        foreach ($bookmark->Categories as $cat) {
            $selectedCats[] = $cat->id;
        }

        $selectedCats = implode(',', $selectedCats);

        $selectedTags = [];

        foreach ($bookmark->Tags as $tag) {
            $selectedTags[] = $tag->id;
        }

        $selectedTags = implode(',', $selectedTags);

        return view('bookmarks.edit', [
            'bookmark' => $bookmark,
            'title' => generateString(0),
            'create_text' => generateString(1),
            'create_route' => generateString(2),
            'categoriesCount' => $categories->count(),
            'categories' => $categories,
            'tagsCount' => $tags->count(),
            'tags' => $tags,
            'selectedCategories' => $bookmark->Categories,
            'selectedTags' => $bookmark->Tags,
            'selCats' => $selectedCats,
            'selTags' => $selectedTags
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $bookmark = Bookmark::find($id);

        abort_if(\Gate::denies('update', $bookmark), 403);

        $data = $request->all();

        if (isset($data['categories'])) {
            $cat_array = explode(',', $data['categories']);
            unset($data['categories']);
            $data['categories'] = [];
            foreach ($cat_array as $cat_arr) {
                $data['categories'][] = $cat_arr;
            }
        } else {
            $data['categories'] = [];
        }

        if (isset($data['tags'])) {
            $tag_array = explode(',', $data['tags']);
            unset($data['tags']);
            $data['tags'] = [];
            foreach ($tag_array as $tag_arr) {
                $data['tags'][] = $tag_arr;
            }
        } else {
            $data['tags'] = [];
        }

        if (!filter_var($request->get('url'), FILTER_VALIDATE_URL)) {
            unset($data['url']);
        }

        $data['id'] = $id;

        $messages = [
            'title.min' => 'Title must be at least 3 characters.',
            'title.max' => "Title can't be more than 2000 characters.",
            'url.required' => 'URL is required.',
            'url.min' => 'URL must be at least 5 characters.',
            'sort_order.numeric' => 'Sort order must be numeric.',
        ];

        $validData = [
            'url' => 'required|string|min:5|max:2000',
            'sort_order' => 'nullable|numeric',
            'categories' => 'array',
            'tags' => 'array',
            'visibility' => 'string',
            'id' => 'integer'
        ];

        if (!empty($request->get('title'))) {
            $data['title'] = $request->get('title');
            $validData['title'] = 'string|min:1|max:512';
        }

        if (!empty($request->get('description'))) {
            $data['description'] = $request->get('description');
            $validData['description'] = 'string|min:1|max:2000';
        }

        if (!empty($request->get('the_category'))) {
            $newCategory = Category::create([
                'title' => $request->get('the_category'),
                'user_id' => auth()->id()
            ]);
            $data['categories'][] = $newCategory->id;
        }

        if (!empty($request->get('the_tag'))) {
            $newTag = Tag::create([
                'title' => $request->get('the_tag'),
                'user_id' => auth()->id()
            ]);
            $data['tags'][] = $newTag->id;
        }

        $validator = Validator::make($data, $validData, $messages);

        if ($validator->fails()) {
            return redirect('bookmarks/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        }

        $bookmark = $this->bookmarkService->updateBookmark($validator->validated(), false);

        if ($bookmark instanceof Bookmark) {
            return redirect('/bookmarks');
        } else {
            return redirect()->back()
                ->withInput()->withErrors($bookmark);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $bookmark = Bookmark::find($id);

        if (\Gate::denies('update', $bookmark)) {
            abort(403);
        } else {
            $bookmark->bookmark_status = 'trash';
            $bookmark->save();
        }

        return redirect('/bookmarks');
    }

    public function restore($id)
    {
        $bookmark = Bookmark::find($id);

        if (\Gate::denies('update', $bookmark)) {
            abort(403);
        } else {
            $bookmark->bookmark_status = 'live';
            $bookmark->save();
        }

        return redirect('/bookmarks');
    }

    public function searchVue(Request $request)
    {
        $data['status'] = ['live'];

        $bookmarks = $this->bookmarkService->fetchBookmarks(1, $data);

            $count = 0;
            if ($bookmarks) {
                $show_trash = $data['status'] === ['trash'] ? 'trash' : 'live';
                $output = [];
                foreach ($bookmarks as $bookmark) {
                    $site_logo = strip_tags($bookmark->link->site->logo);
                    $output[$count]['id'] = $bookmark->id;
                    $output[$count]['bookmark_id'] = $bookmark->id;
                    $output[$count]['site_logo'] = $site_logo;
                    $output[$count]['image'] = strip_tags($bookmark->link->og_image) ?? strip_tags($site_logo);
                    $output[$count]['alt_text'] = strip_tags($bookmark->link->site->rootLink->title);
                    $output[$count]['created_at'] = date_format($bookmark->created_at, "d-M-Y H:i");
                    $og_title = strip_tags($bookmark->link->og_title) ?? strip_tags($bookmark->link->title);
                    $output[$count]['og_title'] = $og_title;
                    $og_desc = strip_tags($bookmark->link->og_description) ?? strip_tags($bookmark->link->meta_description);
                    $output[$count]['og_desc'] = $og_desc;
                    $your_title = strip_tags($bookmark->title) ?? strip_tags($bookmark->link->og_title);
                    $output[$count]['your_title'] = $your_title;
                    $your_desc = strip_tags($bookmark->description) ?? strip_tags($bookmark->link->meta_description);
                    $output[$count]['your_desc'] = $your_desc;
                    $output[$count]['url'] = strip_tags($bookmark->link->url);
                    $your_title = preg_replace('/[[:^print:]]/', '', $your_title);
                    $your_desc = preg_replace('/[[:^print:]]/', '', $your_desc);
                    $og_title = preg_replace('/[[:^print:]]/', '', $og_title);
                    $og_desc = preg_replace('/[[:^print:]]/', '', $og_desc);
                    $output[$count]['short_title'] = strlen($your_title) > 55 ? substr($your_title, 0, 55) . '...' : $your_title;
                    $output[$count]['short_desc'] = strlen($your_desc) > 90 ? substr($your_desc, 0, 90) . '...' : $your_desc;
                    $output[$count]['short_og_title'] = strlen($og_title) > 55 ? substr($og_title, 0, 55) . '...' : $og_title;
                    $output[$count]['short_og_desc'] = strlen($og_desc) > 90 ? substr($og_desc, 0, 90) . '...' : $og_desc;
//                    $output[$count]['short_title'] = '';
//                    $output[$count]['short_desc'] = '';
//                    $output[$count]['short_og_title'] = '';
//                    $output[$count]['short_og_desc'] = '';
                    $output[$count]['edit_url'] = '/bookmarks';
                    $count++;
                }
            }
        return response()->json($output, Response::HTTP_OK);
    }
}
