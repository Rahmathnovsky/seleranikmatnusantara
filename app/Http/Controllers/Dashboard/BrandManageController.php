<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\{Brand, Outlet, Region};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandManageController extends Controller
{
    public function index()
    {
        $query = Brand::withCount('outlets')->orderBy('sort_order');
        if (auth()->user()->brand_id) {
            $query->where('id', auth()->user()->brand_id);
        }
        $brands = $query->get();
        return view('dashboard.brands.index', compact('brands'));
    }

    public function create()
    {
        if (auth()->user()->brand_id) {
            abort(403, 'Unauthorized brand tenant access.');
        }
        return view('dashboard.brands.form');
    }

    public function store(Request $request)
    {
        if (auth()->user()->brand_id) {
            abort(403, 'Unauthorized brand tenant access.');
        }
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'tagline'       => 'nullable|string|max:255',
            'description'   => 'nullable|string',
            'cuisine_type'  => 'nullable|string|max:100',
            'color_primary' => 'nullable|string|max:7',
            'logo'          => 'nullable|image|max:2048',
            'cover_image'   => 'nullable|image|max:5120',
            'website_url'   => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'is_active'     => 'boolean',
            'sort_order'    => 'integer',
        ]);

        if ($request->hasFile('logo'))        $data['logo']        = $request->file('logo')->store('brands', 'public');
        if ($request->hasFile('cover_image')) $data['cover_image'] = $request->file('cover_image')->store('brands', 'public');
        $data['slug'] = Str::slug($data['name']);

        Brand::create($data);
        return redirect()->route('dashboard.brands.index')->with('success', 'Brand created!');
    }

    public function edit(int $id)
    {
        if (auth()->user()->brand_id && auth()->user()->brand_id !== $id) {
            abort(403, 'Unauthorized brand tenant access.');
        }
        $brand = Brand::findOrFail($id);
        return view('dashboard.brands.form', compact('brand'));
    }

    public function update(Request $request, int $id)
    {
        if (auth()->user()->brand_id && auth()->user()->brand_id !== $id) {
            abort(403, 'Unauthorized brand tenant access.');
        }
        $brand = Brand::findOrFail($id);
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'tagline'       => 'nullable|string|max:255',
            'description'   => 'nullable|string',
            'cuisine_type'  => 'nullable|string|max:100',
            'color_primary' => 'nullable|string|max:7',
            'logo'          => 'nullable|image|max:2048',
            'cover_image'   => 'nullable|image|max:5120',
            'website_url'   => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'is_active'     => 'boolean',
            'sort_order'    => 'integer',
        ]);

        if ($request->hasFile('logo'))        $data['logo']        = $request->file('logo')->store('brands', 'public');
        if ($request->hasFile('cover_image')) $data['cover_image'] = $request->file('cover_image')->store('brands', 'public');

        $brand->update($data);
        return redirect()->route('dashboard.brands.index')->with('success', 'Brand updated!');
    }

    public function destroy(int $id)
    {
        if (auth()->user()->brand_id && auth()->user()->brand_id !== $id) {
            abort(403, 'Unauthorized brand tenant access.');
        }
        Brand::findOrFail($id)->delete();
        return back()->with('success', 'Brand deleted!');
    }

    public function outlets(int $brandId)
    {
        if (auth()->user()->brand_id && auth()->user()->brand_id !== $brandId) {
            abort(403, 'Unauthorized brand tenant access.');
        }
        $brand   = Brand::findOrFail($brandId);
        $outlets = Outlet::where('brand_id', $brandId)->with('region')->orderBy('sort_order')->paginate(20);
        $regions = Region::orderBy('name')->get();
        return view('dashboard.brands.outlets', compact('brand', 'outlets', 'regions'));
    }

    public function storeOutlet(Request $request, int $brandId)
    {
        if (auth()->user()->brand_id && auth()->user()->brand_id !== $brandId) {
            abort(403, 'Unauthorized brand tenant access.');
        }
        $brand = Brand::findOrFail($brandId);
        $data  = $request->validate([
            'name'               => 'required|string|max:255',
            'region_id'          => 'nullable|exists:regions,id',
            'address'            => 'nullable|string',
            'phone'              => 'nullable|string|max:20',
            'whatsapp'           => 'nullable|string|max:20',
            'gmaps_url'          => 'nullable|url',
            'photo'              => 'nullable|image|max:5120',
            'operational_hours'  => 'nullable|json',
            'is_active'          => 'boolean',
        ]);

        if ($request->hasFile('photo')) $data['photo'] = $request->file('photo')->store('outlets', 'public');
        $data['brand_id'] = $brandId;
        if ($data['operational_hours'] ?? null) $data['operational_hours'] = json_decode($data['operational_hours'], true);

        Outlet::create($data);
        return back()->with('success', 'Outlet added!');
    }

    public function updateOutlet(Request $request, int $brandId, int $id)
    {
        if (auth()->user()->brand_id && auth()->user()->brand_id !== $brandId) {
            abort(403, 'Unauthorized brand tenant access.');
        }
        $outlet = Outlet::where('brand_id', $brandId)->findOrFail($id);
        $data   = $request->validate(['name' => 'required', 'address' => 'nullable', 'phone' => 'nullable', 'is_active' => 'boolean']);
        $outlet->update($data);
        return back()->with('success', 'Outlet updated!');
    }

    public function destroyOutlet(int $brandId, int $id)
    {
        if (auth()->user()->brand_id && auth()->user()->brand_id !== $brandId) {
            abort(403, 'Unauthorized brand tenant access.');
        }
        Outlet::where('brand_id', $brandId)->findOrFail($id)->delete();
        return back()->with('success', 'Outlet deleted!');
    }

    public function regions()
    {
        $regions = Region::with('parent')->orderBy('type')->orderBy('name')->get();
        return view('dashboard.brands.regions', compact('regions'));
    }

    public function storeRegion(Request $request)
    {
        $data = $request->validate(['name' => 'required', 'type' => 'required|in:province,city,area', 'parent_id' => 'nullable|exists:regions,id']);
        Region::create($data);
        return back()->with('success', 'Region added!');
    }

    public function updateRegion(Request $request, int $id)
    {
        Region::findOrFail($id)->update($request->validate(['name' => 'required', 'type' => 'in:province,city,area']));
        return back()->with('success', 'Region updated!');
    }

    public function destroyRegion(int $id)
    {
        Region::findOrFail($id)->delete();
        return back()->with('success', 'Region deleted!');
    }
}
