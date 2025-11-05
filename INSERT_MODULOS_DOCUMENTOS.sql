-- ============================================
-- INSERTS MANUALES PARA MÓDULOS DE DOCUMENTOS
-- ============================================
-- Fecha: 2025-01-31
-- Descripción: Sentencias SQL para insertar los módulos de 
--              "Requisitos Documentales" y "Documentos de Mascotas"
-- ============================================

-- Módulo 1: Requisitos Documentales
INSERT INTO `modules` (
    `name`, 
    `slug`, 
    `description`, 
    `status`, 
    `created_at`, 
    `updated_at`
) VALUES (
    'Requisitos Documentales',
    'requisitos-documentales',
    'Gestión de requisitos documentales para ingreso de mascotas (activar/desactivar requisitos)',
    1,
    NOW(),
    NOW()
);

-- Módulo 2: Documentos de Mascotas
INSERT INTO `modules` (
    `name`, 
    `slug`, 
    `description`, 
    `status`, 
    `created_at`, 
    `updated_at`
) VALUES (
    'Documentos de Mascotas',
    'documentos-mascotas',
    'Carga y validación de documentos requeridos para el ingreso de mascotas (vacunas, certificados, etc.)',
    1,
    NOW(),
    NOW()
);

-- ============================================
-- VERIFICACIÓN
-- ============================================
-- Para verificar que se insertaron correctamente:
-- SELECT * FROM `modules` WHERE `slug` IN ('requisitos-documentales', 'documentos-mascotas');

-- ============================================
-- NOTAS
-- ============================================
-- 1. El campo `status` = 1 significa que el módulo está ACTIVO
-- 2. El campo `status` = 0 significa que el módulo está INACTIVO
-- 3. El campo `slug` debe ser único, si ya existe, usar UPDATE en lugar de INSERT
-- 4. Si necesitas actualizar en lugar de insertar, usar:
--    UPDATE `modules` SET `status` = 1 WHERE `slug` = 'requisitos-documentales';
--    UPDATE `modules` SET `status` = 1 WHERE `slug` = 'documentos-mascotas';

-- ============================================
-- VERSIÓN CON IGNORE (Para evitar errores si ya existen)
-- ============================================
-- INSERT IGNORE INTO `modules` (`name`, `slug`, `description`, `status`, `created_at`, `updated_at`)
-- VALUES 
--     ('Requisitos Documentales', 'requisitos-documentales', 'Gestión de requisitos documentales para ingreso de mascotas (activar/desactivar requisitos)', 1, NOW(), NOW()),
--     ('Documentos de Mascotas', 'documentos-mascotas', 'Carga y validación de documentos requeridos para el ingreso de mascotas (vacunas, certificados, etc.)', 1, NOW(), NOW());

-- ============================================
-- VERSIÓN CON UPDATE OR INSERT (MySQL 8.0+)
-- ============================================
-- INSERT INTO `modules` (`name`, `slug`, `description`, `status`, `created_at`, `updated_at`)
-- VALUES 
--     ('Requisitos Documentales', 'requisitos-documentales', 'Gestión de requisitos documentales para ingreso de mascotas (activar/desactivar requisitos)', 1, NOW(), NOW()),
--     ('Documentos de Mascotas', 'documentos-mascotas', 'Carga y validación de documentos requeridos para el ingreso de mascotas (vacunas, certificados, etc.)', 1, NOW(), NOW())
-- ON DUPLICATE KEY UPDATE
--     `name` = VALUES(`name`),
--     `description` = VALUES(`description`),
--     `status` = VALUES(`status`),
--     `updated_at` = NOW();

