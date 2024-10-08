<!-- resources/views/tweets/edit.blade.php -->

<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('投稿編集') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <a href="{{ route('tweets.show', $tweet) }}" class="text-blue-500 hover:text-blue-700 mr-2">詳細に戻る</a>
          <form method="POST" action="{{ route('tweets.update', $tweet) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
              <label for="article" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">記事タイトル</label>
              <input type="text" name="article" id="article" value="{{ $tweet->article }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
              <label for="article_url" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">記事のURL</label>
              <input type="url" name="article_url" id="article_url" value="{{ $tweet->article_url }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
              <label for="tweet" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">投稿内容</label>
              <textarea name="tweet" id="tweet" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ $tweet->tweet }}</textarea>
              @error('tweet')
              <span class="text-red-500 text-xs italic">{{ $message }}</span>
              @enderror
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">更新する</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>