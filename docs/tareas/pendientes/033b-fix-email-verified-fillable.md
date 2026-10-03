---
agente: codex
estado: pendiente
rama: ia/codex/pruebas-socialite
archivos: [app/Models/User.php]
---

# Ajuste a la 033: email_verified_at no está en $fillable

Hallazgo de la propia tarea 033: tras login social, email_verified_at
queda null porque no está en $fillable de User.php, aunque el proveedor
social ya verificó el correo. La prueba que lo demuestra ya existe en
tests/Feature/SocialAuthTest.php y hoy falla.

## Qué hacer
1. Agrega email_verified_at a $fillable en app/Models/User.php (o usa el
   método correcto de asignación si $fillable no es el mecanismo correcto
   aquí, revisa cómo se crea el usuario en el flujo social).
2. La prueba que hoy falla debe pasar sin modificarla (si necesitas
   ajustarla, documenta por qué en el entregable).
3. Ejecuta la suite completa: debe quedar 100% en verde.

## Entregable
Actualiza docs/auditorias/seg033-pruebas-socialite.md con la corrección.

## Restricciones
- Solo app/Models/User.php. No toques .env ni dependencias.
- Al terminar, vuelve con git switch --detach origin/main.
