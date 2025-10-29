<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\ModuleLog;
use App\Models\ModuleVerification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ModuleManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $superadmin;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        Role::create(['name' => 'Superadmin']);
        Role::create(['name' => 'Admin']);

        // Create users
        $this->superadmin = User::factory()->create();
        $this->superadmin->assignRole('Superadmin');

        $this->admin = User::factory()->create();
        $this->admin->assignRole('Admin');
    }

    /** @test */
    public function superadmin_can_view_modules_index()
    {
        $response = $this->actingAs($this->superadmin)
            ->get(route('superadmin.modules.index'));

        $response->assertStatus(200);
        $response->assertViewIs('modules.index');
    }

    /** @test */
    public function non_superadmin_cannot_view_modules_index()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('superadmin.modules.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function superadmin_can_request_module_toggle()
    {
        Mail::fake();

        $module = Module::factory()->create(['status' => true]);

        $response = $this->actingAs($this->superadmin)
            ->post(route('superadmin.modules.request-toggle', $module));

        $response->assertRedirect();
        $response->assertSessionHas('info');

        // Check verification was created
        $this->assertDatabaseHas('module_verifications', [
            'user_id' => $this->superadmin->id,
            'module_id' => $module->id,
            'action' => 'desactivar',
        ]);

        // Check email was sent
        Mail::assertSent(\App\Mail\ModuleVerificationMail::class);
    }

    /** @test */
    public function superadmin_can_confirm_module_toggle_with_valid_code()
    {
        $module = Module::factory()->create(['status' => true]);

        $verification = ModuleVerification::createForModule(
            $this->superadmin->id,
            $module->id,
            'desactivar'
        );

        $response = $this->actingAs($this->superadmin)
            ->post(route('superadmin.modules.confirm', $module), [
                'verification_code' => $verification->verification_code,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Check module was deactivated
        $this->assertDatabaseHas('modules', [
            'id' => $module->id,
            'status' => false,
        ]);

        // Check verification was marked as used
        $this->assertDatabaseHas('module_verifications', [
            'id' => $verification->id,
            'used_at' => now(),
        ]);

        // Check log was created
        $this->assertDatabaseHas('module_logs', [
            'user_id' => $this->superadmin->id,
            'module_id' => $module->id,
            'action' => 'deactivated',
        ]);
    }

    /** @test */
    public function superadmin_cannot_confirm_with_invalid_code()
    {
        $module = Module::factory()->create(['status' => true]);

        $response = $this->actingAs($this->superadmin)
            ->post(route('superadmin.modules.confirm', $module), [
                'verification_code' => '123456',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        // Check module status didn't change
        $this->assertDatabaseHas('modules', [
            'id' => $module->id,
            'status' => true,
        ]);
    }

    /** @test */
    public function superadmin_can_view_module_logs()
    {
        $module = Module::factory()->create();

        ModuleLog::createLog(
            $this->superadmin->id,
            $module->id,
            'activated',
            '127.0.0.1',
            'Test User Agent'
        );

        $response = $this->actingAs($this->superadmin)
            ->get(route('superadmin.modules.logs', $module));

        $response->assertStatus(200);
        $response->assertViewIs('modules.logs');
        $response->assertViewHas('module', $module);
        $response->assertViewHas('logs');
    }

    /** @test */
    public function superadmin_can_view_all_logs()
    {
        $module = Module::factory()->create();

        ModuleLog::createLog(
            $this->superadmin->id,
            $module->id,
            'activated',
            '127.0.0.1',
            'Test User Agent'
        );

        $response = $this->actingAs($this->superadmin)
            ->get(route('superadmin.modules.all-logs'));

        $response->assertStatus(200);
        $response->assertViewIs('modules.all-logs');
    }

    /** @test */
    public function rate_limiting_works_for_module_toggle()
    {
        $module = Module::factory()->create(['status' => true]);

        // Make 6 requests (limit is 5 per minute)
        for ($i = 0; $i < 6; $i++) {
            $response = $this->actingAs($this->superadmin)
                ->post(route('superadmin.modules.request-toggle', $module));
        }

        // Last request should be rate limited
        $response->assertStatus(429);
    }
}
