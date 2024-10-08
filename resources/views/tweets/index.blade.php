<!-- resources/views/tweets/index.blade.php -->

<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('投稿一覧') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <div class="mb-4">
            {{ $tweets->appends(request()->input())->links() }}
          </div>
          @foreach ($tweets as $tweet)
          @if ($tweet->is_story)
          <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
            <a href="{{ $tweet->article_url }}" class="text-gray-600 underline hover:underline hover:text-gray-500 dark:text-gray-400">{{ $tweet->article }}</a>
            <p class="text-gray-800 dark:text-gray-300">{{ $tweet->tweet }}</p>
            <a href="{{ route('profile.show', $tweet->user) }}" class="text-gray-600 dark:text-gray-400 text-sm hover:text-gray-500 inline">投稿者: {{ $tweet->user->name }}</a>
            <br>
            <a href="{{ route('tweets.show', $tweet) }}" class="text-green-500 hover:text-green-700">この投稿の詳細を見る</a>
            <div class="flex">
              @if ($tweet->liked->contains(auth()->id()))
              <form action="{{ route('tweets.dislike', $tweet) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-700">dislike {{$tweet->liked->count()}}</button>
              </form>
              @else
              <form action="{{ route('tweets.like', $tweet) }}" method="POST">
                @csrf
                <button type="submit" class="text-blue-500 hover:text-blue-700">like {{$tweet->liked->count()}}</button>
              </form>
              @endif
            </div>
          </div>
          @else
          <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
            <a href="{{ $tweet->article_url }}" class="text-gray-600 underline hover:underline hover:text-gray-500 dark:text-gray-400">{{ $tweet->article }}</a>
            <p class="text-gray-800 dark:text-gray-300">{{ $tweet->tweet }}</p>
            <a href="{{ route('profile.show', $tweet->user) }}" class="text-gray-600 dark:text-gray-400 text-sm hover:text-gray-500 inline">投稿者: {{ $tweet->user->name }}</a>
            <br>
            <a href="{{ route('tweets.show', $tweet) }}" class="text-blue-500 hover:text-blue-700">この投稿の詳細を見る</a>
            <div class="flex">
              @if ($tweet->liked->contains(auth()->id()))
              <form action="{{ route('tweets.dislike', $tweet) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-700">dislike {{$tweet->liked->count()}}</button>
              </form>
              @else
              <form action="{{ route('tweets.like', $tweet) }}" method="POST">
                @csrf
                <button type="submit" class="text-blue-500 hover:text-blue-700">like {{$tweet->liked->count()}}</button>
              </form>
              @endif
            </div>
          </div>
          @endif
          @endforeach
          <div class="mt-4">
            {{ $tweets->appends(request()->input())->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>

</x-app-layout>