#!/bin/bash
###############################################################################
# Script para importar la tabla de artistas famosos a la BD
# Uso: bash scripts/importar_artistas_famosos.sh
###############################################################################

echo "🔄 Importando tabla de artistas famosos..."

# Importar el archivo SQL a la BD del servidor
docker exec -i idcultural_db mysql -u runatechdev -p1234 idcultural < database/artistas_famosos.sql

if [ $? -eq 0 ]; then
    echo "✅ Tabla de artistas famosos importada exitosamente"
    
    # Verificar que se importaron los datos
    TOTAL=$(docker exec idcultural_db mysql -u runatechdev -p1234 idcultural -e "SELECT COUNT(*) FROM artistas_famosos;" -N)
    echo "📊 Total de artistas famosos cargados: $TOTAL"
else
    echo "❌ Error al importar la tabla"
    exit 1
fi
