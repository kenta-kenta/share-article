<?php

use App\Models\Tweet;
use App\Models\User;

it('allows a user to like a tweet', function () {
    $user = User::factory()->create();
    $tweet = Tweet::factory()->create();
    $this->actingAs($user)->post(route('tweets.like', $tweet));
    $this->assertDatabaseHas('tweet_user', [
        'tweet_id' => $tweet->id,
        'user_id' => $user->id,
    ]);
});

it('allows a user to dislike a tweet', function () {
    $user = User::factory()->create();
    $tweet = Tweet::factory()->create();
    $tweet->liked()->attach($user->id);
    $this->actingAs($user)->delete(route('tweets.dislike', $tweet));
    $this->assertDatabaseMissing('tweet_user', [
        'tweet_id' => $tweet->id,
        'user_id' => $user->id,
    ]);
});


