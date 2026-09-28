<?php

namespace Tests\Unit;

use App\Models\Module;
use App\Models\ModuleLog;
use App\Models\ModuleVerification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ModuleTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function module_has_required_fillable_fields()
    {
        $module = new Module();
        $fillable = $module->getFillable();

        $this->assertContains('name', $fillable);
        $this->assertContains('slug', $fillable);
        $this->assertContains('description', $fillable);
        $this->assertContains('status', $fillable);
    }

    #[Test]
    public function module_has_status_cast_to_boolean()
    {
        $module = Module::factory()->createQuietly(['status' => 1]);

        $this->assertTrue($module->status);
        $this->assertIsBool($module->status);
    }

    #[Test]
    public function module_auto_generates_slug_on_creation()
    {
        $this->actingAs(User::factory()->create());

        $module = Module::create([
            'name' => 'Test Module',
            'description' => 'Test Description',
            'status' => true,
        ]);

        $this->assertEquals('test-module', $module->slug);
    }

    #[Test]
    public function module_scope_active_works()
    {
        Module::factory()->createQuietly(['status' => true]);
        Module::factory()->createQuietly(['status' => false]);

        $activeModules = Module::active()->get();

        $this->assertCount(1, $activeModules);
        $this->assertTrue($activeModules->first()->status);
    }

    #[Test]
    public function module_log_can_be_created()
    {
        $user = User::factory()->create();
        $module = Module::factory()->createQuietly();

        $log = ModuleLog::createLog(
            $user->id,
            $module->id,
            'activated',
            '127.0.0.1',
            'Test User Agent'
        );

        $this->assertDatabaseHas('module_logs', [
            'user_id' => $user->id,
            'module_id' => $module->id,
            'action' => 'activated',
            'ip_address' => '127.0.0.1',
        ]);

        $this->assertInstanceOf(ModuleLog::class, $log);
    }

    #[Test]
    public function module_log_has_relationships()
    {
        $user = User::factory()->create();
        $module = Module::factory()->createQuietly();

        $log = ModuleLog::createLog(
            $user->id,
            $module->id,
            'activated',
            '127.0.0.1',
            'Test User Agent'
        );

        $this->assertInstanceOf(User::class, $log->user);
        $this->assertInstanceOf(Module::class, $log->module);
        $this->assertEquals($user->id, $log->user->id);
        $this->assertEquals($module->id, $log->module->id);
    }

    #[Test]
    public function module_verification_can_be_created()
    {
        $user = User::factory()->create();
        $module = Module::factory()->createQuietly();

        $verification = ModuleVerification::createForModule(
            $user->id,
            $module->id,
            'activate'
        );

        $this->assertDatabaseHas('module_verifications', [
            'user_id' => $user->id,
            'module_id' => $module->id,
            'action' => 'activate',
        ]);

        $this->assertInstanceOf(ModuleVerification::class, $verification);
        $this->assertEquals(6, strlen($verification->verification_code));
    }

    #[Test]
    public function module_verification_is_valid_when_not_expired_and_not_used()
    {
        $user = User::factory()->create();
        $module = Module::factory()->createQuietly();

        $verification = ModuleVerification::createForModule(
            $user->id,
            $module->id,
            'activate'
        );

        $this->assertTrue($verification->isValid());
    }

    #[Test]
    public function module_verification_is_invalid_when_expired()
    {
        $user = User::factory()->create();
        $module = Module::factory()->createQuietly();

        $verification = ModuleVerification::create([
            'user_id' => $user->id,
            'module_id' => $module->id,
            'verification_code' => '123456',
            'action' => 'activate',
            'expires_at' => now()->subMinutes(1), // Expired
        ]);

        $this->assertFalse($verification->isValid());
    }

    #[Test]
    public function module_verification_is_invalid_when_used()
    {
        $user = User::factory()->create();
        $module = Module::factory()->createQuietly();

        $verification = ModuleVerification::create([
            'user_id' => $user->id,
            'module_id' => $module->id,
            'verification_code' => '123456',
            'action' => 'activate',
            'expires_at' => now()->addMinutes(10),
            'used_at' => now(), // Already used
        ]);

        $this->assertFalse($verification->isValid());
    }

    #[Test]
    public function module_verification_can_be_marked_as_used()
    {
        $user = User::factory()->create();
        $module = Module::factory()->createQuietly();

        $verification = ModuleVerification::createForModule(
            $user->id,
            $module->id,
            'activate'
        );

        $verification->markAsUsed();

        $this->assertNotNull($verification->fresh()->used_at);
        $this->assertFalse($verification->isValid());
    }

    #[Test]
    public function module_verification_can_find_by_code()
    {
        $user = User::factory()->create();
        $module = Module::factory()->createQuietly();

        $verification = ModuleVerification::createForModule(
            $user->id,
            $module->id,
            'activate'
        );

        $found = ModuleVerification::findByCode(
            $verification->verification_code,
            $user->id
        );

        $this->assertInstanceOf(ModuleVerification::class, $found);
        $this->assertEquals($verification->id, $found->id);
    }

    #[Test]
    public function module_verification_cleanup_removes_expired()
    {
        $user = User::factory()->create();
        $module = Module::factory()->createQuietly();

        // Create expired verification
        ModuleVerification::create([
            'user_id' => $user->id,
            'module_id' => $module->id,
            'verification_code' => '123456',
            'action' => 'activate',
            'expires_at' => now()->subMinutes(1),
        ]);

        // Create valid verification
        ModuleVerification::create([
            'user_id' => $user->id,
            'module_id' => $module->id,
            'verification_code' => '654321',
            'action' => 'activate',
            'expires_at' => now()->addMinutes(10),
        ]);

        $deletedCount = ModuleVerification::cleanupExpired();

        $this->assertEquals(1, $deletedCount);
        $this->assertDatabaseCount('module_verifications', 1);
    }
}
