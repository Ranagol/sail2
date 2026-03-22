<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Post Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
                    <h3 class="text-2xl font-semibold">{{ $post->title }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Created: {{ optional($post->created_at)->format('Y-m-d H:i') }}</p>
                    <div class="whitespace-pre-line text-sm leading-6">{{ $post->content }}</div>

                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('posts.edit', $post) }}" class="inline-flex items-center rounded-md bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-500">
                            Edit
                        </a>
                        <a href="{{ route('posts.index') }}" class="text-sm text-gray-700 underline dark:text-gray-300">Back to posts</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
