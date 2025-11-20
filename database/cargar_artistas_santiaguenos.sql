-- ============================================================================
-- SCRIPT DE CARGA DE ARTISTAS SANTIAGUEÑOS - ID Cultural
-- Generado: 20 de noviembre de 2025
-- Descripción: Carga de 10 artistas santiagueños famosos con status "validado"
-- Credenciales: /CREDENCIALES_ARTISTAS_SANTIAGUENOS.md
-- ============================================================================

-- Contraseña: clave123
-- Hash bcrypt: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi

-- ============================================================================
-- INSERCIÓN DE 10 ARTISTAS SANTIAGUEÑOS VALIDADOS
-- ============================================================================

-- 1. Ramona Galarza
INSERT INTO artistas (
    nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status, especialidades
) VALUES (
    'Ramona', 'Galarza', '1965-05-20', 'femenino', 'Argentina', 'Santiago del Estero', 'Santiago del Estero',
    'ramona.galarza@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    'artista', 'validado', 'Música Folk, Chacarera, Composición'
) ON DUPLICATE KEY UPDATE email=email;

-- 2. Jorge Rojas
INSERT INTO artistas (
    nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status, especialidades
) VALUES (
    'Jorge', 'Rojas', '1972-08-15', 'masculino', 'Argentina', 'Santiago del Estero', 'La Banda',
    'jorge.rojas@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista', 'validado', 'Música Tradicional, Guitarra, Folclore'
) ON DUPLICATE KEY UPDATE email=email;

-- 3. Ricardo Suárez
INSERT INTO artistas (
    nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status, especialidades
) VALUES (
    'Ricardo', 'Suárez', '1968-03-22', 'masculino', 'Argentina', 'Santiago del Estero', 'Termas de Río Hondo',
    'ricardo.suarez@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista', 'validado', 'Guitarra, Música Clásica, Composición'
) ON DUPLICATE KEY UPDATE email=email;

-- 4. Peteco Carabajal
INSERT INTO artistas (
    nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status, especialidades
) VALUES (
    'Peteco', 'Carabajal', '1942-10-05', 'masculino', 'Argentina', 'Santiago del Estero', 'Santiago del Estero',
    'peteco.carabajal@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista', 'validado', 'Chacarera, Música Folclórica, Acordeón'
) ON DUPLICATE KEY UPDATE email=email;

-- 5. Jacqueline Carabajal
INSERT INTO artistas (
    nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status, especialidades
) VALUES (
    'Jacqueline', 'Carabajal', '1948-06-12', 'femenino', 'Argentina', 'Santiago del Estero', 'Santiago del Estero',
    'jacqueline.carabajal@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista', 'validado', 'Canto Folclórico, Zamba, Voz'
) ON DUPLICATE KEY UPDATE email=email;

-- 6. Raly Barrionuevo
INSERT INTO artistas (
    nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status, especialidades
) VALUES (
    'Raly', 'Barrionuevo', '1957-12-14', 'masculino', 'Argentina', 'Santiago del Estero', 'Santiago del Estero',
    'raly.barrionuevo@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista', 'validado', 'Canto Folclórico, Composición, Guitarra'
) ON DUPLICATE KEY UPDATE email=email;

-- 7. Roxana Carabajal
INSERT INTO artistas (
    nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status, especialidades
) VALUES (
    'Roxana', 'Carabajal', '1965-09-08', 'femenino', 'Argentina', 'Santiago del Estero', 'Santiago del Estero',
    'roxana.carabajal@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista', 'validado', 'Percusión, Canto, Ritmo'
) ON DUPLICATE KEY UPDATE email=email;

-- 8. Luciano Carabajal
INSERT INTO artistas (
    nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status, especialidades
) VALUES (
    'Luciano', 'Carabajal', '1970-04-18', 'masculino', 'Argentina', 'Santiago del Estero', 'Santiago del Estero',
    'luciano.carabajal@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista', 'validado', 'Guitarra, Arreglos Musicales, Composición'
) ON DUPLICATE KEY UPDATE email=email;

-- 9. Ale Brizuela
INSERT INTO artistas (
    nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status, especialidades
) VALUES (
    'Ale', 'Brizuela', '1955-07-30', 'masculino', 'Argentina', 'Santiago del Estero', 'La Banda',
    'ale.brizuela@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista', 'validado', 'Violín, Folclore, Música Tradicional'
) ON DUPLICATE KEY UPDATE email=email;

-- 10. Dúo Coplanacu
INSERT INTO artistas (
    nombre, apellido, fecha_nacimiento, genero, pais, provincia, municipio,
    email, password, role, status, especialidades
) VALUES (
    'Dúo', 'Coplanacu', '1975-01-01', 'masculino', 'Argentina', 'Santiago del Estero', 'Santiago del Estero',
    'duo.coplanacu@idcultural.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'artista', 'validado', 'Chacarera, Dúo Musical, Guitarra'
) ON DUPLICATE KEY UPDATE email=email;

-- ============================================================================
-- VERIFICACIÓN
-- ============================================================================

SELECT 
    id, 
    CONCAT(nombre, ' ', apellido) as nombre_completo,
    email,
    especialidades,
    status
FROM artistas 
WHERE email LIKE '%idcultural.com'
ORDER BY id DESC
LIMIT 10;

SELECT COUNT(*) as total_artistas FROM artistas WHERE status = 'validado';
SELECT COUNT(*) as total_artistas_all FROM artistas;

-- ============================================================================
-- NOTAS
-- ============================================================================
-- 
-- Contraseña para todos: clave123
-- Estado: Todos están "validado" para que aparezcan en la Wiki
-- 
-- Artistas cargados:
-- 1. Ramona Galarza - Música Folk (Santiago del Estero)
-- 2. Jorge Rojas - Música Tradicional (La Banda)
-- 3. Ricardo Suárez - Guitarra/Clásica (Termas de Río Hondo)
-- 4. Peteco Carabajal - Chacarera/Acordeón (Santiago del Estero)
-- 5. Jacqueline Carabajal - Canto Folclórico (Santiago del Estero)
-- 6. Raly Barrionuevo - Canto/Composición (Santiago del Estero)
-- 7. Roxana Carabajal - Percusión/Canto (Santiago del Estero)
-- 8. Luciano Carabajal - Guitarra/Arreglos (Santiago del Estero)
-- 9. Ale Brizuela - Violín/Folclore (La Banda)
-- 10. Dúo Coplanacu - Chacarera/Dúo (Santiago del Estero)
-- 
-- ============================================================================
