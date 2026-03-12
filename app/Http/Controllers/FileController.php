<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\File;

class FileController extends Controller
{
    // Show list of files for the logged-in user
    public function index()
    {
        $files = File::where('user_id', Auth::id())->get();

        return view('files.index', compact('files'));
    }

    // Show upload form
    public function create()
    {
        return view('files.create');
    }

    // Handle file upload
    public function store(Request $request)
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
    public function download($id)
    {
        $file = File::findOrFail($id);

        // Ensure user owns the file
        if ($file->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return Storage::download($file->path, $file->original_name);
    }

    // Delete file
    public function destroy($id)
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
