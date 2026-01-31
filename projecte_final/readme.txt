Nombre del proyecto:
Overwatch Hero Manager

Descripción:
Aplicación web para gestionar héroes, habilidades, mapas y valoraciones del videojuego Overwatch. Incluye sistema de autenticación, roles (admin/jugador), CRUD de héroes y habilidades, búsqueda, filtrado, ordenación y estadísticas básicas.

Usuarios de prueba:
admin@overwatch.com / (contraseña que tú pongas y luego hashes en la BD)
user1@overwatch.com / (igual)

Funcionalidades principales:
- Registro y login con contraseñas encriptadas
- Roles: admin y user
- CRUD completo de héroes (con imágenes)
- CRUD completo de habilidades
- Búsqueda y filtrado de héroes por nombre y rol
- Ordenación por rol, nombre, fecha y valoración
- Sistema de valoraciones (1-5) para héroes
- Estadísticas básicas (top héroes, totales)

Instrucciones:
1. Importar database.sql en phpMyAdmin.
2. Ajustar credenciales en config.php.
3. Crear carpeta `uploads/` con permisos de escritura.
4. Abrir `index.html` desde XAMPP (http://localhost/overwatch_manager/).
