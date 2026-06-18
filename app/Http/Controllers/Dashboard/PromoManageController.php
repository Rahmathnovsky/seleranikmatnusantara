<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\{Promo, PromoClaim};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PromoManageController extends Controller
{
    public function index()
    {
        $query = Promo::withCount('claims')->latest();
        if (auth()->user()->brand_id) {
            $query->where('brand_id', auth()->user()->brand_id);
        }
        $promos = $query->paginate(15);
        return view('dashboard.promo.index', compact('promos'));
    }

    public function create()
    {
        return view('dashboard.promo.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'terms'          => 'nullable|string',
            'image'          => 'nullable|image|max:5120',
            'banner_image'   => 'nullable|image|max:5120',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'max_claims'     => 'nullable|integer|min:1',
            'promo_type'     => 'required|in:percentage,fixed,free_item,buy_x_get_y',
            'discount_value' => 'nullable|numeric|min:0',
            'discount_label' => 'nullable|string|max:100',
            'status'         => 'required|in:active,inactive',
            'requires_login' => 'boolean',
        ]);

        if ($request->hasFile('image'))        $data['image']        = $request->file('image')->store('promos', 'public');
        if ($request->hasFile('banner_image')) $data['banner_image'] = $request->file('banner_image')->store('promos', 'public');
        $data['slug'] = Str::slug($data['title']);
        if (auth()->user()->brand_id) {
            $data['brand_id'] = auth()->user()->brand_id;
        }

        Promo::create($data);
        return redirect()->route('dashboard.promo.index')->with('success', 'Promo created!');
    }

    public function edit(int $id)
    {
        $promo = Promo::findOrFail($id);
        if (auth()->user()->brand_id && $promo->brand_id !== auth()->user()->brand_id) {
            abort(403, 'Unauthorized brand tenant access.');
        }
        return view('dashboard.promo.form', compact('promo'));
    }

    public function update(Request $request, int $id)
    {
        $promo = Promo::findOrFail($id);
        if (auth()->user()->brand_id && $promo->brand_id !== auth()->user()->brand_id) {
            abort(403, 'Unauthorized brand tenant access.');
        }
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'terms'          => 'nullable|string',
            'image'          => 'nullable|image|max:5120',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date',
            'max_claims'     => 'nullable|integer|min:1',
            'promo_type'     => 'required|in:percentage,fixed,free_item,buy_x_get_y',
            'discount_value' => 'nullable|numeric',
            'discount_label' => 'nullable|string|max:100',
            'status'         => 'required|in:active,inactive,expired',
        ]);

        if ($request->hasFile('image')) {
            if ($promo->image) Storage::disk('public')->delete($promo->image);
            $data['image'] = $request->file('image')->store('promos', 'public');
        }

        $promo->update($data);
        return redirect()->route('dashboard.promo.index')->with('success', 'Promo updated!');
    }

    public function destroy(int $id)
    {
        $promo = Promo::findOrFail($id);
        if (auth()->user()->brand_id && $promo->brand_id !== auth()->user()->brand_id) {
            abort(403, 'Unauthorized brand tenant access.');
        }
        if ($promo->image) Storage::disk('public')->delete($promo->image);
        $promo->delete();
        return back()->with('success', 'Promo deleted!');
    }

    public function claims(int $id)
    {
        $promo  = Promo::findOrFail($id);
        if (auth()->user()->brand_id && $promo->brand_id !== auth()->user()->brand_id) {
            abort(403, 'Unauthorized brand tenant access.');
        }
        $claims = PromoClaim::where('promo_id', $id)->with('user', 'outlet')->latest()->paginate(20);
        return view('dashboard.promo.claims', compact('promo', 'claims'));
    }

    public function markUsed(Request $request, int $id)
    {
        $claim = PromoClaim::findOrFail($id);
        $claim->update(['status' => 'used', 'used_at' => now()]);
        return back()->with('success', 'Marked as used!');
    }

    public function verifyCode(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $claim = PromoClaim::where('claim_code', strtoupper($request->code))->with('promo', 'user')->first();

        if (!$claim) return response()->json(['valid' => false, 'message' => 'Code not found']);
        return response()->json(['valid' => true, 'claim' => $claim]);
    }
}
