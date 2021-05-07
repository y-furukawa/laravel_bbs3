<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post; // 追記
use App\Category;  // カテゴリーの有力にセレクトボックスを使う為に追記
use App\Http\Requests\PostRequest; // フォームリクエスト用追記

class PostsController extends Controller
{


    public function index(Request $request)
{
    // カテゴリ取得
    $category = new Category;
    $categories = $category->getLists();

    $searchword = $request->searchword;  //名前検索用
 
    $category_id = $request->category_id;

        //投稿を作成日時の降順で取得し index.blade にデータを渡してビューを生成する
        // $posts = Post::orderBy('created_at', 'desc')->paginate(10);   //ページ送りが出るようにgetからpaginateに修正
        
        //withの中に記述している comments が複数形なのは、post対commentは「1対多」だから
        $posts = Post::with(['comments', 'category'])         // 検索用に上記から書き換え
        ->orderBy('created_at', 'desc')
        ->categoryAt($category_id)
        ->fuzzyNameMessage($searchword)  // ←名前検索用に追加
        ->paginate(10);


 
    return view('bbs.index', [
        'posts' => $posts, 
        'categories' => $categories, 
        'category_id'=>$category_id,
        'searchword' => $searchword // ←名前検索用に追加
    ]);

}

    /**
     * 詳細画面用showメソッド
     */
    public function show(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        return view('bbs.show', [
            'post' => $post,
        ]);
    }

    /**
     * 投稿フォーム
     */
    //createメソッドは画面を表示するだけの内容
    //prependメソッドで、作成した配列の先頭に任意の項目（選択というテキスト）を先頭に追加。
    public function create()
    {
        $category = new Category;
        $categories = $category->getLists()->prepend('選択', '');
     
        return view('bbs.create', ['categories' => $categories]);
    }


    /**
     * バリデーション、登録データの整形など
     */
    //storeメソッドはバリデーションを通過してきた内容を$savedata配列で整形し、fillメソッドを使用してホワイトリストを利用し、saveメソッドでデータベースに保存
    public function store(PostRequest $request)
    {
        $savedata = [
            'name' => $request->name,
            'subject' => $request->subject,
            'message' => $request->message,
            'category_id' => $request->category_id,
        ];

        $post = new Post;
        $post->fill($savedata)->save();

        return redirect('/bbs')->with('poststatus', '新規投稿しました');
    }

    /**
     * 編集フォーム画面
     */
    public function edit($id)
    {
        //投稿編集用画面にもカテゴリーのプルダウンを追加
        $category = new Category;
        $categories = $category->getLists();
     
        $post = Post::findOrFail($id);
        return view('bbs.edit', ['post' => $post, 'categories' => $categories]);
    }

    /**
     * 編集実行
     */
    public function update(PostRequest $request)
    {
        $savedata = [
            'name' => $request->name,
            'subject' => $request->subject,
            'message' => $request->message,
            'category_id' => $request->category_id,
        ];

        $post = new Post;
        $post->fill($savedata)->save();

        return redirect('/bbs')->with('poststatus', '投稿を編集しました');
    }

    /**
     * 物理削除
     */
    public function destroy($id)
    {
    $post = Post::findOrFail($id);
    
    $post->comments()->delete(); // ←コメント削除実行
    $post->delete();  // ←投稿削除実行
    
    return redirect('/bbs')->with('poststatus', '投稿を削除しました');
    }
}