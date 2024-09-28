<?php

use App\Models\Tweet;
use App\Models\User;


it('displays tweets', function () {
    $user = User::factory()->create();
    $tweets = Tweet::factory()->count(3)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get(route('tweets.index'));

    $response->assertOk();
    $response->assertViewIs('tweets.index');
    $response->assertViewHas('tweets', $tweets);
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

