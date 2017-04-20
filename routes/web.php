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

/**
 * 前台
 */

Route::group(['namespace' => 'App'], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/post/{flag}', 'HomeController@posts')->name('post');
    Route::get('/tags/{flag}', 'HomeController@tags')->name('tags');
    Route::get('/category/{flag}', 'HomeController@category')->name('category');
    Route::get('/feed', 'HomeController@feed');
    Route::get('/sitemap.xml', 'HomeController@siteMap');
    Route::get('/xmlrpc', 'XmlRpcController@errorMessage');
    Route::post('/xmlrpc', 'XmlRpcController@index')->name('xmlrpc');
    Route::get('/debug', 'HomeController@debug')->name('debug');
    Route::resource('/comment', 'CommentController');
    Route::get('/google', 'ServicesController@index')->name('index');
});

/**
 * 后台
 */

Route::group(['prefix' => 'myp', 'namespace' => 'Backend'], function () {
    Route::get('/', 'DashboardController@dashboard')->name('admin');
    Route::post('/auth/check', 'AuthController@check')->name('admin.login_check');
    Route::post('/auth/logout', 'AuthController@logout')->name('admin.logout');
    Route::post('/auth/login', 'AuthController@authenticate')->name('admin.login');
});

Route::group(['prefix' => 'myp', 'middleware' => 'auth', 'namespace' => 'Backend'], function () {
    Route::get('/dashboard/meta', 'DashboardController@meta');
    Route::resource('/categorys', 'CategorysController');
    Route::resource('/posts', 'PostsController');
    Route::resource('/tags', 'TagsController');
    Route::resource('/links', 'LinksController');
    Route::resource('/options', 'OptionsController');
    Route::resource('/settings', 'SettingsController');
    Route::resource('/navigations', 'NavigationController');
    Route::resource('/uploads', 'FileController');
    Route::resource('/util', 'UtilController');
    Route::resource('/user', 'UserController');
    Route::resource('/comments', 'CommentController');
    Route::resource('/trash', 'TrashController');
});


Route::group(['prefix' => 'thrids', 'namespace' => 'ThridService'], function () {
    Route::get('/shanbay', 'ShanbayController@login')->name('shanbay');
    Route::get('/jxoa', 'JxoaController@index')->name('jxoa');

});


