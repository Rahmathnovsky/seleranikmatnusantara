<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\{BlogPost, BlogCategory, BlogComment, BlogTag};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogManageController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::with('category', 'author')
            ->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->when($request->status, fn($q) => $q->where('status', $request->status));
        if (auth()->user()->brand_id) {
            $query->where('brand_id', auth()->user()->brand_id);
        }
        $posts = $query->latest()->paginate(15);
        return view('dashboard.blog.index', compact('posts'));
    }

    public function create()
    {
        $categories = BlogCategory::where('is_active', true)->get();
        return view('dashboard.blog.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'excerpt'          => 'nullable|string|max:500',
            'body'             => 'required|string',
            'cover_image'      => 'nullable|image|max:5120',
            'status'           => 'required|in:draft,published',
            'published_at'     => 'nullable|date',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'tags'             => 'nullable|array',
        ]);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('blog', 'public');
        }

        $data['author_id'] = auth()->id();
        $data['slug'] = Str::slug($data['title']);
        if (auth()->user()->brand_id) {
            $data['brand_id'] = auth()->user()->brand_id;
        }

        $post = BlogPost::create($data);

        if ($request->tags) {
            $tagIds = collect($request->tags)->map(function ($name) {
                return BlogTag::firstOrCreate(['name' => $name], ['slug' => Str::slug($name)])->id;
            });
            $post->tags()->sync($tagIds);
        }

        return redirect()->route('dashboard.blog.index')->with('success', 'Post created!');
    }

    public function edit(int $id)
    {
        $post = BlogPost::findOrFail($id);
        if (auth()->user()->brand_id && $post->brand_id !== auth()->user()->brand_id) {
            abort(403, 'Unauthorized brand tenant access.');
        }
        $categories = BlogCategory::where('is_active', true)->get();
        return view('dashboard.blog.form', compact('post', 'categories'));
    }

    public function update(Request $request, int $id)
    {
        $post = BlogPost::findOrFail($id);
        if (auth()->user()->brand_id && $post->brand_id !== auth()->user()->brand_id) {
            abort(403, 'Unauthorized brand tenant access.');
        }
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'excerpt'          => 'nullable|string|max:500',
            'body'             => 'required|string',
            'cover_image'      => 'nullable|image|max:5120',
            'status'           => 'required|in:draft,published,archived',
            'published_at'     => 'nullable|date',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'tags'             => 'nullable|array',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($post->cover_image) Storage::disk('public')->delete($post->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('blog', 'public');
        }

        $post->update($data);

        if ($request->has('tags')) {
            $tagIds = collect($request->tags)->map(fn($name) =>
                BlogTag::firstOrCreate(['name' => $name], ['slug' => Str::slug($name)])->id
            );
            $post->tags()->sync($tagIds);
        }

        return redirect()->route('dashboard.blog.index')->with('success', 'Post updated!');
    }

    public function destroy(int $id)
    {
        $post = BlogPost::findOrFail($id);
        if (auth()->user()->brand_id && $post->brand_id !== auth()->user()->brand_id) {
            abort(403, 'Unauthorized brand tenant access.');
        }
        if ($post->cover_image) Storage::disk('public')->delete($post->cover_image);
        $post->delete();
        return back()->with('success', 'Post deleted!');
    }

    public function comments(Request $request)
    {
        $comments = BlogComment::with('user', 'post')
            ->when($request->approved === '0', fn($q) => $q->where('is_approved', false))
            ->when($request->approved === '1', fn($q) => $q->where('is_approved', true))
            ->latest()->paginate(20);
        return view('dashboard.blog.comments', compact('comments'));
    }

    public function approveComment(int $id)
    {
        BlogComment::findOrFail($id)->update(['is_approved' => true]);
        return back()->with('success', 'Comment approved!');
    }

    public function replyComment(Request $request, int $id)
    {
        $request->validate(['body' => 'required|string|min:3']);
        $comment = BlogComment::findOrFail($id);
        BlogComment::create([
            'blog_post_id'  => $comment->blog_post_id,
            'user_id'       => auth()->id(),
            'parent_id'     => $comment->id,
            'body'          => $request->body,
            'is_approved'   => true,
            'is_admin_reply'=> true,
        ]);
        return back()->with('success', 'Reply sent!');
    }

    public function destroyComment(int $id)
    {
        BlogComment::findOrFail($id)->delete();
        return back()->with('success', 'Comment deleted!');
    }

    public function categories()
    {
        $categories = BlogCategory::withCount('posts')->get();
        return view('dashboard.blog.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:100', 'description' => 'nullable|string']);
        $data['slug'] = Str::slug($data['name']);
        BlogCategory::create($data);
        return back()->with('success', 'Category created!');
    }

    public function updateCategory(Request $request, int $id)
    {
        $cat = BlogCategory::findOrFail($id);
        $cat->update($request->validate(['name' => 'required|string|max:100', 'is_active' => 'boolean']));
        return back()->with('success', 'Category updated!');
    }

    public function destroyCategory(int $id)
    {
        BlogCategory::findOrFail($id)->delete();
        return back()->with('success', 'Category deleted!');
    }
}
