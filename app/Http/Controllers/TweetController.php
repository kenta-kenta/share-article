<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class TweetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tweets = Tweet::with('user')->latest()->paginate(10);
        return view('tweets.index', compact('tweets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tweets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tweet' => 'required',
            'article' => 'required|max:255',
            'article_url' => 'required',
        ]);

        $tweet = new Tweet();
        $tweet->tweet = $request->input('tweet');
        $tweet->article = $request->input('article');
        $tweet->article_url = $request->input('article_url');
        $tweet->user_id = Auth::id();

        if ($request->input('is_story')) {
            $tweet->is_story = true;
            $tweet->expires_at = Carbon::now()->addMinute();  // 24時間後に有効期限を設定
        } else {
            // 通常の投稿の場合
            $tweet->is_story = false;
            $tweet->expires_at = null;
        }

        $tweet->save();

        return redirect()->route('tweets.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tweet $tweet)
    {
        $tweet->load('comments');
        return view('tweets.show', compact('tweet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tweet $tweet)
    {
        return view('tweets.edit', compact('tweet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tweet $tweet)
    {
        $request->validate([
            'tweet' => 'required',
            'article' => 'required|max:255',
            'article_url' => 'required',
        ]);

        $tweet->update($request->only(['article', 'article_url', 'tweet']));

        return redirect()->route('tweets.show', $tweet);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tweet $tweet)
    {
        $tweet->delete();

        return redirect()->route('tweets.index');
    }

    public function search(Request $request)
    {
        $query = Tweet::query();

        // キーワードが指定されている場合のみ検索を実行
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('tweet', 'like', '%' . $keyword . '%');
        }

        // ページネーションを追加(1ページに10件表示)
        $tweets = $query
            ->latest()
            ->paginate(10);

        return view('tweets.search', compact('tweets'));
    }

    public function scopeOlderThanOneHour(Builder $query)
    {
        return $query->where('created_at', '<', now()->subHour());
    }
}
