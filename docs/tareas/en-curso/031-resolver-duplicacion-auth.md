---
agente: claude
estado: en-curso
rama: ia/claude/fortify-auth
archivos: [bootstrap/providers.php, routes/web.php, app/Http/Controllers/, config/fortify.php]
---

# U-06 + apagar 2FA: Fortify como stack oficial de auth

Decisión humana (docs/auditorias/informe-unificado-y-plan.md, §7, tarea 030
resuelta): Fortify es el stack oficial. Los controladores propios de
auth quedan retirados. 2FA se apaga por ahora (decisión humana), no se
implementa en esta tarea.

Precisión ya verificada en el informe (§3.3): la variante que HOY recibe
el tráfico real es la de los controladores propios (raíz o Auth\\, según
cada caso — revisa la tabla exacta en el informe antes de tocar nada), no
Fortify. Vas a invertir cuál gana.

## Qué hacer
1. Pruebas primero (commit que falle): escribe pruebas que confirmen el
   comportamiento correcto DESPUÉS del cambio (registro, login y reset de
   contraseña funcionando vía Fortify). Es normal que estas pruebas fallen
   antes de aplicar el cambio si Fortify aún no está activo.
2. Registra FortifyServiceProvider en bootstrap/providers.php.
3. En config/fortify.php, quita 'two-factor-authentication' (o el nombre
   exacto de la feature) del arreglo 'features' — 2FA queda apagado.
4. Elimina de routes/web.php la ruta duplicada 'register' (y cualquier
   otra colisión de nombre que documentó el informe), dejando que la de
   Fortify sea la que se registre.
5. Mueve a _borrar/ los controladores propios que quedan muertos según la
   tabla del informe §3.3 (no los borres del todo, solo muévelos).
6. Revisa las vistas de login/registro/reset: Fortify espera vistas con
   nombres y campos específicos (revisa la documentación oficial de
   Fortify si no la tienes en contexto). Si las vistas actuales no
   coinciden con lo que Fortify espera, adáptalas o créalas — sin cambiar
   el diseño visual del proyecto, solo la integración.
7. Ejecuta la suite completa: las 146 + las nuevas deben pasar.
8. Prueba manualmente en tu entorno local (no solo con la suite): login,
   registro y reset de contraseña deben funcionar de principio a fin.
   Documenta el resultado de esa prueba manual en el entregable.

## Entregable
docs/auditorias/seg031-fortify-auth.md: qué se movió a _borrar/, qué
vistas se ajustaron, resultado de pruebas automatizadas y manuales.

## Restricciones
- No implementes 2FA en esta tarea (queda apagado, es tarea futura aparte).
- No toques .env ni dependencias (Fortify ya está instalado).
- Si algo de esto requiere una decisión de producto que no está cubierta
  aquí, detente y pregunta en el Handoff en vez de decidir tú.
- Al terminar, vuelve con git switch --detach origin/main.
