<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function() {
    Route::resource('categories', 'CategoriesController');
});

Route::get('/categories/{category}/{tag}/show', [
    'middleware' => 'auth',
    'uses' => 'CategoriesController@show'
]);

Route::post('/categories/showTags', [
    'middleware' => 'auth',
    'uses' => 'CategoriesController@showTags'
])->name('show_cat_tags');

Route::get('/tags/{tag}/show', [
    'middleware' => 'auth',
    'uses' => 'TagsController@show'
]);

Route::get('/sites/{site}/show', [
    'middleware' => 'auth',
    'uses' => 'SitesController@show'
]);

Route::get('/categories/{category}/delete', [
    'middleware' => 'auth',
    'uses' => 'CategoriesController@destroy'
]);

Route::get('/bookmarks/{bookmark}/delete', [
    'middleware' => 'auth',
    'uses' => 'BookmarksController@destroy'
]);

Route::get('/bookmarks_trash', [
    'middleware' => 'auth',
    'uses' => 'BookmarksController@showDeleted'
])->name('bookmarks_trash');

Route::get('/categories_trash', [
    'middleware' => 'auth',
    'uses' => 'CategoriesController@showDeleted'
])->name('categories_trash');

Route::get('/tags_trash', [
    'middleware' => 'auth',
    'uses' => 'TagsController@showDeleted'
])->name('tags_trash');

Route::get('/tags/{tag}/delete', [
    'middleware' => 'auth',
    'uses' => 'TagsController@destroy'
]);

Route::get('/bookmarks/{bookmark}/restore', [
    'middleware' => 'auth',
    'uses' => 'BookmarksController@restore'
]);

Route::get('/categories/{category}/restore', [
    'middleware' => 'auth',
    'uses' => 'CategoriesController@restore'
]);

Route::get('/tags/{tag}/restore', [
    'middleware' => 'auth',
    'uses' => 'TagsController@restore'
]);

Route::group(['middleware' => 'auth'], function() {
    Route::resource('bookmarks', 'BookmarksController');
});

Route::group(['middleware' => 'auth'], function() {
    Route::resource('tags', 'TagsController');
});

Route::group(['middleware' => 'auth'], function() {
    Route::resource('sites', 'SitesController');
});

Route::group(['middleware' => 'auth'], function() {
    Route::resource('links', 'LinksController');
});

Route::get('/search', [
    'middleware' => 'auth',
    'uses' => 'BookmarksController@search'
]);

Route::get('/catsearch', [
    'middleware' => 'auth',
    'uses' => 'CategoriesController@search'
]);

Route::get('/tagsearch', [
    'middleware' => 'auth',
    'uses' => 'TagsController@search'
]);

Route::get('/sitesearch', [
    'middleware' => 'auth',
    'uses' => 'SitesController@search'
]);

Route::post('/public/showTags', [
    'middleware' => 'web',
    'uses' => 'PublicController@showTags'
])->name('show_public_cat_tags');

Route::get('/public/{username}/', [
    'middleware' => 'web',
    'uses' => 'PublicController@index'
]);

Route::get('/public/{username}/{category_id}/{tag_id}', [
    'middleware' => 'web',
    'uses' => 'PublicController@category'
]);

Route::get('/publicsearch', [
    'middleware' => 'web',
    'uses' => 'PublicController@search'
]);

Route::get('/contact-us', [
    'middleware' => 'web',
    'uses' => 'PublicController@contact'
])->name('contact-us');

Route::get('/instructions', [
    'middleware' => 'web',
    'uses' => 'PublicController@contact'
])->name('instructions');

Route::post('/categories/copy_category', [
    'middleware' => 'auth',
    'uses' => 'CategoriesController@copyCategory'
])->name('copy_category');

Route::get('/admin/users', [
    'middleware' => 'auth',
    'uses' => 'UsersController@index'
])->name('admin_users');

Route::get('/saveimages', [
    'middleware' => 'web',
    'uses' => 'ImageController@saveImages'
]);