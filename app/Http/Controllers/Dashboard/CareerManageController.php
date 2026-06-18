<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\{CareerJob, JobCategory, JobApplication, Brand};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CareerManageController extends Controller
{
    public function index(Request $request)
    {
        $query = CareerJob::with('brand', 'category')->withCount('applications');
        if (auth()->user()->brand_id) {
            $query->where('brand_id', auth()->user()->brand_id);
        }
        $jobs = $query->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()->paginate(15);
        $categories = JobCategory::all();
        $brandsQuery = Brand::active();
        if (auth()->user()->brand_id) {
            $brandsQuery->where('id', auth()->user()->brand_id);
        }
        $brands = $brandsQuery->get();
        return view('dashboard.career.index', compact('jobs', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = JobCategory::all();
        $brandsQuery = Brand::active();
        if (auth()->user()->brand_id) {
            $brandsQuery->where('id', auth()->user()->brand_id);
        }
        $brands = $brandsQuery->get();
        return view('dashboard.career.form', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'           => 'required|string|max:255',
            'job_category_id' => 'nullable|exists:job_categories,id',
            'brand_id'        => 'nullable|exists:brands,id',
            'description'     => 'required|string',
            'requirements'    => 'nullable|string',
            'benefits'        => 'nullable|string',
            'location'        => 'required|string|max:255',
            'salary_range'    => 'nullable|string|max:100',
            'type'            => 'required|in:fulltime,parttime,internship,contract',
            'status'          => 'required|in:open,closed,draft',
            'deadline'        => 'nullable|date',
        ]);
        if (auth()->user()->brand_id) {
            $data['brand_id'] = auth()->user()->brand_id;
        }
        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(4);
        CareerJob::create($data);
        return redirect()->route('dashboard.career.index')->with('success', 'Job posted!');
    }

    public function edit(int $id)
    {
        $job = CareerJob::findOrFail($id);
        if (auth()->user()->brand_id && $job->brand_id !== auth()->user()->brand_id) {
            abort(403, 'Unauthorized brand tenant access.');
        }
        $categories = JobCategory::all();
        $brandsQuery = Brand::active();
        if (auth()->user()->brand_id) {
            $brandsQuery->where('id', auth()->user()->brand_id);
        }
        $brands = $brandsQuery->get();
        return view('dashboard.career.form', compact('job', 'categories', 'brands'));
    }

    public function update(Request $request, int $id)
    {
        $job = CareerJob::findOrFail($id);
        if (auth()->user()->brand_id && $job->brand_id !== auth()->user()->brand_id) {
            abort(403, 'Unauthorized brand tenant access.');
        }
        $data = $request->validate([
            'title'           => 'required|string|max:255',
            'job_category_id' => 'nullable|exists:job_categories,id',
            'brand_id'        => 'nullable|exists:brands,id',
            'description'     => 'required|string',
            'requirements'    => 'nullable|string',
            'benefits'        => 'nullable|string',
            'location'        => 'required|string|max:255',
            'salary_range'    => 'nullable|string|max:100',
            'type'            => 'required|in:fulltime,parttime,internship,contract',
            'status'          => 'required|in:open,closed,draft',
            'deadline'        => 'nullable|date',
        ]);
        if (auth()->user()->brand_id) {
            $data['brand_id'] = auth()->user()->brand_id;
        }
        $job->update($data);
        return redirect()->route('dashboard.career.index')->with('success', 'Job updated!');
    }

    public function destroy(int $id)
    {
        $job = CareerJob::findOrFail($id);
        if (auth()->user()->brand_id && $job->brand_id !== auth()->user()->brand_id) {
            abort(403, 'Unauthorized brand tenant access.');
        }
        $job->delete();
        return back()->with('success', 'Job deleted!');
    }

    public function applications(int $id)
    {
        $job = CareerJob::findOrFail($id);
        if (auth()->user()->brand_id && $job->brand_id !== auth()->user()->brand_id) {
            abort(403, 'Unauthorized brand tenant access.');
        }
        $applications = JobApplication::where('career_job_id', $id)
            ->orderBy('applied_at', 'desc')->paginate(20);
        return view('dashboard.career.applications', compact('job', 'applications'));
    }

    public function showApplication(int $appId)
    {
        $application = JobApplication::with('job.brand')->findOrFail($appId);
        if (auth()->user()->brand_id && $application->job->brand_id !== auth()->user()->brand_id) {
            abort(403, 'Unauthorized brand tenant access.');
        }
        return view('dashboard.career.application-detail', compact('application'));
    }

    public function updateApplicationStatus(Request $request, int $appId)
    {
        $app = JobApplication::with('job')->findOrFail($appId);
        if (auth()->user()->brand_id && $app->job->brand_id !== auth()->user()->brand_id) {
            abort(403, 'Unauthorized brand tenant access.');
        }
        $request->validate([
            'status'   => 'required|in:new,reviewed,shortlisted,interview,offered,rejected',
            'hr_notes' => 'nullable|string|max:2000',
        ]);
        $app->update(['status' => $request->status, 'hr_notes' => $request->hr_notes]);
        return back()->with('success', 'Status updated!');
    }
}
