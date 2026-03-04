<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;

class BlogController extends Controller
{
    public function index()
{
    $categorySlug = request('category');

    $posts = Post::where('is_published', true)
        ->when($categorySlug, function ($query) use ($categorySlug) {
            $query->whereHas('categories', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        })
        ->latest()
        ->paginate(6);

    $categories = Category::withCount('posts')->get();

    return view('blog.index', compact('posts', 'categories', 'categorySlug'));
}

    public function show($slug)
    {
        $post = Post::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('blog.show', compact('post'));
    }
}
