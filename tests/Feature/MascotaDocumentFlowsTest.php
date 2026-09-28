<?php

namespace Tests\Feature;

use App\Models\DocumentRequirement;
use App\Models\MascotaDocument;
use App\Models\User;
use App\Services\DocumentValidationService;
use Database\Factories\MascotaFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MascotaDocumentFlowsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        foreach (['Superadmin', 'Admin', 'Cliente', 'Paseador'] as $role) {
            Role::create(['name' => $role]);
        }
    }

    #[Test]
    public function cliente_can_upload_a_valid_document_for_own_mascota(): void
    {
        $cliente = $this->userWithRole('Cliente');
        $mascota = MascotaFactory::new()->create(['user_id' => $cliente->id]);
        $requirement = DocumentRequirement::factory()->create([
            'codigo' => 'VACUNA',
            'formatos_permitidos' => ['pdf'],
            'tamaño_maximo_kb' => 10,
        ]);

        $response = $this->actingAs($cliente)->post(route('mascota-documents.store'), [
            'mascota_id' => $mascota->id,
            "archivo_{$requirement->id}" => $this->validPdf(),
        ]);

        $response->assertRedirect(route('mascota-documents.index'));
        $document = MascotaDocument::query()->sole();
        $this->assertSame($cliente->id, $document->usuario_subio_id);
        $this->assertSame('pendiente', $document->estado);
        $this->assertTrue($document->validacion_automatica);
        $this->assertNull($document->usuario_aprobo_id);
        $this->assertNull($document->fecha_aprobacion);
        Storage::disk('public')->assertExists($document->ruta_archivo);
    }

    #[Test]
    public function administrators_can_approve_and_reject_documents(): void
    {
        $admin = $this->userWithRole('Admin');
        $superadmin = $this->userWithRole('Superadmin');
        $documentToApprove = MascotaDocument::factory()->create();
        $documentToReject = MascotaDocument::factory()->create();
        $documentForSuperadmin = MascotaDocument::factory()->create();

        $this->actingAs($admin)
            ->from(route('mascota-documents.show', $documentToApprove))
            ->post(route('mascota-documents.aprobar', $documentToApprove), ['notas' => 'Revisado'])
            ->assertRedirect(route('mascota-documents.show', $documentToApprove));

        $this->assertDatabaseHas('mascota_documents', [
            'id' => $documentToApprove->id,
            'estado' => 'aprobado',
            'usuario_aprobo_id' => $admin->id,
            'notas' => 'Revisado',
        ]);

        $this->actingAs($admin)
            ->from(route('mascota-documents.show', $documentToReject))
            ->post(route('mascota-documents.rechazar', $documentToReject), [
                'motivo_rechazo' => 'Documento ilegible',
            ])
            ->assertRedirect(route('mascota-documents.show', $documentToReject));

        $this->assertDatabaseHas('mascota_documents', [
            'id' => $documentToReject->id,
            'estado' => 'rechazado',
            'motivo_rechazo' => 'Documento ilegible',
            'usuario_aprobo_id' => null,
        ]);

        $this->actingAs($superadmin)
            ->post(route('mascota-documents.aprobar', $documentForSuperadmin))
            ->assertRedirect();

        $this->assertDatabaseHas('mascota_documents', [
            'id' => $documentForSuperadmin->id,
            'estado' => 'aprobado',
            'usuario_aprobo_id' => $superadmin->id,
        ]);
    }

    #[Test]
    public function cliente_cannot_view_modify_download_approve_or_reject_another_clientes_document(): void
    {
        $cliente = $this->userWithRole('Cliente');
        $otroCliente = $this->userWithRole('Cliente');
        $mascotaAjena = MascotaFactory::new()->create(['user_id' => $otroCliente->id]);
        $document = MascotaDocument::factory()->create([
            'mascota_id' => $mascotaAjena->id,
            'notas' => 'Original',
        ]);
        Storage::disk('public')->put($document->ruta_archivo, '%PDF-1.4 contenido');

        $countBefore = MascotaDocument::count();
        $this->actingAs($cliente)
            ->from(route('mascota-documents.index'))
            ->post(route('mascota-documents.store'), [
                'mascota_id' => $mascotaAjena->id,
                "archivo_{$document->document_requirement_id}" => $this->validPdf(),
            ])
            ->assertRedirect(route('mascota-documents.index'));
        $this->assertSame($countBefore, MascotaDocument::count());

        $this->actingAs($cliente)
            ->get(route('mascota-documents.show', $document))
            ->assertForbidden();
        $this->actingAs($cliente)
            ->get(route('mascota-documents.descargar', $document))
            ->assertForbidden();
        $this->actingAs($cliente)
            ->post(route('mascota-documents.aprobar', $document))
            ->assertForbidden();
        $this->actingAs($cliente)
            ->post(route('mascota-documents.rechazar', $document), ['motivo_rechazo' => 'No autorizado'])
            ->assertForbidden();

        $this->assertDatabaseHas('mascota_documents', [
            'id' => $document->id,
            'notas' => 'Original',
            'estado' => 'pendiente',
        ]);
    }

    #[Test]
    public function owner_can_download_an_existing_document(): void
    {
        $cliente = $this->userWithRole('Cliente');
        $mascota = MascotaFactory::new()->create(['user_id' => $cliente->id]);
        $document = MascotaDocument::factory()->create([
            'mascota_id' => $mascota->id,
            'nombre_archivo' => 'vacuna.pdf',
        ]);
        Storage::disk('public')->put($document->ruta_archivo, '%PDF-1.4 contenido');

        $this->actingAs($cliente)
            ->get(route('mascota-documents.descargar', $document))
            ->assertOk()
            ->assertDownload('vacuna.pdf');
    }

    #[Test]
    public function upload_rejects_disallowed_file_type(): void
    {
        [$cliente, $mascota, $requirement] = $this->uploadContext();

        $response = $this->actingAs($cliente)
            ->from(route('mascota-documents.create', ['mascota_id' => $mascota->id]))
            ->post(route('mascota-documents.store'), [
                'mascota_id' => $mascota->id,
                "archivo_{$requirement->id}" => UploadedFile::fake()->create('malware.exe', 1),
            ]);

        $response->assertRedirect(route('mascota-documents.create', ['mascota_id' => $mascota->id]));
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('mascota_documents', 0);
        Storage::disk('public')->assertDirectoryEmpty('documentos_mascotas');
    }

    #[Test]
    public function upload_rejects_file_larger_than_requirement_limit(): void
    {
        [$cliente, $mascota, $requirement] = $this->uploadContext();

        $response = $this->actingAs($cliente)
            ->from(route('mascota-documents.create', ['mascota_id' => $mascota->id]))
            ->post(route('mascota-documents.store'), [
                'mascota_id' => $mascota->id,
                "archivo_{$requirement->id}" => UploadedFile::fake()->createWithContent(
                    'grande.pdf',
                    '%PDF-1.4 '.str_repeat('A', 2048),
                ),
            ]);

        $response->assertRedirect(route('mascota-documents.create', ['mascota_id' => $mascota->id]));
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('mascota_documents', 0);
        Storage::disk('public')->assertDirectoryEmpty('documentos_mascotas');
    }

    #[Test]
    public function internal_exception_details_are_not_exposed_or_logged(): void
    {
        $secret = 'password-super-secreto';
        $cliente = $this->userWithRole('Cliente');
        $mascota = MascotaFactory::new()->create(['user_id' => $cliente->id]);
        $requirement = DocumentRequirement::factory()->create();
        $service = Mockery::mock(DocumentValidationService::class);
        $service->shouldReceive('validarDocumento')
            ->once()
            ->andThrow(new \RuntimeException("Fallo interno: {$secret}"));
        $this->app->instance(DocumentValidationService::class, $service);
        Log::spy();

        $response = $this->actingAs($cliente)
            ->from(route('mascota-documents.create', ['mascota_id' => $mascota->id]))
            ->post(route('mascota-documents.store'), [
                'mascota_id' => $mascota->id,
                "archivo_{$requirement->id}" => $this->validPdf(),
            ]);

        $response->assertRedirect(route('mascota-documents.create', ['mascota_id' => $mascota->id]));
        $response->assertSessionHas('error', fn (string $message): bool => ! str_contains($message, $secret));
        Log::shouldHaveReceived('error')
            ->once()
            ->with(
                'Error al subir documentos.',
                Mockery::on(fn (array $context): bool => ! str_contains(json_encode($context), $secret)),
            );
    }

    #[Test]
    public function denied_foreign_document_update_does_not_leave_a_transaction_open(): void
    {
        $cliente = $this->userWithRole('Cliente');
        $otroCliente = $this->userWithRole('Cliente');
        $mascotaAjena = MascotaFactory::new()->create(['user_id' => $otroCliente->id]);
        $document = MascotaDocument::factory()->create([
            'mascota_id' => $mascotaAjena->id,
            'notas' => 'Original',
        ]);
        $transactionLevelBefore = DB::transactionLevel();

        $response = $this->actingAs($cliente)
            ->from(route('mascota-documents.index'))
            ->put(route('mascota-documents.update', $document), ['notas' => 'Alterada']);

        $response->assertRedirect(route('mascota-documents.index'));
        $this->assertSame($transactionLevelBefore, DB::transactionLevel());
        $this->assertDatabaseHas('mascota_documents', [
            'id' => $document->id,
            'notas' => 'Original',
        ]);
    }

    private function uploadContext(): array
    {
        $cliente = $this->userWithRole('Cliente');
        $mascota = MascotaFactory::new()->create(['user_id' => $cliente->id]);
        $requirement = DocumentRequirement::factory()->create([
            'codigo' => 'VACUNA',
            'formatos_permitidos' => ['pdf'],
            'tamaño_maximo_kb' => 1,
        ]);

        return [$cliente, $mascota, $requirement];
    }

    private function validPdf(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent('vacuna.pdf', '%PDF-1.4 documento válido');
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }
}
