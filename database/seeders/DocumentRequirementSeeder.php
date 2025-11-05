<?php

namespace Database\Seeders;

use App\Models\DocumentRequirement;
use Illuminate\Database\Seeder;

class DocumentRequirementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requirements = [
            [
                'codigo' => 'VAC',
                'nombre' => 'Carné de Vacunación',
                'descripcion' => 'Certificado de vacunación actualizado de la mascota',
                'obligatorio' => true,
                'activo' => true,
                'orden' => 1,
                'tipo_validacion' => 'fecha_vencimiento',
                'dias_validez' => 365, // 1 año
                'formatos_permitidos' => ['pdf', 'jpg', 'jpeg', 'png'],
                'tamaño_maximo_kb' => 2048,
                'aplica_razas_peligrosas' => false,
            ],
            [
                'codigo' => 'DESP',
                'nombre' => 'Certificado de Desparasitación',
                'descripcion' => 'Certificado de desparasitación reciente (máximo 3 meses)',
                'obligatorio' => true,
                'activo' => true,
                'orden' => 2,
                'tipo_validacion' => 'fecha_vencimiento',
                'dias_validez' => 90, // 3 meses
                'formatos_permitidos' => ['pdf', 'jpg', 'jpeg', 'png'],
                'tamaño_maximo_kb' => 2048,
                'aplica_razas_peligrosas' => false,
            ],
            [
                'codigo' => 'SALUD',
                'nombre' => 'Certificado de Salud Veterinario',
                'descripcion' => 'Certificado médico veterinario que acredita el buen estado de salud de la mascota',
                'obligatorio' => true,
                'activo' => true,
                'orden' => 3,
                'tipo_validacion' => 'sello_veterinario',
                'dias_validez' => 30, // 1 mes
                'formatos_permitidos' => ['pdf', 'jpg', 'jpeg', 'png'],
                'tamaño_maximo_kb' => 2048,
                'aplica_razas_peligrosas' => false,
            ],
            [
                'codigo' => 'DOC_DUENO',
                'nombre' => 'Documento del Dueño',
                'descripcion' => 'Copia de documento de identidad del propietario de la mascota',
                'obligatorio' => true,
                'activo' => true,
                'orden' => 4,
                'tipo_validacion' => null,
                'dias_validez' => null,
                'formatos_permitidos' => ['pdf', 'jpg', 'jpeg', 'png'],
                'tamaño_maximo_kb' => 2048,
                'aplica_razas_peligrosas' => false,
            ],
            [
                'codigo' => 'CONTRATO',
                'nombre' => 'Contrato Firmado',
                'descripcion' => 'Contrato de servicios firmado por el dueño de la mascota',
                'obligatorio' => true,
                'activo' => true,
                'orden' => 5,
                'tipo_validacion' => 'firma_digital',
                'dias_validez' => null,
                'formatos_permitidos' => ['pdf'],
                'tamaño_maximo_kb' => 5120,
                'aplica_razas_peligrosas' => false,
            ],
            [
                'codigo' => 'COMPORT',
                'nombre' => 'Certificación de Comportamiento',
                'descripcion' => 'Evaluación de comportamiento de la mascota (opcional, recomendado para razas peligrosas)',
                'obligatorio' => false,
                'activo' => true,
                'orden' => 6,
                'tipo_validacion' => null,
                'dias_validez' => null,
                'formatos_permitidos' => ['pdf', 'jpg', 'jpeg', 'png'],
                'tamaño_maximo_kb' => 2048,
                'aplica_razas_peligrosas' => true, // Aplica principalmente para razas peligrosas
            ],
        ];

        foreach ($requirements as $requirement) {
            DocumentRequirement::updateOrCreate(
                ['codigo' => $requirement['codigo']],
                $requirement
            );
        }

        $this->command->info('Requisitos documentales iniciales creados exitosamente.');
    }
}
