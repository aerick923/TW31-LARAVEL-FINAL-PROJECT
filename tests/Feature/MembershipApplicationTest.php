<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\MembershipCategory;
use App\Models\MembershipLevel;
use App\Notifications\MembershipApplicationSubmitted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class MembershipApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_application_is_stored_and_email_notification_is_sent(): void
    {
        Notification::fake();

        // Create an authenticated and verified regular user.
        $user = User::factory()->create([
            'name' => 'Ana Cruz',
            'email' => 'ana@example.test',
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // Create an active membership category.
        $category = MembershipCategory::create([
            'name' => 'Regular',
            'fee' => 500,
            'is_active' => true,
        ]);

        // Create a membership level.
        $level = MembershipLevel::create([
            'name' => 'Basic',
            'fee' => 500,
        ]);

        // Authenticate before submitting the application.
        $this->actingAs($user);

        $response = $this->post(route('membership.store'), [
            'membership_category_id' => $category->id,
            'membership_level_id' => $level->id,
            'full_name' => 'Ana Cruz',
            'email' => 'ana@example.test',
            'phone' => '09123456789',
            'address' => 'Manila, Philippines',
            'latitude' => 14.5995,
            'longitude' => 120.9842,
        ]);

        // Successful applications return to the user dashboard.
        $response->assertRedirect(route('user.dashboard'));

        // Verify that the application was saved.
        $this->assertDatabaseHas('membership_applications', [
            'user_id' => $user->id,
            'email' => 'ana@example.test',
            'membership_category_id' => $category->id,
            'membership_level_id' => $level->id,
            'status' => 'pending',
        ]);

        // Verify that an email notification was dispatched.
        Notification::assertSentOnDemand(
            MembershipApplicationSubmitted::class
        );
    }
}
