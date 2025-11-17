#!/bin/bash

# 🎯 CHECKLIST RÁPIDO PARA DEMO CON NGROK
# Ejecutar antes de la demo: bash pre-demo-check.sh

echo "╔════════════════════════════════════════════════════════════╗"
echo "║     🎯 CHECKLIST PRE-DEMO - ID CULTURAL                   ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""

# 1. Verificar Docker
echo "1️⃣  Verificando Docker..."
if docker --version > /dev/null 2>&1; then
    echo "   ✅ Docker instalado"
else
    echo "   ❌ Docker NO está instalado"
    exit 1
fi

# 2. Verificar que los contenedores están corriendo
echo ""
echo "2️⃣  Verificando contenedores..."
if docker ps | grep -q "idcultural"; then
    echo "   ✅ Contenedores están corriendo"
else
    echo "   ⚠️  Los contenedores NO están corriendo"
    echo "   Inicia con: docker-compose up -d"
fi

# 3. Verificar localhost:8080
echo ""
echo "3️⃣  Verificando localhost:8080..."
if curl -s http://localhost:8080 > /dev/null; then
    echo "   ✅ Aplicación responde en localhost:8080"
else
    echo "   ❌ Aplicación NO responde en localhost:8080"
    exit 1
fi

# 4. Verificar ngrok
echo ""
echo "4️⃣  Verificando ngrok..."
if which ngrok > /dev/null; then
    NGROK_VERSION=$(ngrok version 2>/dev/null || echo "desconocida")
    echo "   ✅ ngrok instalado (v$NGROK_VERSION)"
else
    echo "   ❌ ngrok NO está instalado"
    exit 1
fi

# 5. Verificar base de datos
echo ""
echo "5️⃣  Verificando base de datos..."
if docker exec idcultural-db mysql -h localhost -u root -proot123 idcultural -e "SELECT 1" > /dev/null 2>&1; then
    echo "   ✅ Base de datos conectada"
    ARTISTAS=$(docker exec idcultural-db mysql -h localhost -u root -proot123 idcultural -sN -e "SELECT COUNT(*) FROM artistas_famosos WHERE activo=1;")
    echo "   📊 Artistas en BD: $ARTISTAS"
else
    echo "   ⚠️  Base de datos NO responde"
fi

# 6. Verificar archivo de configuración
echo ""
echo "6️⃣  Verificando configuración..."
if [ -f "config.php" ]; then
    echo "   ✅ config.php existe"
else
    echo "   ❌ config.php NO existe"
    exit 1
fi

# 7. Verificar API de artistas
echo ""
echo "7️⃣  Verificando API..."
API_RESPONSE=$(curl -s http://localhost:8080/api/artistas_famosos.php)
if echo "$API_RESPONSE" | grep -q "data"; then
    echo "   ✅ API de artistas responde"
else
    echo "   ⚠️  API de artistas podría tener problemas"
fi

# Resumen final
echo ""
echo "╔════════════════════════════════════════════════════════════╗"
echo "║                    ✅ LISTO PARA DEMO                      ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""
echo "Próximo paso: Ejecutar ngrok"
echo ""
echo "  bash deploy-ngrok.sh"
echo ""
echo "O comando directo:"
echo ""
echo "  ngrok http 8080"
echo ""
echo "La URL compartida será algo como:"
echo "  https://xxxx-xxxx-xxxx.ngrok.io"
echo ""
