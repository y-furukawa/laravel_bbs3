<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //カテゴリーテーブルから見てポストテーブルは「1対多」なので、「hasMany」を設定します。
    public function posts()
    {
        // カテゴリは複数のポストを持つ
        return $this->hasMany('App\Post');
    }

    /**
     * カテゴリーの一覧を取得
     */
    public function getLists()
    {
        //検索時にpluckメソッドを使用することで、任意のキーのみを取得
        $categories = Category::orderBy('id','asc')->pluck('name', 'id');
    
        return $categories;
    }
}