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

Route::get('/', 'MicropostsController@index');

// ユーザ登録
Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('signup.get');
Route::post('signup', 'Auth\RegisterController@register')->name('signup.post');

// ログイン認証
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login.post');
Route::get('logout', 'Auth\LoginController@logout')->name('logout.get');

// ユーザ機能 ユーザ一覧　と　詳細　はログインしてから
// グループで回す　ログインしているかを確認　OKなら、function () 以降
Route::group(['middleware' => ['auth']], function () {
    Route::resource('users', 'UsersController', ['only' => ['index', 'show']]);
    
    //['prefix' => 'users/{id}']URLに追加する。例）/users/{id}/follow
    Route::group(['prefix' => 'users/{id}'], function () {
        //フォローする
        Route::post('follow', 'UserFollowController@store')->name('user.follow');
        //アンフォローする
        Route::delete('unfollow', 'UserFollowController@destroy')->name('user.unfollow');
        //フォローの一覧を表示する
        Route::get('followings', 'UsersController@followings')->name('users.followings');
        //フォロワーの一覧を表示する
        Route::get('followers', 'UsersController@followers')->name('users.followers');
        Route::get('favorites', 'UsersController@favorites')->name('users.favorites');    // 追加
    });
    
    // 追加
    Route::group(['prefix' => 'microposts/{id}'], function () {
        Route::post('favorite', 'FavoritesController@store')->name('favorites.favorite');
        Route::delete('unfavorite', 'FavoritesController@destroy')->name('favorites.unfavorite');
    });
    
    Route::resource('microposts', 'MicropostsController', ['only' => ['store', 'destroy']]);
});
// 'users'というURLを'UsersController'で'index', 'show'だけを見せる
