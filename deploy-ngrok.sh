#!/bin/bash

# Script para desplegar ID-Cultural en ngrok
# Uso: ./deploy-ngrok.sh

echo "🚀 Iniciando ngrok para ID-Cultural..."
echo ""
echo "📱 Tu aplicación será accesible desde cualquier navegador"
echo "🔗 La URL será visible a continuación:"
echo ""

# Verificar que la aplicación esté corriendo
if ! curl -s http://localhost:8080 > /dev/null; then
    echo "❌ ERROR: La aplicación no está corriendo en localhost:8080"
    echo "Inicia la aplicación primero (docker-compose up, etc.)"
    exit 1
fi

# Ejecutar ngrok
ngrok http 8080 --log=stdout --config /home/maxii/.config/ngrok/ngrok.yml

# Cuando termina, mostrar mensaje
echo ""
echo "✅ Sesión de ngrok finalizada"
