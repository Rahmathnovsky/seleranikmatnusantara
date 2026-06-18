<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\{CareerJob, JobCategory, JobApplication, Brand};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\CandidateApplicationSubmitted;
use App\Mail\HRNewApplicationAlert;

class CareerController extends Controller
{
    public function index(Request $request)
    {
        $query = CareerJob::open()->with('brand', 'category');

        if ($request->type)     $query->where('type', $request->type);
        if ($request->category) $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        if ($request->brand)    $query->whereHas('brand', fn($q) => $q->where('slug', $request->brand));
        if ($request->search)   $query->where('title', 'like', "%{$request->search}%");

        $jobs       = $query->latest()->paginate(10);
        $categories = JobCategory::where('is_active', true)->get();
        $brands     = Brand::active()->get();

        return view('public.career.index', compact('jobs', 'categories', 'brands'));
    }

    public function show(string $slug)
    {
        $job = CareerJob::open()->where('slug', $slug)->with('brand', 'category')->firstOrFail();
        $related = CareerJob::open()->where('id', '!=', $job->id)
            ->where('job_category_id', $job->job_category_id)
            ->with('brand')->take(4)->get();

        return view('public.career.show', compact('job', 'related'));
    }

    public function apply(Request $request, int $id)
    {
        $job = CareerJob::findOrFail($id);

        $request->validate([
            'full_name'            => 'required|string|max:255',
            'email'                => 'required|email|max:255',
            'phone'                => 'required|string|max:20',
            'address'              => 'nullable|string|max:500',
            'date_of_birth'        => 'nullable|date|before:today',
            'last_education'       => 'nullable|string|max:100',
            'major'                => 'nullable|string|max:100',
            'university'           => 'nullable|string|max:200',
            'gpa'                  => 'nullable|numeric|min:0|max:4',
            'work_experience_years'=> 'nullable|integer|min:0|max:50',
            'cv_file'              => 'required|file|mimes:pdf,doc,docx|max:5120',
            'cover_letter'         => 'nullable|string|max:5000',
            'portfolio_url'        => 'nullable|url|max:255',
        ]);

        // Check duplicate
        $existing = JobApplication::where('career_job_id', $job->id)
            ->where('email', $request->email)->first();
        if ($existing) {
            return back()->withInput()->with('error', __('You have already applied for this position.'));
        }

        $cvPath = $request->file('cv_file')->store('cv-files', 'public');

        $application = JobApplication::create([
            'career_job_id'        => $job->id,
            'full_name'            => $request->full_name,
            'email'                => $request->email,
            'phone'                => $request->phone,
            'address'              => $request->address,
            'date_of_birth'        => $request->date_of_birth,
            'last_education'       => $request->last_education,
            'major'                => $request->major,
            'university'           => $request->university,
            'gpa'                  => $request->gpa,
            'work_experience_years'=> $request->work_experience_years,
            'cv_file'              => $cvPath,
            'cover_letter'         => $request->cover_letter,
            'portfolio_url'        => $request->portfolio_url,
        ]);

        // Send confirmation email to Candidate
        Mail::to($application->email)->queue(new CandidateApplicationSubmitted($application));

        // Send notification email to HR
        $hrEmails = \App\Models\User::where('role', 'hr')
            ->where(function ($q) use ($job) {
                $q->whereNull('brand_id')->orWhere('brand_id', $job->brand_id);
            })
            ->pluck('email')
            ->toArray();

        if (empty($hrEmails)) {
            $hrEmails = [\App\Models\SiteSetting::get('contact_email', 'info@seleranikmatnusantara.test')];
        }

        foreach ($hrEmails as $hrEmail) {
            Mail::to($hrEmail)->queue(new HRNewApplicationAlert($application));
        }

        return redirect()->route('career.apply.success', $id)
            ->with('success', __('Application submitted successfully!'));
    }

    public function applySuccess(int $id)
    {
        $job = CareerJob::findOrFail($id);
        return view('public.career.apply-success', compact('job'));
    }
}
