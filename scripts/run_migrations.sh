#!/bin/bash

# Script para ejecutar todas las migraciones pendientes
# Uso: ./scripts/run_migrations.sh

set -e

DB_CONTAINER="idcultural_db"
DB_USER="runatechdev"
DB_PASSWORD="1234"
DB_NAME="idcultural"
MIGRATIONS_DIR="database/migrations"

echo "🔄 Ejecutando migraciones de base de datos..."

# Verificar que el contenedor está corriendo
if ! docker ps | grep -q $DB_CONTAINER; then
    echo "❌ Error: El contenedor $DB_CONTAINER no está corriendo"
    exit 1
fi

# Crear tabla de migraciones si no existe
echo "📋 Verificando tabla de migraciones..."
docker exec $DB_CONTAINER mysql -u $DB_USER -p$DB_PASSWORD $DB_NAME -e "
CREATE TABLE IF NOT EXISTS migrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    migration VARCHAR(255) NOT NULL UNIQUE,
    executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
"

# Ejecutar migraciones
if [ -d "$MIGRATIONS_DIR" ]; then
    for migration_file in "$MIGRATIONS_DIR"/*.sql; do
        if [ -f "$migration_file" ]; then
            migration_name=$(basename "$migration_file")
            
            # Verificar si ya fue ejecutada
            already_run=$(docker exec $DB_CONTAINER mysql -u $DB_USER -p$DB_PASSWORD $DB_NAME -sN -e "
                SELECT COUNT(*) FROM migrations WHERE migration = '$migration_name';
            ")
            
            if [ "$already_run" -eq "0" ]; then
                echo "⚡ Ejecutando: $migration_name"
                docker exec -i $DB_CONTAINER mysql -u $DB_USER -p$DB_PASSWORD $DB_NAME < "$migration_file"
                
                # Registrar migración
                docker exec $DB_CONTAINER mysql -u $DB_USER -p$DB_PASSWORD $DB_NAME -e "
                    INSERT INTO migrations (migration) VALUES ('$migration_name');
                "
                echo "  ✅ Completada"
            else
                echo "  ⏭️  Saltando: $migration_name (ya ejecutada)"
            fi
        fi
    done
else
    echo "❌ Error: Directorio $MIGRATIONS_DIR no encontrado"
    exit 1
fi

echo ""
echo "✅ Todas las migraciones completadas"
echo "📊 Migraciones ejecutadas:"
docker exec $DB_CONTAINER mysql -u $DB_USER -p$DB_PASSWORD $DB_NAME -e "SELECT * FROM migrations ORDER BY executed_at DESC;"
