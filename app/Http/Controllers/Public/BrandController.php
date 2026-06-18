<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\{Brand, Region, Outlet};
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::active()->withCount('activeOutlets')->get();
        return view('public.brands.index', compact('brands'));
    }

    public function show(string $slug)
    {
        $brand = Brand::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $regions = Region::whereHas('outlets', fn($q) => $q->where('brand_id', $brand->id)->where('is_active', true))
            ->with(['outlets' => fn($q) => $q->where('brand_id', $brand->id)->where('is_active', true)->orderBy('sort_order')])
            ->get();

        $otherBrands = Brand::active()->where('id', '!=', $brand->id)->take(4)->get();

        return view('public.brands.show', compact('brand', 'regions', 'otherBrands'));
    }
}
