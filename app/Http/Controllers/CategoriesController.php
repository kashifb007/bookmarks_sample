<?php

namespace App\Http\Controllers;

use App\Category;
use App\Services\BookmarkService;
use App\Services\CategoryService;
use App\Services\TagService;
use App\Tag;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Validator;

class CategoriesController extends Controller
{

    /**
     * @var BookmarkService
     */
    private $bookmarkService;

    /**
     * @var CategoryService
     */
    private $categoryService;

    /**
     * @var TagService
     */
    private $tagService;

    /**
     * CategoriesController constructor.
     * @param BookmarkService $bookmarkService
     * @param CategoryService $categoryService
     * @param TagService $tagService
     */
    public function __construct
    (
        BookmarkService $bookmarkService,
        CategoryService $categoryService,
        TagService $tagService
    )
    {
        $this->bookmarkService = $bookmarkService;
        $this->categoryService = $categoryService;
        $this->tagService = $tagService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|Response|View
     */
    public function index()
    {
        $models = $this->categoryService->fetchCategories(auth()->id(), array('live', 'base'));
        $user = \Auth::user();

        return view('categories.index', [
            'models' => $models,
            'title' => generateString(0),
            'create_text' => generateString(1),
            'create_route' => generateString(2),
            'is_admin' => $user->UserType->name === 'Admin',
            'url' => generateString(3),
            'fullWidth' => true
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $output = '';
        $category_id = $request->category_id;
        $category = Category::find($category_id);

        $tags = $request->get('tagz');

        $data['status'] = ['live'];

        if ($request->ajax()) {
            if (empty($request->search)) {
                // Empty Search Query
                if ($tags == 0) {
                    $bookmarks = $category->bookmarks()->whereUserId(auth()->id())
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
                $data['search_query'] = $request->search;
                if ($tags == 0) {
                    $data['category_id'] = $request->category_id;
                } else {
                    $data['category_id'] = $request->category_id;
                    $data['tags'] = $tags;
                }

                // We have a search query
                $data['tags_count'] = count(explode(',', $tags));
                $bookmarks = $this->tagService->fetchBookmarks(auth()->id(), $data);
            }

            if ($bookmarks) {
                $output = $this->bookmarkService->getMediaData($bookmarks, $data);
            }
        }
        return Response($output);
    }

    public function showDeleted()
    {
        $models = Category::whereUserId(auth()->id())->whereCategoryStatus('trash')->orderBy('title')->get();
        $user = \Auth::user();

        return view('categories.index', [
            'models' => $models,
            'title' => generateString(0),
            'create_text' => generateString(1),
            'create_route' => generateString(2),
            'is_admin' => $user->UserType->name === 'Admin',
            'url' => generateString(3),
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
        $models = Category::whereUserId(auth()->id())->whereCategoryStatus('live')->orderBy('title')->get();

        return view('categories.create', [
            'models' => $models,
            'title' => generateString(0),
            'create_text' => generateString(1),
            'create_route' => generateString(2),
            'count' => $models->count()
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function store(Request $request)
    {
        $messages = [
            'title.required' => 'The Category Title is required.',
            'title.min' => 'The Category Title must be at least 2 characters.',
            'title.max' => "The Category Title can't be more than 255 characters.",
        ];

        $validator = Validator::make($request->all(),
            [
                'title' => 'required|min:2|max:255',
                'parent_category_id' => 'numeric',
                'visibility' => 'string|min:2|max:20',
            ],
            $messages
        );

        if ($validator->fails()) {
            return redirect('categories/create')
                ->withErrors($validator)
                ->withInput();
        }

        $category_with_same_titles = Category::where('user_id', auth()->id())
            ->whereRaw('LCASE(categories.title) LIKE CONCAT(\'%\', ?, \'%\')', [$request->get('title')])
            ->whereIn('category_status', array('live', 'base'))
            ->get();

        $id = 0;

        foreach ($category_with_same_titles as $category_with_same_title) {
            $id = $category_with_same_title->id;
        }

        if ($id > 0) {
            //now we have a condition where we are entering a title that already exists elsewhere in the table for this user id
            return redirect()->back()
                ->withInput()->withErrors(["The Category title '" . request('title') . "' has already been saved before."]);
        }

        $data = $validator->validated();
        $data['user_id'] = auth()->id();
        $data['parent_category_id'] = $data['parent_category_id'] > 0 ? $data['parent_category_id'] : null;

        Category::create([
            'title' => request('title'),
            'description' => request('description'),
            'parent_category_id' => request('parent_category_id') > 0 ? request('parent_category_id') : null,
            'user_id' => auth()->id()
        ]);

        return redirect('/categories');
    }

//    protected function getProductsAsArray($case)
//    {
//        $products = [];
//        for ($i = 1; $i <= 6; $i++) {
//            $code = $case["product_{$i}_code"];
//            $qty = $case["product_{$i}_qty"];
//            if ($qty) {
//                $products[$code] = $qty;
//            }
//        }
//
//        return $products;
//    }

    public function copyCategory(Request $request)
    {


//            $query = 'SELECT
//                        COUNT(c.`id`) AS counter FROM categories c
//                        WHERE LCASE(c.`title`)
//                            LIKE LCASE(:search_title)
//                            AND c.`category_status` LIKE \'live\'
//                            AND c.`user_id` = :user_id;';
//
//            $case = select_one($query, [
//                'case_number' => $this->getFilter('case_number'),
//            ]);
//
//            $case['products'] = $this->getProductsAsArray($case);

        // get the pulled in category details
        $categories = Category::whereRaw('id = ?', [$request->get('category_id')])->whereRaw('visibility LIKE \'public\'')->get();

        // check if this category exists in your account
        $oldCategoryId = DB::table('categories')->whereRaw('user_id = ?', [auth()->id()])->whereRaw('LCASE(`title`) LIKE ?', [strtolower($request->get('category_title'))])->whereRaw('category_status LIKE \'live\'')->value('id');

        if (empty($oldCategoryId)) {

            foreach ($categories as $cat) {
                $id = $cat->id;
                $title = $cat->title;
                $desc = $cat->description;
            }

            try {

                DB::beginTransaction();
                $newCategory = Category::create([
                    'title' => $title,
                    'description' => $desc,
                    'user_id' => auth()->id()
                ]);
            } catch (\Exception $e) {
                //log error and return
                DB::rollBack();
                return [$e->getMessage()];
            }
            $oldCategoryId = $newCategory->id;
        } else {
            $id = $request->get('category_id');
        }

        $category = Category::findOrFail($id);

        $bookmarks = $category->bookmarks()->get();

        $theTagArr = [];

        foreach ($bookmarks as $bookmark) {

            //these are the tags for the bookmark you are importing
            $theTags = $bookmark->Tags;

            // check if each tag exists in this users account
            foreach ($theTags as $thisTag) {

                if ($thisTag->tag_status === 'live') {
                    $newTags = Tag::whereRaw('LCASE(`title`) LIKE ?', [strtolower($thisTag->title)])->whereRaw('user_id = ?', [auth()->id()])->whereRaw('tag_status LIKE \'live\'')->get();
                }
                $oldTagFound = false;

                if (isset($newTags)) {
                    foreach ($newTags as $newTag) {
                        $oldTagFound = true;
                        //add this tag id to be associated with this bookmark
                        $theTagArr[$bookmark->id][] = $newTag->id;
                    }
                }

                if (!$oldTagFound) {
                    $newTag = Tag::create([
                        'title' => $thisTag->title,
                        'user_id' => auth()->id()
                    ]);
                    $theTagArr[$bookmark->id][] = $newTag->id;
                }

            }

            if (isset($theTagArr[$bookmark->id])) {
                $theTagArr[$bookmark->id] = array_unique($theTagArr[$bookmark->id]);
            } else {
                $theTagArr[$bookmark->id] = null;
            }

        }

        foreach ($bookmarks as $bookmark) {

            $data = [
                'title' => $bookmark->title,
                'description' => $bookmark->description,
                'url' => $bookmark->link->url,
                'categories' => $oldCategoryId,
                'tags' => $theTagArr[$bookmark->id]
            ];

            try {

                $id = 0;

                //check if we have this bookmark already before trying to create it
                $oldBookmark = DB::table('bookmarks')
                    ->join('links', 'bookmarks.link_id', '=', 'links.id')
                    ->select('bookmarks.id')
                    ->whereRaw('links.url LIKE ?', [$bookmark->link->url])
                    ->whereRaw('bookmarks.user_id = ?', [auth()->id()])
                    ->whereRaw('bookmarks.bookmark_status = \'live\'')
                    ->get();

                foreach ($oldBookmark as $oldBookmar) {
                    $id = $oldBookmar->id;
                }

                if ($id === 0) {
                    $bookmark = $this->bookmarkService->updateBookmark($data, true);
                } else {
                    $data['id'] = $id;
                    $bookmark = $this->bookmarkService->updateBookmark($data, false);
                }

            } catch (\Exception $e) {
                //log error and return
                DB::rollBack();
                return [$e->getMessage()];
            }
        }
        DB::commit();
        return redirect('/categories');
    }

    /**
     * Show the Bookmarks in this Category
     *
     * @param int $id
     * @return void
     */
    public function show(Request $request)
    {
        $category = Category::find($request->category);
        $models = $category->bookmarks()->whereBookmarkStatus('live')->get();
        $categories = $this->categoryService->fetchCategories(auth()->id(), ['live', 'base']);
        $tags = Tag::whereUserId(auth()->id())->whereTagStatus('live')->orderBy('title')->get();

        abort_if(\Gate::denies('update', $category), 403);

        $sites = [];

        foreach ($models as $model) {
            $sites[] = $model->link->site;
        }

        $theTagArr = [];

        foreach ($models as $bookmark) {
            $theTags = $bookmark->Tags;
            foreach ($theTags as $thisTag) {
                $theTagArr[] = $thisTag->id;
            }
        }

        $catTags = Tag::whereIn('id', $theTagArr)->orderBy('title')->whereTagStatus('live')->get();

        $category_tree = [];
        $category_names_tree = [];

        foreach ($categories as $cat) {
            $category_tree[$cat->id] = $cat->parent_category_id;
            $category_names_tree[$cat->id] = $cat->title;
        }

        $result = $this->bookmarkService->parseTree($category_tree, null);
        $this->bookmarkService->printSelectTree($result, $category_names_tree);
        $treeData = $this->bookmarkService->getSelectData();

        $selCats = 0;

        return view('categories.view', [
            'title' => generateString(0),
            'create_text' => generateString(1),
            'create_route' => generateString(2),
            'url' => generateString(3),
            'categories' => $categories,
            'tags' => $tags,
            'sites' => array_unique($sites),
            'category_id' => $request->category,
            'catz' => $category,
            'catTags' => $catTags,
            'tag_id' => $request->tag,
            'treeData' => $treeData,
            'fullWidth' => true,
            'selCats' => $selCats,
            'tagz' => 0,
            'showSites' => true,
            'showFilter' => true
        ]);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function showTags(Request $request)
    {

        $category = Category::find($request->category);
        $models = $category->bookmarks()->whereBookmarkStatus('live')->get();
        $categories = $this->categoryService->fetchCategories(auth()->id(), ['live', 'base']);
        $tags = Tag::whereUserId(auth()->id())->whereTagStatus('live')->orderBy('title')->get();

        abort_if(\Gate::denies('update', $category), 403);

        $sites = [];

        foreach ($models as $model) {
            $sites[] = $model->link->site;
        }

        $theTagArr = [];

        foreach ($models as $bookmark) {
            $theTags = $bookmark->Tags;
            foreach ($theTags as $thisTag) {
                $theTagArr[] = $thisTag->id;
            }
        }

        $catTags = Tag::whereIn('id', $theTagArr)->orderBy('title')->whereTagStatus('live')->get();

        $category_tree = [];
        $category_names_tree = [];

        foreach ($categories as $cat) {
            $category_tree[$cat->id] = $cat->parent_category_id;
            $category_names_tree[$cat->id] = $cat->title;
        }

        $result = $this->bookmarkService->parseTree($category_tree, null);
        $this->bookmarkService->printSelectTree($result, $category_names_tree);
        $treeData = $this->bookmarkService->getSelectData();

        $selCats = 0;

        $tagz = explode(',', $request->get('catTags'));
        $tagz = array_diff($tagz, [0]);
        $tagz_string = implode(',', $tagz);

        return view('categories.view', [
            'title' => generateString(0),
            'create_text' => generateString(1),
            'create_route' => generateString(2),
            'url' => generateString(3),
            'categories' => $categories,
            'tags' => $tags,
            'sites' => array_unique($sites),
            'category_id' => $request->category,
            'catz' => $category,
            'catTags' => $catTags,
            'tagz' => $tagz_string,
            'treeData' => $treeData,
            'fullWidth' => true,
            'selCats' => $selCats,
            'showSites' => true,
            'showFilter' => true
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $category = Category::find($id);

        abort_if(\Gate::denies('update', $category), 403);

        $categories = $this->categoryService->fetchCategories(auth()->id(), ['live', 'base']);

        $user = \Auth::user();

        return view('categories.edit', [
            'cat' => $category,
            'categories' => $categories,
            'title' => generateString(0),
            'create_text' => generateString(1),
            'create_route' => generateString(2),
            'count' => $categories->count(),
            'is_admin' => $user->UserType->name === 'Admin'
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
        $category = Category::find($id);

        abort_if(\Gate::denies('update', $category), 403);

        //$categories = Category::where('user_id', auth()->id())->get();
        $category_with_same_title = Category::where('user_id', auth()->id())->where('title', request('title'))->whereIn('category_status', array('live', 'base'))->where('user_id', auth()->id())->get();

        foreach ($category_with_same_title as $cate) {
            $catid = $cate->id;
        }

        //     $categories_amount = $categories->count();

        if (isset($category_with_same_title) && isset($catid)) {
            if ($catid != $id) {
                //now we have a condition where we are entering a title that already exists elsewhere in the table for this user id
                return redirect()->back()
                    ->withInput()->withErrors(["The Category title '" . request('title') . "' has already been saved before."]);
//                return view('categories.edit')->withCat($category)->withCategories($categories)->withCount($categories_amount)
//                    ->withErrors("The Category title '" . request('title') . "' has already been saved before.");
            }
        }

        $messages = [
            'title.required' => 'The Category Title is required.',
            'title.min' => 'The Category Title must be at least 2 characters.',
            'title.max' => "The Category Title can't be more than 255 characters."
        ];

        $validator = Validator::make($request->all(),
            [
                'title' => 'bail|required|min:2|max:255',
                'parent_category_id' => 'nullable|numeric'],
            $messages
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()->withErrors($validator);
        } else {
            $category->title = $request->title;
            $category->description = $request->description;
            $category->visibility = $request->visibility;
            $category->category_status = isset($request->category_status) ? $request->category_status : $category->category_status;
            $category->parent_category_id = request('parent_category_id') > 0 ? request('parent_category_id') : null;

            $category->save();
        }

        return redirect('/categories');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public
    function destroy($id)
    {
        $category = Category::find($id);

        if (\Gate::denies('update', $category)) {
            abort(403);
        } elseif ($category->category_status !== 'base') {
            $category->category_status = 'trash';
            $category->save();
        }

        return redirect('/categories');
    }

    /**
     * @param $id
     * @return Application|RedirectResponse|Redirector
     */
    public
    function restore($id)
    {
        $category = Category::find($id);

        if (\Gate::denies('update', $category)) {
            abort(403);
        } elseif ($category->category_status !== 'base') {
            $category->category_status = 'live';
            $category->save();
        }

        return redirect('/categories');
    }
}
