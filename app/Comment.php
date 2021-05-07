<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //コメントテーブルから見てポストテーブルは「所属元」なので、「belongsTo」を設定します。
    public function post()
    {
        // コメントは1つの投稿に所属する
        return $this->belongsTo('App\Post');
    }

    // 割り当て許可
    protected $fillable = [
        'post_id',
        'name',
        'comment',
    ];
}