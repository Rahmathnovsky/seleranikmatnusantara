<?php

namespace Database\Seeders;

use App\Models\{User, Brand, Region, Outlet, Promo, BlogCategory, BlogPost, CareerJob, JobCategory, SiteSetting};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ========================
        // 1. Brands (Seeded First)
        // ========================
        $brand1 = Brand::create([
            'name'         => 'Bakoel Bamboe',
            'slug'         => 'bakoel-bamboe',
            'tagline'      => 'Cita Rasa Khas Sunda & Nusantara',
            'description'  => 'Hidangan tradisional Sunda dan Nusantara dengan atmosfer bakoel bambu alami yang menenangkan.',
            'cuisine_type' => 'Indonesian',
            'color_primary'=> '#634524',
            'is_active'    => true,
            'sort_order'   => 1,
        ]);

        $brand2 = Brand::create([
            'name'         => 'Shem Ramen',
            'slug'         => 'shem-ramen',
            'tagline'      => 'Ramen Autentik Jepang Halal',
            'description'  => 'Ramen kuah kental dengan cita rasa gurih autentik Jepang yang 100% halal dan disukai lidah lokal.',
            'cuisine_type' => 'Japanese',
            'color_primary'=> '#991b1b',
            'is_active'    => true,
            'sort_order'   => 2,
        ]);

        $brand3 = Brand::create([
            'name'         => 'Shem Sushi',
            'slug'         => 'shem-sushi',
            'tagline'      => 'Sushi Segar Berkualitas',
            'description'  => 'Koleksi sushi premium yang dibuat langsung oleh chef berpengalaman menggunakan bahan-bahan segar terbaik.',
            'cuisine_type' => 'Japanese',
            'color_primary'=> '#1e40af',
            'is_active'    => true,
            'sort_order'   => 3,
        ]);

        $brand4 = Brand::create([
            'name'         => 'Shem Ramen x Sushi',
            'slug'         => 'shem-ramen-x-sushi',
            'tagline'      => 'Perpaduan Terbaik Kuliner Jepang',
            'description'  => 'Konsep outlet kolaboratif yang menggabungkan kenikmatan ramen hangat dan kelembutan sushi segar dalam satu meja.',
            'cuisine_type' => 'Japanese',
            'color_primary'=> '#111827',
            'is_active'    => true,
            'sort_order'   => 4,
        ]);

        $brand5 = Brand::create([
            'name'         => 'Shem Signature',
            'slug'         => 'shem-signature',
            'tagline'      => 'Pengalaman Kuliner Jepang Premium',
            'description'  => 'Restoran premium dengan menu signature Jepang yang eksklusif, pelayanan kelas atas, dan suasana intim.',
            'cuisine_type' => 'Japanese Fine Dining',
            'color_primary'=> '#c5a059',
            'is_active'    => true,
            'sort_order'   => 5,
        ]);

        $brand6 = Brand::create([
            'name'         => 'Gokuro',
            'slug'         => 'gokuro',
            'tagline'      => 'Yakiniku & Shabu-Shabu Cepat Saji',
            'description'  => 'Nikmati yakisoba, yakiniku bowl, dan shabu-shabu dengan penyajian cepat dan harga terjangkau.',
            'cuisine_type' => 'Japanese Fast Food',
            'color_primary'=> '#0f766e',
            'is_active'    => true,
            'sort_order'   => 6,
        ]);

        // ========================
        // 2. Users (With brand tenancy)
        // ========================
        $admin = User::create([
            'brand_id'  => null, // Corporate Admin
            'name'      => 'Corporate Admin',
            'email'     => 'admin@snn.id',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'brand_id'  => $brand2->id, // Tenant Editor for Shem Ramen
            'name'      => 'Shem Ramen Editor',
            'email'     => 'editor@snn.id',
            'password'  => Hash::make('password'),
            'role'      => 'editor',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'brand_id'  => null, // Corporate HR
            'name'      => 'HR Manager',
            'email'     => 'hr@snn.id',
            'password'  => Hash::make('password'),
            'role'      => 'hr',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'brand_id'  => null,
            'name'      => 'Customer Demo',
            'email'     => 'customer@snn.id',
            'password'  => Hash::make('password'),
            'role'      => 'customer',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // ========================
        // 3. Site Settings
        // ========================
        $settings = [
            ['key' => 'hero_background_type',  'value' => 'image',  'type' => 'text',    'group' => 'hero',    'label' => 'Hero Background Type'],
            ['key' => 'hero_title_id',          'value' => '<span class="highlight">Selera Nikmat</span><br>Nusantara',  'type' => 'text', 'group' => 'hero', 'label' => 'Hero Title ID'],
            ['key' => 'hero_title_en',          'value' => '<span class="highlight">Exquisite Taste</span><br>of Nusantara', 'type' => 'text', 'group' => 'hero', 'label' => 'Hero Title EN'],
            ['key' => 'hero_subtitle_id',       'value' => 'Dari Sabang sampai Merauke, kami hadir dengan koleksi brand F&B premium yang memanjakan lidah dan jiwa.', 'type' => 'textarea', 'group' => 'hero', 'label' => 'Hero Subtitle ID'],
            ['key' => 'hero_subtitle_en',       'value' => 'From Sabang to Merauke, we present a collection of premium F&B brands that delight your palate and soul.', 'type' => 'textarea', 'group' => 'hero', 'label' => 'Hero Subtitle EN'],
            ['key' => 'hero_cta_text_id',       'value' => 'Jelajahi Brand Kami', 'type' => 'text', 'group' => 'hero', 'label' => 'CTA Text ID'],
            ['key' => 'hero_cta_text_en',       'value' => 'Explore Our Brands',  'type' => 'text', 'group' => 'hero', 'label' => 'CTA Text EN'],
            ['key' => 'about_title_id',         'value' => 'Kami Adalah <span style="color:var(--accent)">Selera Nikmat Nusantara</span>', 'type' => 'text', 'group' => 'about', 'label' => 'About Title ID'],
            ['key' => 'about_title_en',         'value' => 'We Are <span style="color:var(--accent)">Selera Nikmat Nusantara</span>', 'type' => 'text', 'group' => 'about', 'label' => 'About Title EN'],
            ['key' => 'about_text_id',          'value' => 'Selera Nikmat Nusantara adalah grup F&B yang berkomitmen menghadirkan cita rasa autentik Indonesia dengan standar kuliner premium. Kami percaya bahwa makanan adalah jembatan budaya yang menyatukan kita semua.', 'type' => 'textarea', 'group' => 'about', 'label' => 'About Text ID'],
            ['key' => 'about_text_en',          'value' => 'Selera Nikmat Nusantara is an F&B group committed to delivering authentic Indonesian flavors with premium culinary standards. We believe food is a cultural bridge that unites us all.', 'type' => 'textarea', 'group' => 'about', 'label' => 'About Text EN'],
            ['key' => 'show_promo_section',     'value' => '1', 'type' => 'boolean', 'group' => 'sections', 'label' => 'Show Promo Section'],
            ['key' => 'show_blog_section',      'value' => '1', 'type' => 'boolean', 'group' => 'sections', 'label' => 'Show Blog Section'],
            ['key' => 'show_career_section',    'value' => '1', 'type' => 'boolean', 'group' => 'sections', 'label' => 'Show Career Section'],
            ['key' => 'contact_phone',          'value' => '+62 21 1234 5678', 'type' => 'text', 'group' => 'contact', 'label' => 'Phone'],
            ['key' => 'contact_email',          'value' => 'info@seleranikmatnusantara.test', 'type' => 'text', 'group' => 'contact', 'label' => 'Email'],
            ['key' => 'social_instagram',       'value' => 'https://instagram.com/seleranikmatnusantara', 'type' => 'url', 'group' => 'contact', 'label' => 'Instagram'],
            ['key' => 'social_tiktok',          'value' => 'https://tiktok.com/@seleranikmatnusantara', 'type' => 'url', 'group' => 'contact', 'label' => 'TikTok'],
        ];

        foreach ($settings as $s) {
            SiteSetting::create($s);
        }

        // ========================
        // 4. Regions (Provinsi > Kota)
        // ========================
        $jakarta = Region::create(['name' => 'DKI Jakarta', 'type' => 'province']);
        $jabar   = Region::create(['name' => 'Jawa Barat',  'type' => 'province']);
        $jateng  = Region::create(['name' => 'Jawa Tengah', 'type' => 'province']);

        $jakarta_pusat  = Region::create(['name' => 'Jakarta Pusat',  'type' => 'city', 'parent_id' => $jakarta->id]);
        $jakarta_selatan = Region::create(['name' => 'Jakarta Selatan','type' => 'city', 'parent_id' => $jakarta->id]);
        $bandung        = Region::create(['name' => 'Bandung',          'type' => 'city', 'parent_id' => $jabar->id]);
        $semarang       = Region::create(['name' => 'Semarang',         'type' => 'city', 'parent_id' => $jateng->id]);

        // ========================
        // 5. Outlets (Provinsi > Kota > Outlet)
        // ========================
        $outlets_data = [
            ['brand_id' => $brand1->id, 'region_id' => $jakarta_pusat->id,  'name' => 'Bakoel Bamboe - Thamrin',       'address' => 'Jl. MH Thamrin No. 22, Jakarta Pusat'],
            ['brand_id' => $brand1->id, 'region_id' => $jakarta_selatan->id,'name' => 'Bakoel Bamboe - Kemang',        'address' => 'Jl. Kemang Raya No. 45, Jakarta Selatan'],
            ['brand_id' => $brand1->id, 'region_id' => $bandung->id,        'name' => 'Bakoel Bamboe - Dago',          'address' => 'Jl. Ir. H. Juanda No. 80, Bandung'],
            ['brand_id' => $brand2->id, 'region_id' => $jakarta_pusat->id,  'name' => 'Shem Ramen - Grand Indonesia',  'address' => 'Mall Grand Indonesia Lt. 5, Jakarta Pusat'],
            ['brand_id' => $brand3->id, 'region_id' => $jakarta_selatan->id,'name' => 'Shem Sushi - Senayan City',     'address' => 'Senayan City Mall Lt. LG, Jakarta Selatan'],
            ['brand_id' => $brand4->id, 'region_id' => $bandung->id,        'name' => 'Shem Ramen x Sushi - Paskal',   'address' => 'Paskal Hyper Square Ruko C-12, Bandung'],
            ['brand_id' => $brand5->id, 'region_id' => $jakarta_selatan->id,'name' => 'Shem Signature - SCBD',         'address' => 'SCBD Lot 8, Jakarta Selatan'],
            ['brand_id' => $brand6->id, 'region_id' => $semarang->id,       'name' => 'Gokuro - Semarang Town Square', 'address' => 'Semarang Town Square, Semarang'],
        ];

        foreach ($outlets_data as $o) {
            Outlet::create(array_merge($o, ['is_active' => true, 'phone' => '+62 21 ' . rand(1000, 9999) . ' ' . rand(1000, 9999)]));
        }

        // ========================
        // 6. Blog Categories & Posts
        // ========================
        $cat1 = BlogCategory::create(['name' => 'Resep & Kuliner', 'slug' => 'resep-kuliner', 'is_active' => true]);
        $cat2 = BlogCategory::create(['name' => 'Tips & Trik',     'slug' => 'tips-trik',     'is_active' => true]);
        $cat3 = BlogCategory::create(['name' => 'Berita Korporat', 'slug' => 'berita-korporat', 'is_active' => true]);

        $posts_data = [
            [
                'title'             => 'Kisah Dibalik Kelezatan Ramen Shem Ramen',
                'slug'              => 'kisah-dibalik-kelezatan-ramen-shem',
                'excerpt'           => 'Bagaimana Shem Ramen menghadirkan cita rasa ramen autentik Jepang yang 100% halal untuk masyarakat Indonesia.',
                'body'              => '<p>Shem Ramen berkomitmen menyajikan ramen halal terbaik dengan bahan berkualitas tinggi...</p>',
                'blog_category_id'  => $cat1->id,
                'status'            => 'published',
                'published_at'      => now()->subDays(3),
                'views'             => 1250,
            ],
            [
                'title'             => 'Cara Menikmati Sushi ala Tradisional Jepang',
                'slug'              => 'cara-menikmati-sushi-tradisional-jepang',
                'excerpt'           => 'Simak tips dan etiket makan sushi langsung dari chef Shem Sushi untuk pengalaman bersantap maksimal.',
                'body'              => '<p>Menikmati sushi memiliki seni tersendiri. Mulai dari urutan memakan hingga penggunaan wasabi...</p>',
                'blog_category_id'  => $cat2->id,
                'status'            => 'published',
                'published_at'      => now()->subDays(7),
                'views'             => 890,
            ],
            [
                'title'             => 'Selera Nikmat Nusantara Membuka Outlet Kolaborasi Baru',
                'slug'              => 'selera-nikmat-nusantara-buka-outlet-baru',
                'excerpt'           => 'Pembukaan outlet kolaboratif Shem Ramen x Sushi kini resmi hadir untuk memanjakan pecinta kuliner di kota Bandung.',
                'body'              => '<p>Selera Nikmat Nusantara meresmikan outlet terbaru dengan konsep gabungan ramen & sushi...</p>',
                'blog_category_id'  => $cat3->id,
                'status'            => 'published',
                'published_at'      => now()->subDays(1),
                'views'             => 2100,
            ],
        ];

        foreach ($posts_data as $p) {
            BlogPost::create(array_merge($p, ['author_id' => $admin->id]));
        }

        // ========================
        // 7. Promos
        // ========================
        Promo::create([
            'title'          => 'Diskon Pertama di Bakoel Bamboe',
            'slug'           => 'diskon-pertama-bakoel-bamboe',
            'description'    => 'Nikmati diskon 30% untuk pembelian pertama Anda di semua outlet Bakoel Bamboe. Cukup klaim voucher digital ini!',
            'terms'          => 'Berlaku untuk dine-in pertama kali. Min. transaksi Rp 100.000.',
            'start_date'     => now()->subDay(),
            'end_date'       => now()->addMonth(),
            'max_claims'     => 500,
            'promo_type'     => 'percentage',
            'discount_value' => 30,
            'discount_label' => 'DISKON 30%',
            'status'         => 'active',
        ]);

        Promo::create([
            'title'          => 'Free Gyoza di Shem Ramen',
            'slug'           => 'free-gyoza-shem-ramen',
            'description'    => 'Beli ramen varian apa saja di Shem Ramen dan dapatkan free gyoza dengan menunjukkan kupon ini.',
            'terms'          => 'Berlaku di semua outlet Shem Ramen. Min. pembelian 1 ramen main course.',
            'start_date'     => now(),
            'end_date'       => now()->addWeeks(2),
            'max_claims'     => 200,
            'promo_type'     => 'free_item',
            'discount_label' => 'FREE GYOZA',
            'status'         => 'active',
        ]);

        Promo::create([
            'title'          => 'Weekend Ocha Free Flow',
            'slug'           => 'weekend-ocha-free-flow',
            'description'    => 'Makan sushi di Shem Sushi setiap akhir pekan gratis ocha dingin/hangat sepuasnya.',
            'terms'          => 'Hanya berlaku Sabtu & Minggu di seluruh outlet Shem Sushi.',
            'start_date'     => now(),
            'end_date'       => now()->addMonths(2),
            'promo_type'     => 'free_item',
            'discount_label' => 'FREE OCHA',
            'status'         => 'active',
        ]);

        // ========================
        // 8. Career
        // ========================
        $hrCat    = JobCategory::create(['name' => 'Operasional',    'slug' => 'operasional']);
        $techCat  = JobCategory::create(['name' => 'Manajemen',      'slug' => 'manajemen']);
        $kitchCat = JobCategory::create(['name' => 'Kitchen & Chef', 'slug' => 'kitchen-chef']);
        $mktCat   = JobCategory::create(['name' => 'Marketing',      'slug' => 'marketing']);

        $jobs = [
            [
                'title'           => 'Sous Chef — Bakoel Bamboe',
                'slug'            => 'sous-chef-bakoel-bamboe',
                'job_category_id' => $kitchCat->id,
                'brand_id'        => $brand1->id,
                'description'     => '<p>Mencari Sous Chef berpengalaman masakan Nusantara.</p>',
                'requirements'    => '<ul><li>Pengalaman min. 3 tahun di kitchen</li><li>Memahami food cost & safety</li></ul>',
                'benefits'        => '<ul><li>Gaji kompetitif</li><li>Asuransi kesehatan</li></ul>',
                'location'        => 'Jakarta',
                'salary_range'    => 'Rp 7.000.000 - Rp 11.000.000',
                'type'            => 'fulltime',
                'status'          => 'open',
                'deadline'        => now()->addMonth(),
            ],
            [
                'title'           => 'Assistant Store Manager — Shem Ramen',
                'slug'            => 'assistant-store-manager-shem-ramen',
                'job_category_id' => $techCat->id,
                'brand_id'        => $brand2->id,
                'description'     => '<p>Mencari asisten manajer outlet ramen yang dinamis.</p>',
                'requirements'    => '<ul><li>Pendidikan min. D3</li><li>Pengalaman di F&B hospitality</li></ul>',
                'benefits'        => '<ul><li>Gaji tetap + insentif</li></ul>',
                'location'        => 'Jakarta Pusat',
                'salary_range'    => 'Rp 6.000.000 - Rp 9.000.000',
                'type'            => 'fulltime',
                'status'          => 'open',
                'deadline'        => now()->addMonths(2),
            ],
            [
                'title'           => 'Social Media Specialist',
                'slug'            => 'social-media-specialist-corporate',
                'job_category_id' => $mktCat->id,
                'brand_id'        => null,
                'description'     => '<p>Mengelola sosial media seluruh brand Selera Nikmat Nusantara.</p>',
                'requirements'    => '<ul><li>Kreatif, memahami tren TikTok/IG</li><li>Bisa edit video singkat</li></ul>',
                'location'        => 'Jakarta (Corporate)',
                'salary_range'    => 'Rp 5.500.000 - Rp 8.000.000',
                'type'            => 'fulltime',
                'status'          => 'open',
                'deadline'        => now()->addMonth(),
            ],
        ];

        foreach ($jobs as $j) {
            CareerJob::create($j);
        }
    }
}
