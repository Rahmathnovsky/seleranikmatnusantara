<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\{BlogPost, BlogCategory, BlogComment};
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::published()->with('category', 'author');

        if ($request->category) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }
        if ($request->search) {
            $query->where(fn($q) => $q->where('title', 'like', "%{$request->search}%")
                ->orWhere('excerpt', 'like', "%{$request->search}%"));
        }

        $posts = $query->latest('published_at')->paginate(9);
        $categories = BlogCategory::where('is_active', true)->withCount('posts')->get();
        $featured = BlogPost::published()->orderByDesc('views')->take(3)->get();

        return view('public.blog.index', compact('posts', 'categories', 'featured'));
    }

    public function show(string $slug)
    {
        $post = BlogPost::published()->where('slug', $slug)->with('category', 'author', 'tags')->firstOrFail();

        // Increment views
        $post->increment('views');

        $comments = BlogComment::where('blog_post_id', $post->id)
            ->approved()->topLevel()->with('user', 'replies.user')
            ->orderByDesc('created_at')->get();

        $related = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->where('blog_category_id', $post->blog_category_id)
            ->latest('published_at')->take(3)->get();

        return view('public.blog.show', compact('post', 'comments', 'related'));
    }

    public function storeComment(Request $request, int $id)
    {
        $request->validate([
            'body' => 'required|string|min:3|max:2000',
            'guest_name' => 'required_without_all:user_id|nullable|string|max:100',
            'guest_email' => 'nullable|email|max:255',
            'parent_id' => 'nullable|exists:blog_comments,id',
        ]);

        $post = BlogPost::findOrFail($id);

        BlogComment::create([
            'blog_post_id' => $post->id,
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id,
            'guest_name' => auth()->check() ? null : $request->guest_name,
            'guest_email' => auth()->check() ? null : $request->guest_email,
            'body' => $request->body,
            'is_approved' => auth()->check() ? true : false, // auto-approve logged in users
        ]);

        return back()->with('success', __('Comment submitted successfully!'));
    }

    public function like(Request $request, int $id)
    {
        $post = BlogPost::findOrFail($id);
        $liked = session()->get('liked_posts', []);

        if (in_array($id, $liked)) {
            $post->decrement('likes');
            $liked = array_diff($liked, [$id]);
            session()->put('liked_posts', $liked);
            $status = 'unliked';
        } else {
            $post->increment('likes');
            $liked[] = $id;
            session()->put('liked_posts', $liked);
            $status = 'liked';
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'likes' => $post->likes,
                'status' => $status
            ]);
        }

        return back();
    }
}
