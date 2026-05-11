<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Posts/Index', [
            'posts' => Post::latest()->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Posts/Form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_id' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'content_id' => 'required|string',
            'content_en' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title_id']);
        if ($validated['is_published']) {
            $validated['published_at'] = now();
        }

        Post::create($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Berita berhasil diterbitkan.');
    }

    public function edit(Post $post)
    {
        return Inertia::render('Admin/Posts/Form', [
            'post' => $post
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title_id' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'content_id' => 'required|string',
            'content_en' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        if ($validated['is_published'] && !$post->is_published) {
            $validated['published_at'] = now();
        }

        $post->update($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Berita berhasil dihapus.');
    }
}
