<?php

use App\Models\Comment;
use App\Models\Tweet;
use App\Models\User;

// コメントが表示できることを確認するテスト
it('displays the comment creation form', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $tweet = $user->tweets()->create(Tweet::factory()->raw());

    $response = $this->get(route('tweets.comments.create', $tweet));
    $response->assertStatus(200);
    $response->assertViewIs('tweets.comments.create');
    $response->assertViewHas('tweet', $tweet);
});

// コメントが作成できることを確認するテスト
it('creates a comment', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $tweet = $user->tweets()->create(Tweet::factory()->raw());
    $commentData = ['comment' => 'store test comment'];

    $response = $this->post(route('tweets.comments.store', $tweet), $commentData);
    $response->assertRedirect(route('tweets.show', $tweet));
    $this->assertDatabaseHas('comments', [
        'comment' => $commentData['comment'],
        'tweet_id' => $tweet->id,
        'user_id' => $user->id,
    ]);
});

// コメントの詳細画面に表示できることを確認するテスト
it('displays the comment', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $tweet = $user->tweets()->create(Tweet::factory()->raw());
    $comment = $tweet->comments()->create(Comment::factory()->raw(['user_id' => $user->id]));

    $response = $this->get(route('tweets.comments.show', [$tweet, $comment]));
    $response->assertStatus(200);
    $response->assertViewIs('tweets.comments.show');
    $response->assertViewHas('tweet', $tweet);
    $response->assertViewHas('comment', $comment);
});

// コメントが編集できることを確認するテスト
it('displays the comment edit form', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $tweet = $user->tweets()->create(Tweet::factory()->raw());
    $comment = $tweet->comments()->create(Comment::factory()->raw(['user_id' => $user->id]));

    $response = $this->get(route('tweets.comments.edit', [$tweet, $comment]));
    $response->assertStatus(200);
    $response->assertViewIs('tweets.comments.edit');
    $response->assertViewHas('tweet', $tweet);
    $response->assertViewHas('comment', $comment);
});

// コメントが更新できることを確認するテスト
it('updates a comment', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $tweet = $user->tweets()->create(Tweet::factory()->raw());
    $comment = $tweet->comments()->create(Comment::factory()->raw(['user_id' => $user->id]));
    $updatedData = ['comment' => 'update test comment'];

    $response = $this->put(route('tweets.comments.update', [$tweet, $comment]), $updatedData);
    $response->assertRedirect(route('tweets.comments.show', [$tweet, $comment]));
    $this->assertDatabaseHas('comments', [
        'id' => $comment->id,
        'comment' => $updatedData['comment'],
    ]);
});

// コメントが削除できることを確認するテスト
it('deletes a comment', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $tweet = $user->tweets()->create(Tweet::factory()->raw());
    $comment = $tweet->comments()->create(Comment::factory()->raw(['user_id' => $user->id]));

    $response = $this->delete(route('tweets.comments.destroy', [$tweet, $comment]));
    $response->assertRedirect(route('tweets.show', $tweet));
    $this->assertDatabaseMissing('comments', [
        'id' => $comment->id,
    ]);
});
