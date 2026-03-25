<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }} — AWS Demo Overview</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">

        @include('layouts.navigation')

        <main class="mx-auto max-w-5xl px-6 py-12">

            {{-- Hero --}}
            <div class="mb-10 text-center">
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100 sm:text-4xl">
                    Laravel AWS Demo
                </h1>

                <p class="mt-3 text-base text-slate-500 dark:text-slate-400">
                    A Laravel application deployed on AWS.<br>
                    Demonstrating sessions, caching, queues, storage, and database integration.
                </p>

                <p class="mt-2 text-xs text-slate-400">
                    Application hosted on EC2 within a VPC
                </p>
            </div>

            {{-- Demo cards --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                {{-- Session --}}
                <a href="{{ route('session.demo') }}" class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                    <div class="bg-gradient-to-r from-cyan-500 via-sky-500 to-blue-600 px-6 py-5">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </span>
                        <h2 class="mt-3 text-lg font-bold text-white">Session Demo - AWS ElastiCache/Redis testing</h2>
                    </div>
                    <div class="flex flex-1 flex-col px-6 py-5">
                        <p class="text-sm text-slate-600 dark:text-slate-300">
                            Write, read, and delete a value in Laravel's server-side session.
                        </p>
                        <p class="mt-3 text-xs text-slate-400">
                            Backed by AWS ElastiCache (Redis)
                        </p>
                    </div>
                </a>

                {{-- Redis Cache --}}
                <a href="{{ route('redis.demo') }}" class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                    <div class="bg-gradient-to-r from-rose-500 via-orange-500 to-amber-500 px-6 py-5">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </span>
                        <h2 class="mt-3 text-lg font-bold text-white">Redis Cache Demo - AWS ElastiCache/Redis testing</h2>
                    </div>
                    <div class="flex flex-1 flex-col px-6 py-5">
                        <p class="text-sm text-slate-600 dark:text-slate-300">
                            Compare cold MySQL queries with cached Redis responses and observe performance gains.
                        </p>
                        <p class="mt-3 text-xs text-slate-400">
                            Powered by AWS ElastiCache (Redis)
                        </p>
                    </div>
                </a>

                {{-- Queue --}}
                <a href="{{ route('queue.demo') }}" class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                    <div class="bg-gradient-to-r from-violet-500 via-purple-500 to-indigo-600 px-6 py-5">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </span>
                        <h2 class="mt-3 text-lg font-bold text-white">Queue Demo - AWS ElastiCache/Redis testing</h2>
                    </div>
                    <div class="flex flex-1 flex-col px-6 py-5">
                        <p class="text-sm text-slate-600 dark:text-slate-300">
                            Dispatch jobs and process them.
                        </p>
                        <p class="mt-3 text-xs text-slate-400">
                            Queue driver: AWS ElastiCache (Redis)
                        </p>
                    </div>
                </a>

                {{-- File Upload --}}
                <a href="{{ route('files.index') }}" class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                    <div class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-600 px-6 py-5">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                        </span>
                        <h2 class="mt-3 text-lg font-bold text-white">File Upload Demo - AWS S3 testing</h2>
                    </div>
                    <div class="flex flex-1 flex-col px-6 py-5">
                        <p class="text-sm text-slate-600 dark:text-slate-300">
                            Upload, list, download, and delete files using cloud storage.
                        </p>
                        <p class="mt-3 text-xs text-slate-400">
                            Stored in AWS S3
                        </p>
                    </div>
                </a>

                {{-- Database --}}
                <a href="{{ route('posts.index') }}" class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                    <div class="bg-gradient-to-r from-fuchsia-500 via-pink-500 to-rose-600 px-6 py-5">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16"/>
                            </svg>
                        </span>
                        <h2 class="mt-3 text-lg font-bold text-white">Database Demo - AWS RDS (MySQL) testing</h2>
                    </div>
                    <div class="flex flex-1 flex-col px-6 py-5">
                        <p class="text-sm text-slate-600 dark:text-slate-300">
                            Perform CRUD operations on posts stored in a relational database.
                        </p>
                        <p class="mt-3 text-xs text-slate-400">
                            Database: AWS RDS (MySQL)
                        </p>
                    </div>
                </a>

            </div>
        </main>
    </body>
</html>
