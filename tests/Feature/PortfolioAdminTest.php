<?php

namespace Tests\Feature;

use App\Models\Experience;
use App\Models\PortfolioSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_portfolio_renders_with_seeded_experiences(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Klent Russell Aguilar')
            ->assertSee('BS Information Technology');
    }

    public function test_guest_is_redirected_from_admin_settings(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_update_public_profile_content(): void
    {
        $payload = [
            'name' => 'Jane Portfolio',
            'location' => 'Manila, Philippines',
            'role' => 'Product Designer',
            'email' => 'jane@example.test',
            'phone' => '0917 000 0000',
            'about' => "First paragraph.\n\nSecond paragraph.",
        ];

        $this->withSession(['admin_authenticated' => true])
            ->put(route('admin.profile.update'), $payload)
            ->assertRedirect()
            ->assertSessionHas('status', 'Profile information saved.');

        $this->assertDatabaseHas('portfolio_settings', ['key' => 'name', 'value' => 'Jane Portfolio']);
        $this->assertDatabaseHas('portfolio_settings', ['key' => 'email', 'value' => 'jane@example.test']);

        $this->get('/')
            ->assertOk()
            ->assertSee('Jane Portfolio')
            ->assertSee('Product Designer')
            ->assertSee('First paragraph.');
    }

    public function test_admin_can_add_edit_and_remove_experience(): void
    {
        $admin = $this->withSession(['admin_authenticated' => true]);

        $admin->post(route('admin.experience.store'), [
            'title' => 'Junior Developer',
            'detail' => 'Portfolio platform',
            'year' => '2026',
        ])->assertRedirect()->assertSessionHas('status', 'Experience entry added.');

        $experience = Experience::query()->where('title', 'Junior Developer')->firstOrFail();
        $this->assertDatabaseHas('experiences', ['id' => $experience->id, 'detail' => 'Portfolio platform']);

        $admin->put(route('admin.experience.update', $experience), [
            'title' => 'Web Developer',
            'detail' => 'Updated portfolio platform',
            'year' => '2027',
        ])->assertRedirect()->assertSessionHas('status', 'Experience entry updated.');

        $this->assertDatabaseHas('experiences', ['id' => $experience->id, 'title' => 'Web Developer', 'year' => '2027']);

        $admin->delete(route('admin.experience.destroy', $experience))
            ->assertRedirect()
            ->assertSessionHas('status', 'Experience entry removed.');

        $this->assertDatabaseMissing('experiences', ['id' => $experience->id]);
    }
}
