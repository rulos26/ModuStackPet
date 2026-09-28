<?php

namespace Tests\Unit;

use App\Models\Paseador;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PaseadorTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function paseador_uses_the_existing_paseadores_table(): void
    {
        $user = User::factory()->create();

        $paseador = Paseador::create([
            'user_id' => $user->id,
            'cedula' => '123456789',
            'disponibilidad' => true,
        ]);

        $this->assertSame('paseadores', $paseador->getTable());
        $this->assertDatabaseHas('paseadores', [
            'id' => $paseador->id,
            'user_id' => $user->id,
            'cedula' => '123456789',
        ]);
    }
}
