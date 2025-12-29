#!/bin/bash

# ID Cultural - Quick Setup
# Configura el proyecto después de clonar

echo "🚀 ID CULTURAL - CONFIGURACIÓN INICIAL"
echo "======================================"
echo ""

# 1. Verificar Docker
if ! command -v docker &> /dev/null; then
    echo "❌ Docker no está instalado"
    echo "   Instálalo desde: https://docs.docker.com/get-docker/"
    exit 1
fi
echo "✅ Docker instalado"

# 2. Verificar docker-compose
if ! command -v docker compose &> /dev/null; then
    echo "❌ Docker Compose no está instalado"
    exit 1
fi
echo "✅ Docker Compose instalado"

# 3. Crear .env desde template
if [ ! -f .env ]; then
    echo "📝 Creando archivo .env..."
    cp .env.example .env
    
    # Generar keys
    echo "🔑 Generando keys de seguridad..."
    APP_KEY=$(openssl rand -base64 32 | tr -d '\n')
    JWT_SECRET=$(openssl rand -base64 64 | tr -d '\n')
    
    # Reemplazar en .env (macOS compatible)
    if [[ "$OSTYPE" == "darwin"* ]]; then
        sed -i '' "s|APP_KEY=.*|APP_KEY=$APP_KEY|" .env
        sed -i '' "s|JWT_SECRET=.*|JWT_SECRET=$JWT_SECRET|" .env
    else
        sed -i "s|APP_KEY=.*|APP_KEY=$APP_KEY|" .env
        sed -i "s|JWT_SECRET=.*|JWT_SECRET=$JWT_SECRET|" .env
    fi
    
    echo "✅ .env creado con keys únicas"
else
    echo "⚠️  .env ya existe, saltando..."
fi

# 4. Crear directorios necesarios
echo "📁 Creando estructura de directorios..."
mkdir -p storage/logs storage/uploads storage/cache
mkdir -p public/uploads tmp sessions
chmod -R 755 storage/
echo "✅ Directorios creados"

# 5. Levantar Docker
echo "🐳 Levantando contenedores Docker..."
docker compose down
docker compose up -d

# Esperar a que la BD esté lista
echo "⏳ Esperando a que la base de datos esté lista..."
sleep 10

# 6. Verificar contenedores
if docker ps | grep -q idcultural_web; then
    echo "✅ Contenedor web corriendo"
else
    echo "❌ Contenedor web no está corriendo"
    docker logs idcultural_web
    exit 1
fi

if docker ps | grep -q idcultural_db; then
    echo "✅ Contenedor db corriendo"
else
    echo "❌ Contenedor db no está corriendo"
    exit 1
fi

# 7. Test de conectividad
echo ""
echo "🧪 Verificando conectividad..."
if curl -s -f http://localhost:8080/ > /dev/null; then
    echo "✅ Sitio web respondiendo en http://localhost:8080"
else
    echo "❌ Sitio web no responde"
    exit 1
fi

if curl -s -f http://localhost:8080/api/stats.php?action=public > /dev/null; then
    echo "✅ API respondiendo correctamente"
else
    echo "❌ API no responde"
    exit 1
fi

# 8. Resumen
echo ""
echo "======================================"
echo "✅ CONFIGURACIÓN COMPLETADA"
echo "======================================"
echo ""
echo "🌐 URLs disponibles:"
echo "   - Sitio web:    http://localhost:8080"
echo "   - phpMyAdmin:   http://localhost:8081"
echo ""
echo "🔑 Credenciales por defecto:"
echo "   Usuario: admin@idcultural.gob.ar"
echo "   Contraseña: admin123"
echo ""
echo "📝 Próximos pasos:"
echo "   1. Revisa las credenciales en .env"
echo "   2. Cambia las contraseñas por defecto"
echo "   3. Lee la documentación en docs/"
echo ""
echo "🚀 Para detener: docker compose down"
echo "🔄 Para reiniciar: docker compose restart"
echo ""
