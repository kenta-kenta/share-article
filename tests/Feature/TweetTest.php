<?php

use App\Models\Tweet;
use App\Models\User;


it('displays tweets', function () {
    $user = User::factory()->create();
    $tweets = Tweet::factory()->count(3)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get(route('tweets.index'));

    $response->assertOk();
    $response->assertViewIs('tweets.index');
    $response->assertViewHas('tweets');
});

it('displays the create tweet form', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('tweets.create'));

    $response->assertOk();
    $response->assertViewIs('tweets.create');
});

it('creates a tweet', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('tweets.store'), [
        'tweet' => 'My first tweet',
        'article' => 'My first article',
        'article_url' => 'https://example.com',
    ]);

    $response->assertRedirect(route('tweets.index'));
    $this->assertDatabaseHas('tweets', [
        'tweet' => 'My first tweet',
        'article' => 'My first article',
        'article_url' => 'https://example.com',
    ]);
});

it('displays a tweet', function () {
    $user = User::factory()->create();
    $tweet = Tweet::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get(route('tweets.show', $tweet));

    $response->assertOk();
    $response->assertViewIs('tweets.show');
    $response->assertViewHas('tweet', $tweet);
});

it('displays the edit tweet form', function () {
    $user = User::factory()->create();
    $tweet = Tweet::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get(route('tweets.edit', $tweet));

    $response->assertOk();
    $response->assertViewIs('tweets.edit');
    $response->assertViewHas('tweet', $tweet);
});

it('updates a tweet', function () {
    $user = User::factory()->create();
    $tweet = Tweet::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->patch(route('tweets.update', $tweet), [
        'tweet' => 'My updated tweet',
        'article' => 'My updated article',
        'article_url' => 'https://example.com/updated',
    ]);

    $this->assertDatabaseHas('tweets', [
        'tweet' => 'My updated tweet',
        'article' => 'My updated article',
        'article_url' => 'https://example.com/updated',
    ]);

    $response->assertRedirect("tweets/{$tweet->id}");
});

it('deletes a tweet', function () {
    $user = User::factory()->create();
    $tweet = Tweet::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->delete(route('tweets.destroy', $tweet));

    $response->assertRedirect(route('tweets.index'));
    $this->assertDatabaseMissing('tweets', ['id' => $tweet->id]);
});

it('can search tweets by content keyword', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
  
    // キーワードを含むツイートを作成
    Tweet::factory()->create([
      'tweet' => 'This is a test tweet',
      'user_id' => $user->id,
    ]);
  
    // キーワードを含まないツイートを作成
    Tweet::factory()->create([
      'tweet' => 'This is another tweet',
      'user_id' => $user->id,
    ]);
  
    // キーワード "test" で検索
    $response = $this->get(route('tweets.search', ['keyword' => 'test']));
  
    $response->assertStatus(200);
    $response->assertSee('This is a test tweet');
    $response->assertDontSee('This is another tweet');
  });
  
  it('shows no tweets if no match found', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
  
    Tweet::factory()->create([
      'tweet' => 'This is a tweet',
      'user_id' => $user->id,
    ]);
  
    // 存在しないキーワードで検索
    $response = $this->get(route('tweets.search', ['keyword' => 'nonexistent']));
  
    $response->assertStatus(200);
    $response->assertDontSee('This is a tweet');
    $response->assertSee('No tweets found.');
  });
  
  