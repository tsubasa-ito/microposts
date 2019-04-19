<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    //storeメソッドの中でUser.phpの中で定義したfollowメソッドを使って、ユーザーをフォローできるように
    public function store(Request $request, $id)
    {
        \Auth::user()->favorite($id);
        return back();
    }
    //destroyメソッドの中でUser.phpの中で定義したunfollowメソッドを使って、ユーザーをアンフォローできるように
    public function destroy($id)
    {
        \Auth::user()->unfavorite($id);
        return back();
    }
}