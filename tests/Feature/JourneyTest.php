<?php

namespace Tests\Feature;

use App\Models\Court;
use App\Models\CourtBooking;
use App\Models\InventarisKategori;
use App\Models\Opportunity;
use App\Models\PklApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class JourneyTest extends TestCase
{
    use RefreshDatabase;

    private function person(string $role = 'student'): User
    {
        $u = User::factory()->create();
        $u->forceFill(['role' => $role])->save();

        return $u;
    }

    private function job(User $owner): Opportunity
    {
        return Opportunity::create(['user_id' => $owner->id, 'title' => 'Frontend Intern', 'company' => 'Test Studio', 'city' => 'Bandung', 'category' => 'Web', 'description' => 'Belajar membuat aplikasi web bersama mentor.', 'deadline' => today()->addDays(10)->toDateString(), 'capacity' => 1]);
    }

    public function test_public_pages_and_protected_redirects(): void
    {
        foreach (['/', '/pkl', '/booking', '/login'] as $url) {
            $this->get($url)->assertOk();
        }
        foreach (['/pkl/dashboard', '/pkl/create', '/inventaris'] as $url) {
            $this->get($url)->assertRedirect('/login');
        }
        $this->get('/bulan-10')->assertRedirect('/booking');
    }

    public function test_registration_cannot_assign_privileged_role_and_login_works(): void
    {
        $this->post('/register', ['name' => 'Siswa Baru', 'email' => 'siswa@example.test', 'password' => 'TestingPass123!', 'password_confirmation' => 'TestingPass123!', 'role' => 'admin'])->assertRedirect('/pkl/dashboard');
        $this->assertDatabaseHas('users', ['email' => 'siswa@example.test', 'role' => 'student']);
        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();
        $this->post('/login', ['email' => 'siswa@example.test', 'password' => 'TestingPass123!'])->assertRedirect('/pkl/dashboard');
        $this->assertAuthenticated();
    }

    public function test_booking_conflicts_price_ownership_and_cancel(): void
    {
        $u = $this->person();
        $other = $this->person();
        $court = Court::create(['name' => 'A', 'sport' => 'Futsal', 'price' => 120000]);
        $data = ['court_id' => $court->id, 'date' => today()->addDay()->toDateString(), 'hour' => 10, 'price' => 1];
        $this->actingAs($u)->post('/booking', $data)->assertSessionHasNoErrors();
        $booking = CourtBooking::firstOrFail();
        $this->assertSame(120000, $booking->price);
        $this->actingAs($other)->post('/booking', $data)->assertSessionHasErrors('hour');
        $this->delete('/booking/'.$booking->id)->assertForbidden();
        $this->actingAs($u)->get('/booking?date='.$data['date'])->assertOk()->assertSee('A');
        $this->delete('/booking/'.$booking->id)->assertRedirect();
        $this->assertDatabaseCount('court_bookings', 0);
        $this->post('/booking', $data)->assertSessionHasNoErrors();
        $this->post('/booking', array_replace($data, ['date' => today()->subDay()->toDateString()]))->assertSessionHasErrors('date');
    }

    public function test_pkl_upload_visibility_decision_and_exports(): void
    {
        Storage::fake('local');
        $owner = $this->person('company');
        $student = $this->person();
        $outsider = $this->person('company');
        $job = $this->job($owner);
        $this->get('/pkl?q=Frontend&city=Bandung')->assertOk()->assertSee('Frontend Intern');
        $this->get('/pkl?q=Missing')->assertOk()->assertDontSee('Frontend Intern');
        $this->actingAs($student)->post('/pkl/jobs/'.$job->id.'/apply', ['motivation' => 'Saya ingin belajar pengembangan web dengan mentor.', 'cv' => UploadedFile::fake()->create('cv.pdf', 10, 'application/pdf')])->assertRedirect('/pkl/dashboard');
        $app = PklApplication::firstOrFail();
        Storage::disk('local')->assertExists($app->cv_path);
        $this->get('/pkl/applications/'.$app->id.'/cv')->assertOk();
        $this->get('/pkl/dashboard')->assertOk()->assertSee('Frontend Intern');
        $this->post('/pkl/jobs/'.$job->id.'/apply', ['motivation' => 'Saya ingin belajar pengembangan web dengan mentor.', 'cv' => UploadedFile::fake()->create('cv.pdf', 10, 'application/pdf')])->assertSessionHasErrors('cv');
        $this->get('/pkl/create')->assertForbidden();
        $this->patch('/pkl/applications/'.$app->id, ['status' => 'accepted'])->assertForbidden();
        $this->actingAs($outsider)->get('/pkl/applications/'.$app->id.'/cv')->assertForbidden();
        $this->get('/pkl/dashboard')->assertOk()->assertDontSee($student->email);
        $this->get('/pkl/jobs/'.$job->id.'/edit')->assertForbidden();
        $this->actingAs($owner)->patch('/pkl/applications/'.$app->id, ['status' => 'accepted'])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('pkl_applications', ['id' => $app->id, 'status' => 'accepted']);
        $second = PklApplication::create(['user_id' => $outsider->id, 'opportunity_id' => $job->id, 'motivation' => 'Test', 'cv_path' => 'test.pdf']);
        $this->patch('/pkl/applications/'.$second->id, ['status' => 'accepted'])->assertSessionHasErrors('status');
        $this->get('/pkl/dashboard')->assertOk()->assertSee($student->email);
        $pdf = $this->get('/pkl/export/pdf')->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-', $pdf->getContent());
        $excel = $this->get('/pkl/export/excel')->assertOk()->assertSee($student->email);
        $this->assertNotFalse(simplexml_load_string($excel->getContent()));
        $this->actingAs($this->person())->get('/pkl/export/excel')->assertDontSee($student->email);
    }

    public function test_closed_jobs_and_non_pdf_upload_are_rejected(): void
    {
        Storage::fake('local');
        $job = $this->job($this->person('company'));
        $this->actingAs($this->person())->post('/pkl/jobs/'.$job->id.'/apply', ['motivation' => str_repeat('x', 30), 'cv' => UploadedFile::fake()->create('bad.php', 1, 'text/plain')])->assertSessionHasErrors('cv');
        $job->update(['active' => false]);
        $this->post('/pkl/jobs/'.$job->id.'/apply', [])->assertStatus(422);
        $this->assertDatabaseCount('pkl_applications', 0);
    }

    public function test_company_can_create_edit_close_and_admin_can_manage(): void
    {
        $owner = $this->person('company');
        $data = ['title' => 'Intern QA', 'company' => 'Studio', 'city' => 'Solo', 'category' => 'Teknologi', 'description' => 'Belajar menguji perangkat lunak.', 'deadline' => today()->addDays(5)->toDateString(), 'capacity' => 3, 'active' => 1];
        $this->actingAs($owner)->get('/pkl/create')->assertOk();
        $this->post('/pkl/create', $data)->assertRedirect('/pkl/dashboard');
        $job = Opportunity::firstOrFail();
        $this->get('/pkl/jobs/'.$job->id.'/edit')->assertOk();
        $this->put('/pkl/jobs/'.$job->id, array_replace($data, ['active' => 0]))->assertRedirect('/pkl/dashboard');
        $this->get('/pkl')->assertDontSee('Intern QA');
        $this->actingAs($this->person('admin'))->get('/pkl/jobs/'.$job->id.'/edit')->assertOk();
    }

    public function test_inventory_dashboard_and_crud(): void
    {
        $this->actingAs($this->person())->get('/inventaris')->assertOk();
        $this->post('/inventaris/kategori', ['nama_kategori' => 'Elektronik', 'kode_prefix' => 'ELK', 'deskripsi' => 'Alat elektronik'])->assertRedirect();
        $category = InventarisKategori::firstOrFail();
        $this->post('/inventaris/barang', ['nama_barang' => 'Laptop belajar', 'kategori_id' => $category->id, 'jumlah' => 2, 'kondisi' => 'baik', 'lokasi' => 'Lab', 'tanggal_masuk' => today()->toDateString()])->assertRedirect();
        $this->get('/inventaris')->assertOk()->assertSee('Laptop belajar');
        $this->get('/inventaris/barang?search=Laptop')->assertOk()->assertSee('Laptop belajar');
        $this->get('/inventaris/laporan')->assertOk();
    }
}
