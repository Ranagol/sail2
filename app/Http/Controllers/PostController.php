<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * @var list<string>
     */
    private array $latinWords = [
        'lorem', 'ipsum', 'dolor', 'sit', 'amet',
        'consectetur', 'adipiscing', 'elit', 'sed', 'do',
        'eiusmod', 'tempor', 'incididunt', 'ut', 'labore',
        'et', 'dolore', 'magna', 'aliqua', 'enim',
        'ad', 'minim', 'veniam', 'quis', 'nostrud',
        'exercitation', 'ullamco', 'laboris', 'nisi', 'aliquip',
        'ex', 'ea', 'commodo', 'consequat', 'duis',
        'aute', 'irure', 'in', 'reprehenderit', 'voluptate',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $posts = Post::query()
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): RedirectResponse
    {
        Post::query()->create([
            'user_id' => Auth::id(),
            'title' => $request->validated('title'),
            'content' => $request->validated('content'),
        ]);

        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): View
    {
        $this->authorizeOwnership($post);

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post): View
    {
        $this->authorizeOwnership($post);

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        $this->authorizeOwnership($post);

        $post->update($request->validated());

        return redirect()->route('posts.index')
            ->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): RedirectResponse
    {
        $this->authorizeOwnership($post);

        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully.');
    }

    public function createDemoPosts(): RedirectResponse
    {
        for ($i = 0; $i < 10; $i++) {
            $titleWords = $this->latinWords;
            shuffle($titleWords);
            $title = ucfirst(implode(' ', array_slice($titleWords, 0, random_int(3, 6))));

            $contentWords = $this->latinWords;
            shuffle($contentWords);
            $content = ucfirst(implode(' ', array_slice($contentWords, 0, random_int(6, 10)))).'.';

            Post::query()->create([
                'user_id' => Auth::id(),
                'title' => $title,
                'content' => $content,
            ]);
        }

        return redirect()->route('posts.index')
            ->with('success', '10 demo posts created successfully.');
    }

    public function deleteAll(): RedirectResponse
    {
        Post::query()->where('user_id', Auth::id())->delete();

        return redirect()->route('posts.index')
            ->with('success', 'All posts deleted successfully.');
    }

    private function authorizeOwnership(Post $post): void
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
