<?php

namespace Tests\Feature;

use App\Mail\WebsiteRequestMail;
use App\Models\User;
use App\Models\WebsiteRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class WebsiteRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_website_page_is_available(): void
    {
        $response = $this->get('/request-website');

        $response->assertStatus(200);
        $response->assertSee('FORM KEBUTUHAN WEBSITE');
        $response->assertSee('Estimasi Harga');
    }

    public function test_request_can_be_submitted_and_stored(): void
    {
        Mail::fake();
        Http::fake([
            'https://api.twilio.com/*' => Http::response(['sid' => 'SM123'], 200),
        ]);

        $response = $this->post('/request-website', [
            'nama' => 'Budi Santoso',
            'wa' => '081234567890',
            'email' => 'budi@example.com',
            'nama_website' => 'Toko Budi',
            'tujuan_website' => 'Meningkatkan penjualan online',
            'deskripsi_usaha' => 'Toko kebutuhan rumah tangga',
        ]);

        $response->assertRedirect('/request-website');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('website_requests', [
            'nama' => 'Budi Santoso',
            'email' => 'budi@example.com',
        ]);

        Mail::assertSent(WebsiteRequestMail::class);
    }

    public function test_admin_can_view_submitted_requests(): void
    {
        WebsiteRequest::create([
            'nama' => 'Ani Rahma',
            'wa' => '081111111111',
            'email' => 'ani@example.com',
            'nama_website' => 'Studio Ani',
            'tujuan_website' => 'Meningkatkan branding',
        ]);

        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();
        $response = $this->actingAs($admin)->get('/admin/website-requests');

        $response->assertStatus(200);
        $response->assertSee('Ani Rahma');
        $response->assertSee('ani@example.com');
    }

    public function test_reports_are_not_public_and_reject_invalid_contact_data(): void
    {
        $this->get('/laporan-website')->assertRedirect('/login');
        $this->post('/request-website', ['nama' => 'Budi', 'wa' => '<script>'])
            ->assertSessionHasErrors('wa');
    }
}
