#!/bin/bash

# 🔗 Script para obtener y copiar la URL de ngrok al clipboard
# Uso: bash get-ngrok-url.sh

NGROK_API="http://127.0.0.1:4040/api/tunnels"

# Intentar obtener la URL
echo "🔍 Buscando URL de ngrok..."

URL=$(curl -s $NGROK_API 2>/dev/null | grep -o '"public_url":"https:[^"]*' | cut -d'"' -f4 | head -1)

if [ -z "$URL" ]; then
    echo "❌ No se encontró URL de ngrok"
    echo ""
    echo "Posibles razones:"
    echo "1. ngrok no está corriendo"
    echo "2. ngrok aún se está inicializando (espera 5 segundos)"
    echo ""
    echo "Para iniciar ngrok:"
    echo "  bash deploy-ngrok.sh"
    exit 1
fi

echo "✅ URL encontrada:"
echo ""
echo "   $URL"
echo ""

# Copiar al clipboard si está disponible
if command -v xclip &> /dev/null; then
    echo "$URL" | xclip -selection clipboard
    echo "📋 URL copiada al clipboard"
elif command -v xsel &> /dev/null; then
    echo "$URL" | xsel --clipboard --input
    echo "📋 URL copiada al clipboard"
else
    echo "💡 Copiar manualmente: $URL"
fi

echo ""
echo "🌐 Puedes compartir esta URL:"
echo "   - Email"
echo "   - WhatsApp/Telegram"
echo "   - En pantalla/proyector"
echo "   - QR code (generador online)"
