<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\{User, BlogPost, Promo, PromoClaim, Brand, Outlet, CareerJob, JobApplication, BlogComment};

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'       => User::where('role', 'customer')->count(),
            'total_posts'       => BlogPost::where('status', 'published')->count(),
            'active_promos'     => Promo::where('status', 'active')->count(),
            'total_claims'      => PromoClaim::count(),
            'total_brands'      => Brand::where('is_active', true)->count(),
            'total_outlets'     => Outlet::where('is_active', true)->count(),
            'open_jobs'         => CareerJob::where('status', 'open')->count(),
            'new_applications'  => JobApplication::where('status', 'new')->count(),
            'pending_comments'  => BlogComment::where('is_approved', false)->count(),
        ];

        $recentApplications = JobApplication::with('job.brand')->latest('applied_at')->take(5)->get();
        $recentClaims       = PromoClaim::with('user', 'promo')->latest()->take(5)->get();
        $recentComments     = BlogComment::with('user', 'post')->latest()->take(5)->get();

        return view('dashboard.index', compact('stats', 'recentApplications', 'recentClaims', 'recentComments'));
    }
}
