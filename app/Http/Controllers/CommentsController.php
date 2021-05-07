<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest; //追加
use App\Comment; //追加

class CommentsController extends Controller
{
    /**
     * バリデーション、登録データの整形など
     */
    public function store(CommentRequest $request)
    {
        $savedata = [
            'post_id' => $request->post_id,
            'name' => $request->name,
            'comment' => $request->comment,
        ];
        //バリデーションを通過してきた内容を savedata 配列で整形し、
        //fillメソッドを使用してホワイトリストを利用し、saveメソッドでデータベースに保存。
        $comment = new Comment;
        $comment->fill($savedata)->save();

        //DB登録後は、コメント投稿をした詳細画面にリダイレクト
        return redirect()->route('bbs.show', [$savedata['post_id']])->with('commentstatus', 'コメントを投稿しました');
    }
}