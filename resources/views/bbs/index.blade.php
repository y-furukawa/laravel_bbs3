@extends('layout.bbslayout')

@section('title', 'LaravelPjt BBS 投稿の一覧ページ')
@section('keywords', 'キーワード1,キーワード2,キーワード3')
@section('description', '投稿一覧ページの説明文')
@section('pageCss')
<link href="/css/bbs/style.css" rel="stylesheet">
@endsection

@include('layout.bbsheader')

@section('content')
<div class="mt-4 mb-4">
    <a href="{{ route('bbs.create') }}" class="btn btn-primary">
        投稿の新規作成
    </a>
</div>

<!-- 検索フォーム -->
<div class="mt-4 mb-4">
    <form class="form-inline" method="GET" action="{{ route('bbs.index') }}">
        <div class="form-group">
            <input type="text" name="searchword" value="{{$searchword}}" class="form-control" placeholder="名前を入力してください">
        </div>
        <input type="submit" value="検索" class="btn btn-info ml-2">
    </form>
</div>

<!-- 何件の投稿が表示されるのかをカウントするメッセージも付ける -->
<div class="mt-4 mb-4">
    <p>{{ $posts->total() }}件が見つかりました。</p>
</div>
<div class="mt-4 mb-4">
    @foreach($categories as $id => $name)
    <span class="btn"><a href="{{ route('bbs.index', ['category_id'=>$id]) }}"
            title="{{ $name }}">{{ $name }}</a></span>
    @endforeach
</div>
@if (session('poststatus'))
<div class="alert alert-success mt-4 mb-4">
    {{ session('poststatus') }}
</div>
@endif
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>カテゴリ</th>
                <th>作成日時</th>
                <th>名前</th>
                <th>件名</th>
                <th>メッセージ</th>
                <th>処理</th>
            </tr>
        </thead>
        <tbody id="tbl">
            <!-- foreachでデータをループして表示。postー＞***の形式で、表示させたい項目を指定。 -->
            <!-- 「カテゴリ」はモデルにてリレーション設定が行われているので、postー＞categoryー＞nameで取得（表示）可能。 -->
            <!-- メッセージは改行に対応しつつ80文字で省略されるようにしており、コメント数はpostー＞commentsー＞count()で取得できる。 -->
            @foreach ($posts as $post)
            <tr>

                <td>{{ $post->id }}</td>
                <td>{{ $post->category->name }}</td>
                <td>{{ $post->created_at->format('Y.m.d') }}</td>
                <td>{{ $post->name }}</td>
                <td>{{ $post->subject }}</td>
                <td>{!! nl2br(e(Str::limit($post->message, 100))) !!}
                    @if ($post->comments->count() >= 1)
                    <p><span class="badge badge-primary">コメント：{{ $post->comments->count() }}件</span></p>
                    @endif
                </td>
                <td class="text-nowrap">
                    <p><a href="{{ action('PostsController@show', $post->id) }}" class="btn btn-primary btn-sm">詳細</a>
                    </p>
                    <p><a href="{{ action('PostsController@edit', $post->id) }}" class="btn btn-info btn-sm">編集</a></p>

                    <p>
                    <form method="POST" action="{{ action('PostsController@destroy', $post->id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">削除</button>
                    </form>
                    </p>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <!-- ページネーションを表示 -->
    <!-- カテゴリーのを指定しながらページ送りが出来るよう修正 -->
    <div class="d-flex justify-content-center mb-5">
        {{ $posts->appends(['category_id' => $category_id,'searchword' => $searchword])->links() }}
    </div>
</div>
@endsection

@include('layout.bbsfooter')