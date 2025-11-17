#!/bin/bash

# 🌍 Script para desplegar en BACKGROUND y dejar corriendo la demo
# Uso: nohup bash deploy-ngrok-background.sh &

LOGFILE="ngrok-demo.log"
PIDFILE="ngrok-demo.pid"

# Crear timestamp
TIMESTAMP=$(date '+%Y-%m-%d %H:%M:%S')

echo "[$TIMESTAMP] Iniciando despliegue en background..." >> $LOGFILE
echo "[$TIMESTAMP] Log guardado en: $LOGFILE" >> $LOGFILE

# Verificar localhost
if ! curl -s http://localhost:8080 > /dev/null; then
    echo "[$TIMESTAMP] ERROR: localhost:8080 no responde" >> $LOGFILE
    echo "[$TIMESTAMP] Verifica que Docker está corriendo: docker-compose up -d" >> $LOGFILE
    exit 1
fi

echo "[$TIMESTAMP] ✅ localhost:8080 respondiendo" >> $LOGFILE

# Ejecutar ngrok y guardar URL
ngrok http 8080 --log=stdout 2>&1 | tee -a $LOGFILE &
NGROK_PID=$!
echo $NGROK_PID > $PIDFILE

echo "[$TIMESTAMP] ✅ ngrok iniciado (PID: $NGROK_PID)" >> $LOGFILE

# Esperar a que ngrok se inicialice (5 segundos)
sleep 5

# Obtener URL de ngrok
NGROK_URL=$(curl -s http://127.0.0.1:4040/api/tunnels | grep -o '"public_url":"[^"]*' | cut -d'"' -f4 | head -1)

if [ -z "$NGROK_URL" ]; then
    echo "[$TIMESTAMP] ⚠️  No se pudo obtener URL de ngrok automáticamente" >> $LOGFILE
    echo "[$TIMESTAMP] Verifica el log: tail -f $LOGFILE" >> $LOGFILE
    echo "[$TIMESTAMP] O usa: curl http://127.0.0.1:4040/api/tunnels" >> $LOGFILE
else
    echo "[$TIMESTAMP] ✅ URL PÚBLICA: $NGROK_URL" >> $LOGFILE
    echo "" >> $LOGFILE
    echo "╔═════════════════════════════════════════════════════════╗" >> $LOGFILE
    echo "║                   🎉 DEMO EN VIVO                       ║" >> $LOGFILE
    echo "║                                                         ║" >> $LOGFILE
    echo "║   Tu app está disponible en:                           ║" >> $LOGFILE
    echo "║   $NGROK_URL" >> $LOGFILE
    echo "║                                                         ║" >> $LOGFILE
    echo "║   Credenciales:                                        ║" >> $LOGFILE
    echo "║   Editor: editor@test.com / password123               ║" >> $LOGFILE
    echo "║   Admin:  admin@test.com / admin123                   ║" >> $LOGFILE
    echo "║                                                         ║" >> $LOGFILE
    echo "║   Para ver logs: tail -f $LOGFILE                      ║" >> $LOGFILE
    echo "║                                                         ║" >> $LOGFILE
    echo "║   Para detener: kill $NGROK_PID                        ║" >> $LOGFILE
    echo "╚═════════════════════════════════════════════════════════╝" >> $LOGFILE
fi

# Mantener el script corriendo
wait $NGROK_PID
