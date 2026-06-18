<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManageController extends Controller
{
    public function index(Request $request)
    {
        $users = User::when($request->role, fn($q) => $q->where('role', $request->role))
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%"))
            ->latest()->paginate(20);
        return view('dashboard.users.index', compact('users'));
    }

    public function create()
    {
        $brands = \App\Models\Brand::active()->get();
        return view('dashboard.users.form', compact('brands'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|string|min:8|confirmed',
            'role'      => 'required|in:customer,admin,editor,hr',
            'brand_id'  => 'nullable|exists:brands,id',
            'phone'     => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);
        $data['password'] = Hash::make($data['password']);
        User::create($data);
        return redirect()->route('dashboard.users.index')->with('success', 'User created!');
    }

    public function edit(int $id)
    {
        $user = User::findOrFail($id);
        $brands = \App\Models\Brand::active()->get();
        return view('dashboard.users.form', compact('user', 'brands'));
    }

    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $id,
            'password'  => 'nullable|string|min:8|confirmed',
            'role'      => 'required|in:customer,admin,editor,hr',
            'brand_id'  => 'nullable|exists:brands,id',
            'phone'     => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);
        if ($request->filled('password')) $data['password'] = Hash::make($data['password']);
        else unset($data['password']);
        
        // If role is admin or customer, clear brand_id scope
        if (in_array($data['role'], ['admin', 'customer'])) {
            $data['brand_id'] = null;
        }

        $user->update($data);
        return redirect()->route('dashboard.users.index')->with('success', 'User updated!');
    }

    public function destroy(int $id)
    {
        if ($id === auth()->id()) return back()->with('error', 'Cannot delete yourself!');
        User::findOrFail($id)->delete();
        return back()->with('success', 'User deleted!');
    }
}
