<?php
//役割：model→データベースから、データを取ってくる
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //  $fillableで一気に保存しても大丈夫なものを指定
    // →2014_10_12_000000_create_users_table.php　のcreate()で作ってもセキュリティ的にOK
    protected $fillable = [
        'name', 'email', 'password',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function microposts() //１対多
    {
        return $this->hasMany(Micropost::class);
    }
    
    public function followings()//多対多
    { //$this＝User (1得られるモデルクラス, 2中間テーブル名, 3中間テーブルの自分のidのカラム名, 4中間テーブルの関係先idカラム名)
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }

    public function followers()//多対多
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    public function follow($userId)
    {
        // 既にフォローしているかの確認
        $exist = $this->is_following($userId);
        // 相手が自分自身ではないかの確認
        $its_me = $this->id == $userId;
    
        if ($exist || $its_me) {
            // 既にフォローしていれば何もしない
            return false;
        } else {
            // 未フォローであればフォローする
            $this->followings()->attach($userId);
            return true;
        }
    }
    
    public function unfollow($userId)
    {
        // 既にフォローしているかの確認
        $exist = $this->is_following($userId);
        // 相手が自分自身ではないかの確認
        $its_me = $this->id == $userId;
    
        if ($exist && !$its_me) {
            // 既にフォローしていればフォローを外す
            $this->followings()->detach($userId);
            return true;
            // 念のためconsoleする
        } else {
            // 未フォローであれば何もしない
            return false;
        }
    }
    
    public function is_following($userId)
    {
        //$userIdに、followings()の中の、$userIdの中の、follow_idを探し、置く
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    
    public function feed_microposts()
    {   
        // フォローしているUserのidの配列を取得　pluck()引数のテーブルのカラム名だけを抜き出す　toArray()通常の配列に変換
        $follow_user_ids = $this->followings()->pluck('users.id')->toArray();
        //　その中に自分のidも追加
        $follow_user_ids[] = $this->id;
        //microposts テーブルの user_idカラムで $follow_user_ids の中の 自分のidを含むもの全てを取得して return（全てのユーザツイートを表示するため）
        return Micropost::whereIn('user_id', $follow_user_ids);
    }
    
    
    //お気に入り一覧
    public function favorites()
    {//得られる Model クラス？  ←中間テーブル名で　多対多　の関係を示します　須田
        return $this->belongsToMany(Micropost::class, 'favorites', 'user_id', 'micropost_id')->withTimestamps();
    }
    
    //お気に入りに追加
    public function favorite($micropostId)
    {
        // 既にお気に入りかの確認
        $exist = $this->is_favorite($micropostId);
        // お気に入りが自分のものではないか確認(必要なし)
    
        if ($exist) {
            // 既にお気に入りしていれば何もしない
            return false;
        } else {
            // 未お気に入りあればお気に入りにする
            $this->favorites()->attach($micropostId);
            return true;
        }
    }
    
    //お気に入りから削除
    public function unfavorite($micropostId)
    {
        // 既にお気に入りかの確認
        $exist = $this->is_favorite($micropostId);
        // お気に入りが自分のものではないか確認（必要なし）
    
        if ($exist) {
            // 既にお気に入りにいればお気に入りを外す
            $this->favorites()->detach($micropostId);
            return true;
        } else {
            // 未お気に入りであれば何もしない
            return false;
        }
    }
    
    public function is_favorite($micropostId)
    {
        return $this->favorites()->where('micropost_id', $micropostId)->exists();
    }
}
