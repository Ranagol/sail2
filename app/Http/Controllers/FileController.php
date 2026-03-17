<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    // Show list of files for the logged-in user
    public function index(): View
    {
        $files = File::where('user_id', Auth::id())->get();

        return view('files.index', compact('files'));
    }

    // Show upload form
    public function create(): View
    {
        return view('files.create');
    }

    // Handle file upload
    public function store(Request $request): RedirectResponse
    {
        // Validate file
        $request->validate([
            'file' => 'required|file|max:5120', // 5 MB max
        ]);

        // Store file in user-specific folder
        $path = $request->file('file')->store(
            'uploads/' . Auth::id()
        );

        // Save file info in database
        File::create([
            'user_id' => Auth::id(),
            'original_name' => $request->file('file')->getClientOriginalName(),
            'path' => $path,
            'size' => $request->file('file')->getSize(),
        ]);

        return redirect()->route('files.index')
            ->with('success', 'File uploaded successfully!');
    }

    // Download file
    public function download(int $id): StreamedResponse
    {
        $file = File::findOrFail($id);

        // Ensure user owns the file
        if ($file->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return Storage::download($file->path, $file->original_name);
    }

    // Delete file
    public function destroy(int $id): RedirectResponse
    {
        $file = File::findOrFail($id);

        if ($file->user_id !== Auth::id()) {
            abort(403);
        }

        Storage::delete($file->path);
        $file->delete();

        return redirect()->route('files.index')
            ->with('success', 'File deleted successfully.');
    }
}
