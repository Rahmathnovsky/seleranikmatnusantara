<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\{Brand, BlogPost, Promo, CareerJob};
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [];

        // Static routes
        $urls[] = [
            'loc' => route('home'),
            'priority' => '1.0',
            'changefreq' => 'daily'
        ];
        $urls[] = [
            'loc' => route('brands.index'),
            'priority' => '0.8',
            'changefreq' => 'weekly'
        ];
        $urls[] = [
            'loc' => route('blog.index'),
            'priority' => '0.8',
            'changefreq' => 'daily'
        ];
        $urls[] = [
            'loc' => route('promo.index'),
            'priority' => '0.8',
            'changefreq' => 'daily'
        ];
        $urls[] = [
            'loc' => route('career.index'),
            'priority' => '0.8',
            'changefreq' => 'weekly'
        ];

        // Dynamic Brands
        $brands = Brand::active()->get();
        foreach ($brands as $brand) {
            $urls[] = [
                'loc' => route('brands.show', $brand->slug),
                'priority' => '0.7',
                'changefreq' => 'weekly',
                'lastmod' => $brand->updated_at ? $brand->updated_at->toAtomString() : null
            ];
        }

        // Dynamic Blog Posts
        $posts = BlogPost::where('status', 'published')->get();
        foreach ($posts as $post) {
            $urls[] = [
                'loc' => route('blog.show', $post->slug),
                'priority' => '0.6',
                'changefreq' => 'monthly',
                'lastmod' => $post->published_at ? $post->published_at->toAtomString() : ($post->updated_at ? $post->updated_at->toAtomString() : null)
            ];
        }

        // Dynamic Promos
        $promos = Promo::where('status', 'active')->get();
        foreach ($promos as $promo) {
            $urls[] = [
                'loc' => route('promo.show', $promo->slug),
                'priority' => '0.6',
                'changefreq' => 'weekly',
                'lastmod' => $promo->updated_at ? $promo->updated_at->toAtomString() : null
            ];
        }

        // Dynamic Career Jobs
        $jobs = CareerJob::open()->get();
        foreach ($jobs as $job) {
            $urls[] = [
                'loc' => route('career.show', $job->slug),
                'priority' => '0.6',
                'changefreq' => 'weekly',
                'lastmod' => $job->updated_at ? $job->updated_at->toAtomString() : null
            ];
        }

        $xml = view('public.sitemap', compact('urls'))->render();

        return response($xml, 200)
            ->header('Content-Type', 'text/xml');
    }
}
