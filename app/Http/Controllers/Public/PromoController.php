<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\{Promo, PromoClaim};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::active()->latest()->paginate(12);
        return view('public.promo.index', compact('promos'));
    }

    public function show(string $slug)
    {
        $promo = Promo::where('slug', $slug)->firstOrFail();
        $userClaim = auth()->check()
            ? PromoClaim::where('promo_id', $promo->id)->where('user_id', auth()->id())->first()
            : null;

        return view('public.promo.show', compact('promo', 'userClaim'));
    }

    public function claim(Request $request, int $id)
    {
        $promo = Promo::findOrFail($id);

        if (!$promo->isActive()) {
            return back()->with('error', __('This promo is no longer active.'));
        }

        $existing = PromoClaim::where('promo_id', $promo->id)->where('user_id', auth()->id())->first();
        if ($existing) {
            return back()->with('error', __('You have already claimed this promo.'));
        }

        if ($promo->max_claims && $promo->claims()->count() >= $promo->max_claims) {
            return back()->with('error', __('Promo quota has been reached.'));
        }

        $claim = PromoClaim::create([
            'promo_id' => $promo->id,
            'user_id' => auth()->id(),
            'status' => 'claimed',
        ]);

        return redirect()->route('promo.my-vouchers')->with('success', __('Promo claimed! Your code: :code', ['code' => $claim->claim_code]));
    }

    public function myVouchers()
    {
        $claims = PromoClaim::where('user_id', auth()->id())
            ->with('promo')
            ->latest()
            ->paginate(12);

        return view('public.promo.my-vouchers', compact('claims'));
    }

    public function verifyCode(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $claim = PromoClaim::where('claim_code', strtoupper($request->code))
            ->with('promo', 'user')
            ->first();

        if (!$claim) {
            return response()->json(['valid' => false, 'message' => __('Invalid promo code.')]);
        }

        if ($claim->status === 'used') {
            return response()->json(['valid' => false, 'message' => __('This code has already been used.'), 'claim' => $claim]);
        }

        if ($claim->isExpired()) {
            return response()->json(['valid' => false, 'message' => __('This promo code has expired.'), 'claim' => $claim]);
        }

        return response()->json(['valid' => true, 'claim' => $claim, 'promo' => $claim->promo]);
    }
}
