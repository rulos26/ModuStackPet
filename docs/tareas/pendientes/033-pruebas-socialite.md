---
agente: codex
estado: pendiente
rama:
archivos: [tests/Feature/SocialAuthTest.php]
---

# U-08: Feature tests de Socialite (login social)

Ver docs/auditorias/informe-unificado-y-plan.md (U-08, C-011). Socialite
subió de versión mayor en la Fase 3 y no tiene ninguna prueba automatizada.

## Qué hacer
1. Revisa el controlador de auth social actual (busca SocialAuthController
   o similar en app/Http/Controllers) para entender el flujo real: qué
   proveedores soporta, cómo crea/vincula usuarios.
2. Crea tests/Feature/SocialAuthTest.php con mocks de
   Socialite::shouldReceive (no llames proveedores reales):
   - Redirect a un proveedor activo funciona.
   - Callback con usuario nuevo: crea el usuario y la cuenta social.
   - Callback con usuario existente (mismo email o mismo social_id):
     vincula en vez de duplicar.
   - Proveedor inactivo o no configurado: rechaza con el mensaje/código
     adecuado, no con un error 500.
   - Error devuelto por el proveedor (excepción de Socialite): se maneja
     sin romper la app.
3. NO modifiques código de app/. Si al escribir las pruebas encuentras un
   bug real, documéntalo en el entregable sin corregirlo.

## Entregable
docs/auditorias/seg033-pruebas-socialite.md: qué cubre, bugs encontrados
si los hay, resultado de php artisan test (169 + las nuevas).

## Restricciones
- Solo tests/. No toques .env ni dependencias.
- Al terminar, vuelve con git switch --detach origin/main.
