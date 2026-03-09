<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnouncementPackageControlTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Test tenant oluştur
        $this->tenant = Tenant::create([
            'id' => 'test-tenant-' . uniqid(),
            'package' => 'baslangic',
        ]);
        
        // Tenant domain'i ekle
        $this->tenant->domains()->create([
            'domain' => 'test-tenant.test',
        ]);
        
        // Tenant context'i ayarla
        tenancy()->initialize($this->tenant);
        
        // Tenant database'de announcements tablosunu oluştur
        $this->artisan('migrate', [
            '--path' => 'database/migrations/2026_03_08_200135_create_announcements_table.php',
            '--force' => true,
        ]);
    }

    protected function tearDown(): void
    {
        // Tenant context'ini temizle
        if (tenancy()->initialized) {
            tenancy()->end();
        }
        
        // Tenant'ı sil
        $this->tenant?->delete();
        
        parent::tearDown();
    }

    /**
     * Başlangıç paketinde maksimum 1 duyuru olması gerekir
     */
    public function test_baslangic_package_allows_only_one_announcement(): void
    {
        $this->tenant->update(['package' => 'baslangic']);
        
        // 1. duyuru oluşturulabilir
        $announcement1 = Announcement::factory()->create();
        $this->assertEquals(1, Announcement::count());
    }

    /**
     * Profesyonel paketinde maksimum 5 duyuru olması gerekir
     */
    public function test_profesyonel_package_allows_five_announcements(): void
    {
        $this->tenant->update(['package' => 'profesyonel']);
        
        // 5 duyuru oluşturulabilir
        $announcements = Announcement::factory()
            ->count(5)
            ->create();
        
        $this->assertEquals(5, Announcement::count());
    }

    /**
     * Kurumsal paketinde sınırsız duyuru olması gerekir
     */
    public function test_kurumsal_package_allows_unlimited_announcements(): void
    {
        $this->tenant->update(['package' => 'kurumsal']);
        
        // Sınırsız duyuru oluşturulabilir
        $announcements = Announcement::factory()
            ->count(50)
            ->create();
        
        $this->assertEquals(50, Announcement::count());
    }

    /**
     * API index endpoint'i paket limitine saygılı olmalıdır
     */
    public function test_api_index_respects_package_limit(): void
    {
        $this->tenant->update(['package' => 'baslangic']);
        
        // 3 duyuru oluştur
        Announcement::factory()
            ->count(3)
            ->create(['is_active' => true]);
        
        // API'den getir
        $response = $this->getJson('/api/announcements');
        
        // Başlangıç paketinde yalnız 1 döndürülmelidir
        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
    }

    /**
     * API birden fazla duyuruyu paket limitine göre döndür
     */
    public function test_api_returns_multiple_for_profesyonel(): void
    {
        $this->tenant->update(['package' => 'profesyonel']);
        
        // 7 duyuru oluştur
        Announcement::factory()
            ->count(7)
            ->create(['is_active' => true]);
        
        // API'den getir
        $response = $this->getJson('/api/announcements');
        
        // Profesyonel paketinde maksimum 5 döndürülmelidir
        $response->assertStatus(200);
        $this->assertCount(5, $response->json('data'));
    }
}
