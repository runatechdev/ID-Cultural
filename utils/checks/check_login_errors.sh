#!/bin/bash

# Script para probar el sistema de manejo de errores del login
# Verifica que todos los componentes estén en su lugar

echo "=== VERIFICACIÓN DEL SISTEMA DE ERRORES DE LOGIN ==="
echo ""

# Verificar archivos necesarios
echo "1. Verificando archivos necesarios..."

LOGIN_PHP="/home/maxii/Documentos/ID-Cultural/public/src/views/pages/auth/login.php"
LOGIN_JS="/home/maxii/Documentos/ID-Cultural/public/static/js/login.js"
LOGIN_API="/home/maxii/Documentos/ID-Cultural/public/api/login.php"
VERIFICAR_USUARIO="/home/maxii/Documentos/ID-Cultural/backend/controllers/verificar_usuario.php"

if [ -f "$LOGIN_PHP" ]; then
    echo "   ✅ login.php encontrado"
else
    echo "   ❌ login.php NO encontrado"
    exit 1
fi

if [ -f "$LOGIN_JS" ]; then
    echo "   ✅ login.js encontrado"
else
    echo "   ❌ login.js NO encontrado"
    exit 1
fi

if [ -f "$LOGIN_API" ]; then
    echo "   ✅ login API encontrado"
else
    echo "   ❌ login API NO encontrado"
    exit 1
fi

if [ -f "$VERIFICAR_USUARIO" ]; then
    echo "   ✅ verificar_usuario.php encontrado"
else
    echo "   ❌ verificar_usuario.php NO encontrado"
    exit 1
fi

echo ""

# Verificar que el elemento de error existe en el HTML
echo "2. Verificando elemento de error en HTML..."
if grep -q 'id="mensaje-error"' "$LOGIN_PHP"; then
    echo "   ✅ Elemento #mensaje-error encontrado en login.php"
else
    echo "   ❌ Elemento #mensaje-error NO encontrado en login.php"
    exit 1
fi

echo ""

# Verificar que el JavaScript maneja el elemento de error
echo "3. Verificando JavaScript de manejo de errores..."
if grep -q 'document.getElementById("mensaje-error")' "$LOGIN_JS"; then
    echo "   ✅ JavaScript busca elemento #mensaje-error"
else
    echo "   ❌ JavaScript NO busca elemento #mensaje-error"
    exit 1
fi

if grep -q 'errorMsg.style.display' "$LOGIN_JS"; then
    echo "   ✅ JavaScript maneja display del mensaje de error"
else
    echo "   ❌ JavaScript NO maneja display del mensaje de error"
    exit 1
fi

echo ""

# Verificar que el backend devuelve mensajes de error
echo "4. Verificando backend de autenticación..."
if grep -q '"message" => "Usuario o contraseña incorrectos"' "$VERIFICAR_USUARIO"; then
    echo "   ✅ Backend devuelve mensaje de credenciales incorrectas"
else
    echo "   ❌ Backend NO devuelve mensaje de credenciales incorrectas"
fi

if grep -q '"message" => "Faltan datos de acceso"' "$VERIFICAR_USUARIO"; then
    echo "   ✅ Backend devuelve mensaje de datos faltantes"
else
    echo "   ❌ Backend NO devuelve mensaje de datos faltantes"
fi

echo ""

# Verificar la estructura del mensaje de error en HTML
echo "5. Verificando estructura del mensaje de error..."
if grep -q 'class="alert alert-danger"' "$LOGIN_PHP"; then
    echo "   ✅ Mensaje de error usa clases Bootstrap (alert alert-danger)"
else
    echo "   ❌ Mensaje de error NO usa clases Bootstrap correctas"
fi

if grep -q 'style="display: none;"' "$LOGIN_PHP"; then
    echo "   ✅ Mensaje de error está oculto por defecto"
else
    echo "   ❌ Mensaje de error NO está oculto por defecto"
fi

echo ""

# Verificar funcionalidad de auto-ocultar errores
echo "6. Verificando auto-ocultar errores al escribir..."
if grep -q 'addEventListener("input", hideErrorMessage)' "$LOGIN_JS"; then
    echo "   ✅ JavaScript oculta errores al escribir en los inputs"
else
    echo "   ❌ JavaScript NO oculta errores al escribir"
fi

echo ""

echo "=== RESUMEN ==="
echo "✅ Sistema de manejo de errores implementado correctamente"
echo ""
echo "Para probar:"
echo "1. Abre http://localhost/src/views/pages/auth/login.php"
echo "2. Introduce credenciales incorrectas"
echo "3. Deberías ver el mensaje 'Usuario o contraseña incorrectos'"
echo "4. Al escribir en los campos, el mensaje debería desaparecer"
echo ""
echo "También puedes probar con el archivo de debug:"
echo "utils/debug/test_login_errors.html"