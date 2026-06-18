<?php

namespace Tests\Feature;

use App\Models\Lapangan;
use App\Models\Booking;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_guest_can_open_dashboard_without_being_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');

        $response->assertOk();
        $response->assertViewIs('lapangan.index');
    }

    public function test_guest_can_open_booking_guide(): void
    {
        $response = $this->get('/cara-booking');

        $response->assertOk();
        $response->assertViewIs('booking.guide');
        $response->assertSee('Cara Booking Lapangan');
    }

    public function test_user_gets_popup_message_when_toggling_favorite(): void
    {
        $user = User::factory()->create(['role' => 0]);
        $lapangan = Lapangan::create([
            'nama_lapangan' => 'GOR Test Bandung',
            'tipe_rumput' => 'Sintetis',
            'lokasi' => 'Bandung Pusat',
            'harga_per_jam' => 100000,
            'fasilitas' => 'Parkir',
            'deskripsi' => 'Lapangan untuk pengujian.',
        ]);

        $addResponse = $this->actingAs($user)
            ->from('/dashboard')
            ->post(route('lapangan.favorite', $lapangan->lapangan_id));

        $addResponse
            ->assertRedirect('/dashboard')
            ->assertSessionHas('favorite_status', 'added')
            ->assertSessionHas('favorite_message', 'GOR Test Bandung ditambahkan ke GOR favorit.');
        $this->assertDatabaseHas('favorite_lapangans', [
            'user_id' => $user->id,
            'lapangan_id' => $lapangan->lapangan_id,
        ]);

        $removeResponse = $this->actingAs($user)
            ->from('/dashboard')
            ->post(route('lapangan.favorite', $lapangan->lapangan_id));

        $removeResponse
            ->assertRedirect('/dashboard')
            ->assertSessionHas('favorite_status', 'removed')
            ->assertSessionHas('favorite_message', 'GOR Test Bandung dihapus dari GOR favorit.');
        $this->assertDatabaseMissing('favorite_lapangans', [
            'user_id' => $user->id,
            'lapangan_id' => $lapangan->lapangan_id,
        ]);
    }

    public function test_dashboard_can_sort_fields_by_price_rating_and_popularity(): void
    {
        $user = User::factory()->create(['role' => 0]);
        $cheap = $this->createLapangan('GOR Murah', 50000);
        $popular = $this->createLapangan('GOR Populer', 100000);
        $highRated = $this->createLapangan('GOR Rating Tinggi', 150000);

        $cheapBooking = $this->createBooking($user, $cheap, 'Success');
        $popularBookingOne = $this->createBooking($user, $popular, 'Success');
        $popularBookingTwo = $this->createBooking($user, $popular, 'Success');
        $highRatedBookingOne = $this->createBooking($user, $highRated, 'Pending');
        $highRatedBookingTwo = $this->createBooking($user, $highRated, 'Pending');

        Review::create([
            'booking_id' => $cheapBooking->id,
            'user_id' => $user->id,
            'lapangan_id' => $cheap->lapangan_id,
            'rating' => 5,
        ]);
        Review::create([
            'booking_id' => $popularBookingOne->id,
            'user_id' => $user->id,
            'lapangan_id' => $popular->lapangan_id,
            'rating' => 4,
        ]);
        Review::create([
            'booking_id' => $popularBookingTwo->id,
            'user_id' => $user->id,
            'lapangan_id' => $popular->lapangan_id,
            'rating' => 5,
        ]);
        Review::create([
            'booking_id' => $highRatedBookingOne->id,
            'user_id' => $user->id,
            'lapangan_id' => $highRated->lapangan_id,
            'rating' => 5,
        ]);
        Review::create([
            'booking_id' => $highRatedBookingTwo->id,
            'user_id' => $user->id,
            'lapangan_id' => $highRated->lapangan_id,
            'rating' => 5,
        ]);

        $this->get('/dashboard?sort=price_asc')
            ->assertOk()
            ->assertSeeInOrder(['GOR Murah', 'GOR Populer', 'GOR Rating Tinggi']);

        $this->get('/dashboard?sort=rating_desc')
            ->assertOk()
            ->assertSeeInOrder(['GOR Rating Tinggi', 'GOR Murah', 'GOR Populer']);

        $this->get('/dashboard?sort=popular_desc')
            ->assertOk()
            ->assertSeeInOrder(['GOR Populer', 'GOR Murah', 'GOR Rating Tinggi']);
    }

    public function test_approved_membership_is_active_for_thirty_days_then_expires(): void
    {
        Carbon::setTestNow('2026-06-13 10:00:00');

        $admin = User::factory()->create(['role' => 1]);
        $user = User::factory()->create([
            'role' => 0,
            'is_member' => 2,
            'membership_requested_at' => now()->subDay(),
        ]);

        $this->actingAs($admin)
            ->from('/admin/members')
            ->post(route('admin.members.update', $user->id), ['is_member' => 1])
            ->assertRedirect('/admin/members');

        $user->refresh();

        $this->assertTrue($user->isActiveMember());
        $this->assertSame('2026-06-13 10:00:00', $user->membership_started_at->format('Y-m-d H:i:s'));
        $this->assertSame('2026-07-13 10:00:00', $user->membership_expires_at->format('Y-m-d H:i:s'));

        Carbon::setTestNow('2026-07-14 10:00:00');
        $user->refresh();

        $this->assertFalse($user->isActiveMember());
        $this->assertTrue($user->isExpiredMember());
        $this->assertSame('Membership Kedaluwarsa', $user->membershipLabel());

        Carbon::setTestNow();
    }

    public function test_admin_cannot_change_membership_without_pending_application(): void
    {
        $admin = User::factory()->create(['role' => 1]);
        $user = User::factory()->create(['role' => 0, 'is_member' => 0]);

        $this->actingAs($admin)
            ->from('/admin/members')
            ->post(route('admin.members.update', $user->id), ['is_member' => 1])
            ->assertRedirect('/admin/members')
            ->assertSessionHas('error', 'Hanya pengajuan pending yang dapat diproses.');

        $this->assertFalse($user->refresh()->isActiveMember());
    }

    public function test_admin_dashboard_uses_verified_operational_statistics(): void
    {
        $admin = User::factory()->create(['role' => 1]);
        User::factory()->create([
            'role' => 0,
            'is_member' => 1,
            'membership_started_at' => now()->subDays(5),
            'membership_expires_at' => now()->addDays(25),
        ]);
        User::factory()->create([
            'role' => 0,
            'is_member' => 1,
            'membership_started_at' => now()->subDays(40),
            'membership_expires_at' => now()->subDays(10),
        ]);

        $customer = User::factory()->create(['role' => 0]);
        $lapangan = $this->createLapangan('GOR Statistik', 100000);
        $this->createBooking($customer, $lapangan, 'Success');
        $this->createBooking($customer, $lapangan, 'Pending');
        $this->createBooking($customer, $lapangan, 'Cancelled');

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response
            ->assertOk()
            ->assertViewHas('totalPendapatan', 100000)
            ->assertViewHas('totalBooking', 3)
            ->assertViewHas('bookingPending', 1)
            ->assertViewHas('totalMember', 1)
            ->assertViewHas('bookingTerbaru', fn ($bookings) => $bookings->count() === 3)
            ->assertSee('Selamat Datang, ' . $admin->name)
            ->assertDontSee('Daftar Pengguna Sistem');
    }

    public function test_revenue_report_only_contains_successful_bookings_and_groups_by_field(): void
    {
        $admin = User::factory()->create(['role' => 1]);
        $customer = User::factory()->create(['role' => 0]);
        $gorKoni = $this->createLapangan('GOR KONI', 300000);
        $gorBdg = $this->createLapangan('35 BDG', 114000);

        $successKoni = $this->createBooking($customer, $gorKoni, 'Success');
        $successKoni->update(['kode_tiket' => 'FH-AB1234']);
        $successBdg = $this->createBooking($customer, $gorBdg, 'Success');
        $successBdg->update(['kode_tiket' => 'FH-CD5678']);
        $pending = $this->createBooking($customer, $gorKoni, 'Pending');
        $pending->update(['total_harga' => 999000]);

        $response = $this->actingAs($admin)->get(route('admin.pendapatan'));

        $response
            ->assertOk()
            ->assertViewHas('totalPendapatan', 414000)
            ->assertViewHas('bookings', fn ($bookings) => $bookings->count() === 2)
            ->assertViewHas('pendapatanPerLapangan', function ($revenue) {
                return $revenue->get('GOR KONI') === 300000
                    && $revenue->get('35 BDG') === 114000;
            })
            ->assertSee('FH-AB1234')
            ->assertSee('FH-CD5678')
            ->assertDontSee('Rp 999.000');
    }

    private function createLapangan(string $name, int $price): Lapangan
    {
        return Lapangan::create([
            'nama_lapangan' => $name,
            'tipe_rumput' => 'Sintetis',
            'lokasi' => 'Bandung Pusat',
            'harga_per_jam' => $price,
            'fasilitas' => 'Parkir',
            'deskripsi' => 'Lapangan untuk pengujian.',
        ]);
    }

    private function createBooking(User $user, Lapangan $lapangan, string $status): Booking
    {
        return Booking::create([
            'user_id' => $user->id,
            'lapangan_id' => $lapangan->lapangan_id,
            'nama_gor' => $lapangan->nama_lapangan,
            'tgl_main' => now()->addDay()->toDateString(),
            'jam_mulai' => '08:00',
            'durasi' => 1,
            'total_harga' => $lapangan->harga_per_jam,
            'status' => $status,
        ]);
    }
}
