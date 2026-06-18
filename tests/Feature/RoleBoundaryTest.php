<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Brand;
use App\Models\Region;
use App\Models\Outlet;
use App\Models\Promo;
use App\Models\JobCategory;
use App\Models\CareerJob;
use App\Models\JobApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleBoundaryTest extends TestCase
{
    use RefreshDatabase;

    private Brand $brandA;
    private Brand $brandB;
    private Region $region;
    private JobCategory $jobCategory;
    private User $editorA;
    private User $hrA;
    private User $corporateAdmin;
    private Promo $promoB;
    private Outlet $outletB;
    private CareerJob $jobB;
    private JobApplication $applicationB;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Brands
        $this->brandA = Brand::create([
            'name' => 'Brand A',
            'slug' => 'brand-a',
            'is_active' => true,
        ]);

        $this->brandB = Brand::create([
            'name' => 'Brand B',
            'slug' => 'brand-b',
            'is_active' => true,
        ]);

        // Create Region
        $this->region = Region::create([
            'name' => 'Region A',
            'type' => 'area',
        ]);

        // Create Job Category
        $this->jobCategory = JobCategory::create([
            'name' => 'Category A',
            'slug' => 'category-a',
            'is_active' => true,
        ]);

        // Create Users
        $this->editorA = User::create([
            'brand_id' => $this->brandA->id,
            'name' => 'Editor Brand A',
            'email' => 'editor_a@snn.id',
            'password' => bcrypt('password'),
            'role' => 'editor',
            'is_active' => true,
        ]);

        $this->hrA = User::create([
            'brand_id' => $this->brandA->id,
            'name' => 'HR Brand A',
            'email' => 'hr_a@snn.id',
            'password' => bcrypt('password'),
            'role' => 'hr',
            'is_active' => true,
        ]);

        $this->corporateAdmin = User::create([
            'brand_id' => null,
            'name' => 'Corporate Admin',
            'email' => 'admin@snn.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create Brand B Resources
        $this->promoB = Promo::create([
            'brand_id' => $this->brandB->id,
            'title' => 'Promo Brand B',
            'slug' => 'promo-brand-b',
            'status' => 'active',
            'requires_login' => true,
        ]);

        $this->outletB = Outlet::create([
            'brand_id' => $this->brandB->id,
            'region_id' => $this->region->id,
            'name' => 'Outlet Brand B',
            'address' => 'Address Brand B',
            'is_active' => true,
        ]);

        $this->jobB = CareerJob::create([
            'brand_id' => $this->brandB->id,
            'job_category_id' => $this->jobCategory->id,
            'title' => 'Job Brand B',
            'slug' => 'job-brand-b',
            'description' => 'Description Brand B',
            'location' => 'Location B',
            'type' => 'fulltime',
            'status' => 'open',
        ]);

        $this->applicationB = JobApplication::create([
            'career_job_id' => $this->jobB->id,
            'full_name' => 'Candidate B',
            'email' => 'candidate_b@example.com',
            'phone' => '081234567890',
            'cv_file' => 'cv-files/test_cv.pdf',
        ]);
    }

    /**
     * Test tenant editor cannot access other brand's promos.
     */
    public function test_tenant_editor_cannot_access_other_brand_promo(): void
    {
        // View edit form
        $response = $this->actingAs($this->editorA)
            ->get(route('dashboard.promo.edit', $this->promoB->id));
        $response->assertStatus(403);

        // Update promo
        $response = $this->actingAs($this->editorA)
            ->put(route('dashboard.promo.update', $this->promoB->id), [
                'title' => 'Updated Title',
            ]);
        $response->assertStatus(403);

        // Delete promo
        $response = $this->actingAs($this->editorA)
            ->delete(route('dashboard.promo.destroy', $this->promoB->id));
        $response->assertStatus(403);
    }

    /**
     * Test tenant editor cannot access other brand's outlets.
     */
    public function test_tenant_editor_cannot_access_other_brand_outlet(): void
    {
        // View outlets list
        $response = $this->actingAs($this->editorA)
            ->get(route('dashboard.brands.outlets', $this->brandB->id));
        $response->assertStatus(403);

        // Store outlet
        $response = $this->actingAs($this->editorA)
            ->post(route('dashboard.brands.outlets.store', $this->brandB->id), [
                'name' => 'New Outlet',
                'region_id' => $this->region->id,
            ]);
        $response->assertStatus(403);

        // Update outlet
        $response = $this->actingAs($this->editorA)
            ->put(route('dashboard.brands.outlets.update', [$this->brandB->id, $this->outletB->id]), [
                'name' => 'Updated Outlet',
                'region_id' => $this->region->id,
            ]);
        $response->assertStatus(403);

        // Delete outlet
        $response = $this->actingAs($this->editorA)
            ->delete(route('dashboard.brands.outlets.destroy', [$this->brandB->id, $this->outletB->id]));
        $response->assertStatus(403);
    }

    /**
     * Test tenant HR cannot access other brand's career resources.
     */
    public function test_tenant_hr_cannot_access_other_brand_career(): void
    {
        // View job applications
        $response = $this->actingAs($this->hrA)
            ->get(route('dashboard.career.applications', $this->jobB->id));
        $response->assertStatus(403);

        // View single job application details
        $response = $this->actingAs($this->hrA)
            ->get(route('dashboard.career.applications.show', $this->applicationB->id));
        $response->assertStatus(403);

        // Update application status
        $response = $this->actingAs($this->hrA)
            ->put(route('dashboard.career.applications.status', $this->applicationB->id), [
                'status' => 'reviewed',
            ]);
        $response->assertStatus(403);
    }

    /**
     * Test corporate admin has full access to all brand resources.
     */
    public function test_corporate_admin_can_access_all_brand_resources(): void
    {
        // View promo edit page
        $response = $this->actingAs($this->corporateAdmin)
            ->get(route('dashboard.promo.edit', $this->promoB->id));
        $response->assertStatus(200);

        // View outlets page
        $response = $this->actingAs($this->corporateAdmin)
            ->get(route('dashboard.brands.outlets', $this->brandB->id));
        $response->assertStatus(200);

        // View job applications page
        $response = $this->actingAs($this->corporateAdmin)
            ->get(route('dashboard.career.applications', $this->jobB->id));
        $response->assertStatus(200);

        // View single job application
        $response = $this->actingAs($this->corporateAdmin)
            ->get(route('dashboard.career.applications.show', $this->applicationB->id));
        $response->assertStatus(200);
    }

    /**
     * Test submitting a job application dispatches emails to queue.
     */
    public function test_job_application_submission_queues_emails(): void
    {
        \Illuminate\Support\Facades\Mail::fake();
        \Illuminate\Support\Facades\Storage::fake('public');

        // Create a fake CV file
        $cvFile = \Illuminate\Http\UploadedFile::fake()->create('cv.pdf', 100);

        // Submit the job application
        $response = $this->post(route('career.apply', $this->jobB->id), [
            'full_name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone' => '0812345678',
            'address' => 'Jakarta, Indonesia',
            'date_of_birth' => '1995-01-01',
            'last_education' => 'Bachelor',
            'major' => 'Computer Science',
            'university' => 'Indonesia University',
            'gpa' => 3.75,
            'work_experience_years' => 3,
            'cv_file' => $cvFile,
            'cover_letter' => 'I am interested in this job.',
        ]);

        $response->assertRedirect(route('career.apply.success', $this->jobB->id));

        // Assert candidate email was queued
        \Illuminate\Support\Facades\Mail::assertQueued(\App\Mail\CandidateApplicationSubmitted::class, function ($mail) {
            return $mail->hasTo('johndoe@example.com') && $mail->application->full_name === 'John Doe';
        });

        // Assert HR email was queued
        $defaultHrEmail = \App\Models\SiteSetting::get('contact_email', 'info@seleranikmatnusantara.test');
        \Illuminate\Support\Facades\Mail::assertQueued(\App\Mail\HRNewApplicationAlert::class, function ($mail) use ($defaultHrEmail) {
            return $mail->hasTo($defaultHrEmail) && $mail->application->full_name === 'John Doe';
        });
    }
}
