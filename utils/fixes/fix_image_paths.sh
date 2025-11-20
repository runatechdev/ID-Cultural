#!/bin/bash

# Script para corregir rutas de imágenes en la base de datos
# Cambiar /uploads/imagens/ a /uploads/imagenes/

echo "=== CORRECCIÓN DE RUTAS DE IMÁGENES EN BD ==="
echo ""

DB_CONTAINER="idcultural_db"
DB_USER="runatechdev"
DB_PASSWORD="1234"
DB_NAME="idcultural"

echo "🔍 Buscando rutas incorrectas (imagens)..."

# Verificar cuántos registros tienen la ruta incorrecta
COUNT_ARTISTAS=$(docker exec $DB_CONTAINER mysql -u $DB_USER -p$DB_PASSWORD $DB_NAME -sN -e \
    "SELECT COUNT(*) FROM artistas WHERE foto_perfil LIKE '%/imagens/%'")

COUNT_PUBLICACIONES=$(docker exec $DB_CONTAINER mysql -u $DB_USER -p$DB_PASSWORD $DB_NAME -sN -e \
    "SELECT COUNT(*) FROM publicaciones WHERE multimedia LIKE '%/imagens/%'")

echo "📊 Registros encontrados:"
echo "   - Artistas con foto_perfil incorrecta: $COUNT_ARTISTAS"
echo "   - Publicaciones con multimedia incorrecta: $COUNT_PUBLICACIONES"

if [ "$COUNT_ARTISTAS" -eq 0 ] && [ "$COUNT_PUBLICACIONES" -eq 0 ]; then
    echo "✅ No hay rutas incorrectas para corregir"
    exit 0
fi

echo ""
read -p "¿Deseas corregir estas rutas? (s/n): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Ss]$ ]]; then
    echo "❌ Cancelado"
    exit 1
fi

echo ""
echo "🔧 Corrigiendo rutas en tabla artistas..."
docker exec $DB_CONTAINER mysql -u $DB_USER -p$DB_PASSWORD $DB_NAME -e \
    "UPDATE artistas SET foto_perfil = REPLACE(foto_perfil, '/uploads/imagens/', '/uploads/imagenes/') WHERE foto_perfil LIKE '%/imagens/%'"

echo "🔧 Corrigiendo rutas en tabla publicaciones..."
docker exec $DB_CONTAINER mysql -u $DB_USER -p$DB_PASSWORD $DB_NAME -e \
    "UPDATE publicaciones SET multimedia = REPLACE(multimedia, '/uploads/imagens/', '/uploads/imagenes/') WHERE multimedia LIKE '%/imagens/%'"

echo ""
echo "✅ Corrección completada"
echo ""
echo "📊 Verificación final:"
docker exec $DB_CONTAINER mysql -u $DB_USER -p$DB_PASSWORD $DB_NAME -e \
    "SELECT 'Artistas con ruta incorrecta:' as tipo, COUNT(*) as cantidad FROM artistas WHERE foto_perfil LIKE '%/imagens/%'
     UNION ALL
     SELECT 'Publicaciones con ruta incorrecta:', COUNT(*) FROM publicaciones WHERE multimedia LIKE '%/imagens/%'
     UNION ALL
     SELECT 'Artistas con ruta correcta:', COUNT(*) FROM artistas WHERE foto_perfil LIKE '%/imagenes/%'
     UNION ALL
     SELECT 'Publicaciones con ruta correcta:', COUNT(*) FROM publicaciones WHERE multimedia LIKE '%/imagenes/%'"

echo ""
echo "✅ Script completado"