<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>AWS Architecture Overview</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">

@include('layouts.navigation')

<main class="mx-auto max-w-6xl px-6 py-12">

    {{-- Header --}}
    <div class="text-center mb-14">
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
            AWS Architecture Overview
        </h1>

        <p class="mt-4 mb-6 text-slate-500 dark:text-slate-400 max-w-2xl mx-auto">
            This application is deployed on AWS using a modular architecture.
            Each service has a clear responsibility, ensuring scalability, performance, and maintainability.
        </p>
    </div>



    {{-- Services --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        {{-- EC2 --}}
        <div class="group p-6 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition">
            <div class="flex items-center gap-3 mb-3">
                <span class="text-xl">🖥️</span>
                <h2 class="font-bold text-lg">EC2 (Application Layer)</h2>
            </div>
            <p class="text-sm text-slate-600 dark:text-slate-300">
                Hosts the Laravel application and handles all incoming HTTP requests.
                Acts as the central orchestrator for all AWS services.
            </p>
        </div>

        {{-- RDS --}}
        <div class="group p-6 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition">
            <div class="flex items-center gap-3 mb-3">
                <span class="text-xl">🗄️</span>
                <h2 class="font-bold text-lg">RDS (MySQL)</h2>
            </div>
            <p class="text-sm text-slate-600 dark:text-slate-300">
                Stores persistent relational data such as posts and application records.
                Managed service ensures backups and reliability.
            </p>
        </div>

        {{-- Redis --}}
        <div class="group p-6 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition">
            <div class="flex items-center gap-3 mb-3">
                <span class="text-xl">⚡</span>
                <h2 class="font-bold text-lg">ElastiCache (Redis)</h2>
            </div>
            <p class="text-sm text-slate-600 dark:text-slate-300">
                Used for sessions, caching, and queues. Reduces database load and enables fast response times.
            </p>
        </div>

        {{-- S3 --}}
        <div class="group p-6 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition">
            <div class="flex items-center gap-3 mb-3">
                <span class="text-xl">📦</span>
                <h2 class="font-bold text-lg">S3 (Storage)</h2>
            </div>
            <p class="text-sm text-slate-600 dark:text-slate-300">
                Stores uploaded files separately from the application server, ensuring scalability and durability.
            </p>
        </div>

        {{-- VPC --}}
        <div class="group p-6 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition md:col-span-2">
            <div class="flex items-center gap-3 mb-3">
                <span class="text-xl">🔐</span>
                <h2 class="font-bold text-lg">VPC (Networking)</h2>
            </div>
            <p class="text-sm text-slate-600 dark:text-slate-300">
                All services run inside a private network. Sensitive components like RDS and Redis are not publicly accessible,
                improving security and isolation.
            </p>
        </div>

        {{-- Diagram --}}
    <div class="mb-16">
        <div class="rounded-2xl mb-6 overflow-hidden border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-md hover:shadow-lg transition">
            <img
                src="{{ asset('images/aws-architecture.png') }}"
                alt="AWS Architecture Diagram"
                class="w-full h-auto"
            >
        </div>
    </div>

    </div>



</main>

</body>
</html>
