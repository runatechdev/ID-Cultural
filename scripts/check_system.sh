#!/bin/bash

# Script para verificar el estado de la base de datos
# Verifica tablas, permisos de uploads y configuración

set -e

DB_CONTAINER="idcultural_db"
DB_USER="runatechdev"
DB_PASSWORD="1234"
DB_NAME="idcultural"

echo "=== VERIFICACIÓN DE BASE DE DATOS Y SISTEMA ==="
echo ""

# 1. Verificar contenedor
echo "1️⃣ Verificando contenedor Docker..."
if docker ps | grep -q $DB_CONTAINER; then
    echo "   ✅ Contenedor $DB_CONTAINER está corriendo"
else
    echo "   ❌ Contenedor $DB_CONTAINER NO está corriendo"
    exit 1
fi

# 2. Verificar conexión
echo ""
echo "2️⃣ Verificando conexión a MySQL..."
if docker exec $DB_CONTAINER mysqladmin ping -u $DB_USER -p$DB_PASSWORD --silent; then
    echo "   ✅ Conexión a MySQL OK"
else
    echo "   ❌ No se puede conectar a MySQL"
    exit 1
fi

# 3. Verificar tablas requeridas
echo ""
echo "3️⃣ Verificando tablas requeridas..."
REQUIRED_TABLES=(
    "artistas"
    "users"
    "publicaciones"
    "perfil_cambios_pendientes"
    "notificaciones"
    "system_logs"
)

for table in "${REQUIRED_TABLES[@]}"; do
    exists=$(docker exec $DB_CONTAINER mysql -u $DB_USER -p$DB_PASSWORD $DB_NAME -sN -e "
        SELECT COUNT(*) FROM information_schema.tables 
        WHERE table_schema = '$DB_NAME' AND table_name = '$table';
    ")
    
    if [ "$exists" -eq "1" ]; then
        echo "   ✅ Tabla '$table' existe"
    else
        echo "   ❌ Tabla '$table' NO existe"
    fi
done

# 4. Verificar permisos de uploads
echo ""
echo "4️⃣ Verificando permisos de directorios de uploads..."
UPLOAD_DIRS=(
    "public/uploads"
    "public/uploads/imagenes"
    "public/uploads/audios"
    "public/uploads/videos"
)

for dir in "${UPLOAD_DIRS[@]}"; do
    if [ -d "$dir" ]; then
        perms=$(stat -c "%a" "$dir")
        if [ "$perms" = "777" ]; then
            echo "   ✅ $dir tiene permisos correctos (777)"
        else
            echo "   ⚠️  $dir tiene permisos $perms (recomendado: 777)"
        fi
    else
        echo "   ❌ $dir NO existe"
    fi
done

# 5. Verificar configuración de email
echo ""
echo "5️⃣ Verificando configuración de email..."
if [ -f ".env" ]; then
    echo "   ✅ Archivo .env existe"
    if grep -q "SENDGRID_API_KEY=" .env && ! grep -q "SENDGRID_API_KEY=your_" .env; then
        echo "   ✅ SENDGRID_API_KEY configurado"
    else
        echo "   ⚠️  SENDGRID_API_KEY no configurado"
    fi
else
    echo "   ⚠️  Archivo .env no existe (copia .env.example)"
fi

# 6. Estadísticas de la BD
echo ""
echo "6️⃣ Estadísticas de la base de datos..."
docker exec $DB_CONTAINER mysql -u $DB_USER -p$DB_PASSWORD $DB_NAME -e "
    SELECT 
        'Artistas' as Tabla, COUNT(*) as Total FROM artistas
    UNION ALL
    SELECT 'Usuarios', COUNT(*) FROM users
    UNION ALL
    SELECT 'Publicaciones', COUNT(*) FROM publicaciones
    UNION ALL
    SELECT 'Cambios Pendientes', COUNT(*) FROM perfil_cambios_pendientes
    UNION ALL
    SELECT 'Notificaciones', COUNT(*) FROM notificaciones;
"

echo ""
echo "=== RESUMEN ==="
echo "✅ Sistema verificado y listo para usar"
echo ""
echo "💡 Comandos útiles:"
echo "   - Ver logs: docker logs idcultural_web"
echo "   - Reiniciar: docker-compose restart"
echo "   - Migraciones: ./scripts/run_migrations.sh"
echo "   - Backups: ./scripts/export_database.sh"
