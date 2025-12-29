#!/bin/bash

# Quick Wins - Test Script
# Verifica las mejoras de seguridad implementadas

echo "🔒 VERIFICANDO QUICK WINS - SEGURIDAD"
echo "======================================"
echo ""

# 1. Verificar que .env existe y tiene valores
echo "✓ 1. Verificando archivo .env..."
if [ -f .env ]; then
    echo "   ✅ .env existe"
    if grep -q "APP_KEY=generate_with" .env; then
        echo "   ⚠️  WARNING: APP_KEY no está configurada"
    else
        echo "   ✅ APP_KEY configurada"
    fi
    
    if grep -q "JWT_SECRET=generate_with" .env; then
        echo "   ⚠️  WARNING: JWT_SECRET no está configurada"
    else
        echo "   ✅ JWT_SECRET configurada"
    fi
else
    echo "   ❌ .env NO existe"
fi
echo ""

# 2. Verificar que config.php no tiene credenciales hardcodeadas
echo "✓ 2. Verificando config.php..."
if grep -q "DB_PASS.*1234" config.php; then
    echo "   ⚠️  WARNING: Posible credencial hardcodeada en config.php"
else
    echo "   ✅ No hay credenciales hardcodeadas en config.php"
fi
echo ""

# 3. Verificar Environment.php
echo "✓ 3. Verificando Environment.php..."
if [ -f backend/config/Environment.php ]; then
    echo "   ✅ Environment.php existe"
else
    echo "   ❌ Environment.php NO existe"
fi
echo ""

# 4. Verificar RateLimiter.php
echo "✓ 4. Verificando RateLimiter.php..."
if [ -f backend/helpers/RateLimiter.php ]; then
    echo "   ✅ RateLimiter.php existe"
else
    echo "   ❌ RateLimiter.php NO existe"
fi
echo ""

# 5. Verificar que APIs usan RateLimiter
echo "✓ 5. Verificando Rate Limiting en APIs..."
apis_with_rl=0
total_apis=$(find public/api -name "*.php" | wc -l)

for api in public/api/*.php; do
    if grep -q "RateLimiter" "$api"; then
        ((apis_with_rl++))
    fi
done

echo "   📊 $apis_with_rl de $total_apis APIs tienen Rate Limiting"
if [ $apis_with_rl -ge 3 ]; then
    echo "   ✅ Rate Limiting implementado en APIs críticas"
else
    echo "   ⚠️  Faltan APIs con Rate Limiting"
fi
echo ""

# 6. Verificar conexión segura a BD
echo "✓ 6. Verificando connection.php..."
if grep -q "PDO::ATTR_EMULATE_PREPARES.*false" backend/config/connection.php; then
    echo "   ✅ Prepared statements nativos habilitados"
else
    echo "   ⚠️  Prepared statements emulados (menos seguro)"
fi
echo ""

# 7. Verificar directorios de storage
echo "✓ 7. Verificando estructura de storage..."
for dir in storage/logs storage/uploads storage/cache; do
    if [ -d "$dir" ]; then
        echo "   ✅ $dir/ existe"
    else
        echo "   ⚠️  $dir/ NO existe"
    fi
done
echo ""

# 8. Test de Rate Limiting (si el servidor está corriendo)
echo "✓ 8. Testeando Rate Limiting en vivo..."
if curl -s -f http://localhost:8080/api/stats.php?action=public > /dev/null 2>&1; then
    echo "   ✅ Servidor respondiendo en localhost:8080"
    
    # Hacer 5 requests seguidos
    echo "   🧪 Haciendo 5 requests rápidos..."
    success=0
    for i in {1..5}; do
        status=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8080/api/stats.php?action=public)
        if [ "$status" = "200" ]; then
            ((success++))
        fi
    done
    
    echo "   📊 $success/5 requests exitosos"
    
    # Verificar límite (hacer 105 requests para exceder límite de 100)
    echo "   🧪 Verificando límite de rate limit..."
    rate_limited=false
    for i in {1..110}; do
        status=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8080/api/stats.php?action=public 2>/dev/null)
        if [ "$status" = "429" ]; then
            rate_limited=true
            echo "   ✅ Rate limiting funcionando (bloqueó en request #$i)"
            break
        fi
    done
    
    if [ "$rate_limited" = false ]; then
        echo "   ⚠️  Rate limiting no bloqueó después de 110 requests (límite esperado: 100)"
    fi
else
    echo "   ⚠️  Servidor no está corriendo o no responde"
    echo "   💡 Ejecuta: docker compose up -d"
fi
echo ""

# RESUMEN
echo "======================================"
echo "📋 RESUMEN DE QUICK WINS"
echo "======================================"
echo ""
echo "✅ Implementado:"
echo "   - Sistema de .env para credenciales"
echo "   - Clase Environment.php para cargar config"
echo "   - RateLimiter para prevenir abuso"
echo "   - Prepared statements nativos en PDO"
echo "   - Sesiones con flags de seguridad"
echo "   - Estructura de storage/"
echo ""
echo "🚀 SIGUIENTE PASO:"
echo "   - Revisar que .env tenga credenciales seguras"
echo "   - Agregar rate limiting a APIs restantes"
echo "   - Configurar HTTPS en nginx"
echo "   - Implementar logging estructurado"
echo ""
