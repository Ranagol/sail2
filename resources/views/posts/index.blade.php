<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('success'))
                <div class="rounded-md bg-green-100 p-4 text-green-700 dark:bg-green-900/40 dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex flex-wrap items-center justify-end gap-3">
                <form method="POST" action="{{ route('posts.demo.create') }}">
                    @csrf
                    <button
                        type="submit"
                        class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500"
                    >
                        Create 10 Demo Posts
                    </button>
                </form>
                <a
                    href="{{ route('posts.create') }}"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
                >
                    Create Post
                </a>
                <form method="POST" action="{{ route('posts.destroy-all') }}" onsubmit="return confirm('Delete all your posts? This cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500"
                    >
                        Delete All Posts
                    </button>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($posts->isEmpty())
                        <p class="text-sm text-gray-600 dark:text-gray-300">You have no posts yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-700 text-left">
                                        <th class="px-3 py-2">Title</th>
                                        <th class="px-3 py-2">Created</th>
                                        <th class="px-3 py-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($posts as $post)
                                        <tr class="border-b border-gray-100 dark:border-gray-700/60">
                                            <td class="px-3 py-2">{{ $post->title }}</td>
                                            <td class="px-3 py-2">{{ optional($post->created_at)->format('Y-m-d H:i') }}</td>
                                            <td class="px-3 py-2">
                                                <div class="flex items-center gap-3">
                                                    <a href="{{ route('posts.show', $post) }}" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
                                                        View
                                                    </a>
                                                    <a href="{{ route('posts.edit', $post) }}" class="text-amber-600 hover:text-amber-500 dark:text-amber-400">
                                                        Edit
                                                    </a>
                                                    <form method="POST" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('Delete this post?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-500 dark:text-red-400">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
