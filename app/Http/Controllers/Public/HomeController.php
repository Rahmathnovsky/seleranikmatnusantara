<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\{Brand, Promo, BlogPost, CareerJob, SiteSetting};
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $brands = Brand::active()->with('activeOutlets')->get();
        $promos = Promo::active()->latest()->take(6)->get();
        $posts  = BlogPost::published()->with('category', 'author')->latest('published_at')->take(3)->get();
        $jobs   = CareerJob::open()->with('brand', 'category')->latest()->take(4)->get();

        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('public.index', compact('brands', 'promos', 'posts', 'jobs', 'settings'));
    }

    public function setLocale(Request $request, string $locale)
    {
        if (in_array($locale, ['id', 'en'])) {
            session(['locale' => $locale]);
        }
        return back();
    }

    public function setTheme(Request $request, string $theme)
    {
        if (in_array($theme, ['light', 'dark'])) {
            session(['theme' => $theme]);
        }
        return back();
    }
}
