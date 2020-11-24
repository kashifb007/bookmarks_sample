<?php

namespace App\Http\Controllers;

use App\Category;
use App\Services\BookmarkService;
use App\Services\CategoryService;
use App\Tag;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Validator;
use Illuminate\Support\Str;

class TagsController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $models = Tag::whereUserId(auth()->id())->whereTagStatus('live')->orderBy('title')->get();

        return view('tags.index', [
            'models' => $models,
            'title' => generateString(0),
            'create_text' => generateString(1),
            'create_route' => generateString(2),
            'fullWidth' => true
        ]);
    }

    public function showDeleted()
    {
        $models = Tag::whereUserId(auth()->id())->whereTagStatus('trash')->orderBy('title')->get();

        return view('tags.index', [
            'models' => $models,
            'title' => generateString(0),
            'create_text' => generateString(1),
            'create_route' => generateString(2),
            'fullWidth' => true
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::whereUserId(auth()->id())->pluck('title');
        $action = app('request')->route()->getAction();
        $controller = class_basename($action['controller']);

        $pageType = Str::ucfirst(explode('Controller@', $controller)[1]);
        $pageName = Str::singular(explode('Controller@', $controller)[0]);

        return view('tags.create', [
            'tags' => $tags,
            'title' => $pageType . ' ' . $pageName
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $messages = [
            'title.required' => 'The Category Title is required.',
            'title.min' => 'The Category Title must be at least 2 characters.',
            'title.max' => "The Category Title can't be more than 255 characters.",
        ];

        $data = $request->all();
        $data['title'] = strtolower($data['title']);
        $data['user_id'] = auth()->id();

        $validator = Validator::make($data,
            [
                'title' => 'required|min:2|max:255',
                'user_id' => 'integer'
            ],
            $messages
        );

        if ($validator->fails()) {
            return redirect('tags/create')
                ->withErrors($validator)
                ->withInput();
        }

        $tag_with_same_titles = Tag::where('user_id', auth()->id())
            ->whereRaw('LCASE(tags.title) LIKE CONCAT(\'%\', ?, \'%\')', [$request->get('title')])
            ->whereIn('tag_status', array('live'))
            ->get();

        $id = 0;

        foreach ($tag_with_same_titles as $tag_with_same_title) {
            $id = $tag_with_same_title->id;
        }

        if ($id > 0) {
            //now we have a condition where we are entering a title that already exists elsewhere in the table for this user id
            return redirect()->back()
                ->withInput()->withErrors(["The Tag title '" . request('title') . "' has already been saved before."]);
        }

        Tag::create($validator->validated());

        return redirect('/tags');
    }

    /**
     * Show the Bookmarks in this Category
     *
     * @param int $id
     * @return void
     */
    public function show($id)
    {
        $tag = Tag::find($id);
        $models = $tag->bookmarks()->whereBookmarkStatus('live')->orderBy('created_at', 'DESC')->get();

        $categories = $this->categoryService->fetchCategories(auth()->id(), ['live', 'base']);

        abort_if(\Gate::denies('update', $tag), 403);

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

        return view('tags.view', [
            'title' => generateString(0),
            'create_text' => generateString(1),
            'create_route' => generateString(2),
            'url' => generateString(3),
            'categories' => $categories,
            'tags' => $tags,
            'sites' => array_unique($sites),
            'theTag' => $tag,
            'treeData' => $treeData,
            'fullWidth' => true,
            'showSites' => true,
            'showFilter' => true
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Response
     */
    public function edit($id)
    {
        $tag = Tag::find($id);

        abort_if(\Gate::denies('update', $tag), 403);

        return view('tags.edit', [
            'tag' => $tag,
            'title' => generateString(0),
            'create_text' => generateString(1),
            'create_route' => generateString(2)
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
        $tag = Tag::find($id);

        abort_if(\Gate::denies('update', $tag), 403);

        $messages = [
            'title.required' => 'The Tag Title is required.',
            'title.min' => 'The Tag Title must be at least 2 characters.',
            'title.max' => "The Tag Title can't be more than 255 characters."
        ];

        $validator = Validator::make($request->all(),
            [
                'title' => 'bail|required|min:2|max:255'],
            $messages
        );

        $tag_with_same_title = Tag::where('user_id', auth()->id())->where('title', request('title'))->whereIn('tag_status', array('live'))->where('user_id', auth()->id())->get();

        foreach ($tag_with_same_title as $cate) {
            $catid = $cate->id;
        }

        if (isset($tag_with_same_title) && isset($catid)) {
            if ($catid != $id) {
                //now we have a condition where we are entering a title that already exists elsewhere in the table for this user id
                return redirect()->back()
                    ->withInput()->withErrors(["The Tag title '" . request('title') . "' has already been saved before."]);
            }
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()->withErrors($validator);
        } else {
            $tag->title = $request->title;
            $tag->save();
        }

        return redirect('/tags');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $tag = Tag::find($id);

        if (\Gate::denies('update', $tag)) {
            abort(403);
        } else {
            $tag->tag_status = 'trash';
            $tag->save();
        }

        return redirect('/tags');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $output = '';
        $tag_id = $request->tag_id;
        $tag = Tag::find($tag_id);

        if ($request->ajax()) {
            if (empty($request->search)) {
                $bookmarks = $tag->bookmarks()->whereUserId(auth()->id())->whereBookmarkStatus('live')->orderBy('created_at', 'DESC')->get();
            } else {
                $data['search_query'] = $request->search;
                $data['tag_id'] = $tag_id;
                $data['status'] = ['live'];
                $bookmarks = $this->bookmarkService->fetchBookmarks(auth()->id(), $data);
            }

            if ($bookmarks) {
                $data['status'] = 'live';
                $output = $this->bookmarkService->getMediaData($bookmarks, $data);;
            }
        }
        return Response($output);
    }

    /**
     * @param $id
     * @return Application|RedirectResponse|Redirector
     */
    public function restore($id)
    {
        $tag = Tag::find($id);

        if (\Gate::denies('update', $tag)) {
            abort(403);
        } else {
            $tag->tag_status = 'live';
            $tag->save();
        }

        return redirect('/tags');
    }

}
