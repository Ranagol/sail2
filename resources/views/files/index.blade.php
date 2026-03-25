<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('File storage demo with AWS S3') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ showDeleteModal: false, deleteAction: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('success'))
                <div class="rounded-md bg-green-100 p-4 text-green-700 dark:bg-green-900/40 dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-md bg-red-100 p-4 text-red-700 dark:bg-red-900/40 dark:text-red-300">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Upload File</h3>
                    <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
                        @csrf

                        <div>
                            <label for="file" class="mb-2 block text-sm font-medium">Select file</label>
                            <input
                                id="file"
                                name="file"
                                type="file"
                                required
                                class="block w-full rounded-md border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                            >
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Maximum size: 5 MB</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <button
                                type="submit"
                                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
                            >
                                Upload
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($files->isEmpty())
                        <p class="text-sm text-gray-600 dark:text-gray-300">No files uploaded yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-700 text-left">
                                        <th class="px-3 py-2">Name</th>
                                        <th class="px-3 py-2">Size</th>
                                        <th class="px-3 py-2">Uploaded</th>
                                        <th class="px-3 py-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($files as $file)
                                        <tr class="border-b border-gray-100 dark:border-gray-700/60">
                                            <td class="px-3 py-2">{{ $file->original_name }}</td>
                                            <td class="px-3 py-2">
                                                @if ($file->size)
                                                    {{ number_format($file->size / 1024, 2) }} KB
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-3 py-2">{{ optional($file->created_at)->format('Y-m-d H:i') }}</td>
                                            <td class="px-3 py-2">
                                                <div class="flex items-center gap-3">
                                                    <a
                                                        href="{{ route('files.download', $file->id) }}"
                                                        class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400"
                                                    >
                                                        Download
                                                    </a>

                                                    <button
                                                        type="button"
                                                        @click="deleteAction = '{{ route('files.destroy', $file->id) }}'; showDeleteModal = true"
                                                        class="text-red-600 hover:text-red-500 dark:text-red-400"
                                                    >
                                                        Delete
                                                    </button>
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

            <div
                x-show="showDeleteModal"
                x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
                @click.self="showDeleteModal = false"
            >
                <div class="w-[22rem] max-w-[calc(100vw-2rem)] rounded-xl border border-amber-200 bg-amber-50 p-6 shadow-xl dark:border-amber-700/60 dark:bg-gray-800">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Delete File</h3>
                    <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Are you sure you want to delete this file? This cannot be undone.</p>

                    <form x-bind:action="deleteAction" method="POST" class="mt-5 flex items-center justify-end gap-3">
                        @csrf
                        @method('DELETE')
                        <button
                            @click="showDeleteModal = false"
                            type="button"
                            class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-500"
                        >
                            Delete File
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-8 text-center">
                <a href="/" class="inline-flex items-center gap-2 rounded-md border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
