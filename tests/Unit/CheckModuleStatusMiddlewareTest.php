<?php

namespace Tests\Unit;

use App\Http\Middleware\CheckModuleStatus;
use App\Models\Module;
use App\Models\ModuleLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

class CheckModuleStatusMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected $middleware;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->middleware = new CheckModuleStatus();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function middleware_allows_access_to_active_module()
    {
        $module = Module::factory()->create(['status' => true]);

        $request = Request::create('/test', 'GET');
        $request->setUserResolver(function () {
            return $this->user;
        });

        $response = $this->middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        }, $module->slug);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getContent());
    }

    /** @test */
    public function middleware_blocks_access_to_inactive_module()
    {
        $module = Module::factory()->create(['status' => false]);

        $request = Request::create('/test', 'GET');
        $request->setUserResolver(function () {
            return $this->user;
        });

        $response = $this->middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        }, $module->slug);

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertStringContainsString('access-denied', $response->getContent());
    }

    /** @test */
    public function middleware_blocks_access_to_nonexistent_module()
    {
        $request = Request::create('/test', 'GET');
        $request->setUserResolver(function () {
            return $this->user;
        });

        $response = $this->middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        }, 'nonexistent-module');

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertStringContainsString('access-denied', $response->getContent());
    }

    /** @test */
    public function middleware_logs_access_denied_attempts()
    {
        $module = Module::factory()->create(['status' => false]);

        $request = Request::create('/test', 'GET');
        $request->setUserResolver(function () {
            return $this->user;
        });

        $this->middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        }, $module->slug);

        $this->assertDatabaseHas('module_logs', [
            'user_id' => $this->user->id,
            'module_id' => $module->id,
            'action' => 'access_denied',
        ]);
    }

    /** @test */
    public function middleware_handles_unauthenticated_user()
    {
        $module = Module::factory()->create(['status' => false]);

        $request = Request::create('/test', 'GET');
        // No user set

        $response = $this->middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        }, $module->slug);

        $this->assertEquals(403, $response->getStatusCode());

        // Should still log the attempt
        $this->assertDatabaseHas('module_logs', [
            'user_id' => null,
            'module_id' => $module->id,
            'action' => 'access_denied',
        ]);
    }
}
