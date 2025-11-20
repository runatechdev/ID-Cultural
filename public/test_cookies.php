<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Cookies - Google Translate</title>
    <style>
        body {
            font-family: monospace;
            padding: 20px;
            background: #f5f5f5;
        }
        .info-box {
            background: white;
            padding: 20px;
            margin: 10px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .btn {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        pre {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <h1>🍪 Diagnóstico de Cookies - Google Translate</h1>
    
    <div class="info-box">
        <h3>Información del Servidor (PHP)</h3>
        <pre><?php
echo "Protocolo: " . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'HTTPS' : 'HTTP') . "\n";
echo "Host: " . $_SERVER['HTTP_HOST'] . "\n";
echo "URL completa: " . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "\n\n";

echo "Cookies recibidas por PHP:\n";
if (!empty($_COOKIE)) {
    foreach ($_COOKIE as $name => $value) {
        echo "  $name = $value\n";
    }
} else {
    echo "  (ninguna)\n";
}
        ?></pre>
    </div>
    
    <div class="info-box">
        <h3>Información del Cliente (JavaScript)</h3>
        <pre id="js-info"></pre>
    </div>
    
    <div class="info-box">
        <h3>Acciones de Prueba</h3>
        <button class="btn btn-primary" onclick="testSetCookie()">Establecer Cookie googtrans=/es/en</button>
        <button class="btn btn-success" onclick="checkCookie()">Verificar Cookie</button>
        <button class="btn btn-danger" onclick="clearCookie()">Borrar Cookie</button>
        <button class="btn btn-primary" onclick="reloadPage()">Recargar Página</button>
    </div>
    
    <div class="info-box">
        <h3>Resultado de Pruebas</h3>
        <pre id="test-result"></pre>
    </div>
    
    <script>
        function updateJsInfo() {
            const info = document.getElementById('js-info');
            info.textContent = `Protocolo: ${window.location.protocol}
Hostname: ${window.location.hostname}
URL completa: ${window.location.href}
Es HTTPS: ${window.location.protocol === 'https:' ? 'Sí' : 'No'}

Cookies disponibles en JavaScript:
${document.cookie || '(ninguna)'}`;
        }
        
        function log(message) {
            const result = document.getElementById('test-result');
            result.textContent += `[${new Date().toLocaleTimeString()}] ${message}\n`;
            console.log(message);
        }
        
        function testSetCookie() {
            const isSecure = window.location.protocol === 'https:';
            const value = '/es/en';
            
            log('Intentando establecer cookie...');
            log(`HTTPS: ${isSecure}`);
            
            // Intentar múltiples variantes
            const attempts = [
                `googtrans=${value}; path=/; max-age=31536000; SameSite=Lax`,
                `googtrans=${value}; path=/; max-age=31536000; domain=${window.location.hostname}; SameSite=Lax`,
            ];
            
            if (isSecure) {
                attempts.push(`googtrans=${value}; path=/; max-age=31536000; SameSite=None; Secure`);
                attempts.push(`googtrans=${value}; path=/; max-age=31536000; domain=${window.location.hostname}; SameSite=None; Secure`);
                
                // Para ngrok
                if (window.location.hostname.includes('.')) {
                    const parts = window.location.hostname.split('.');
                    const baseDomain = parts.slice(-2).join('.');
                    attempts.push(`googtrans=${value}; path=/; max-age=31536000; domain=.${baseDomain}; SameSite=None; Secure`);
                }
            }
            
            attempts.forEach((cookieStr, index) => {
                log(`Intento ${index + 1}: ${cookieStr.substring(0, 80)}...`);
                document.cookie = cookieStr;
            });
            
            setTimeout(() => {
                checkCookie();
                updateJsInfo();
            }, 100);
        }
        
        function checkCookie() {
            const cookies = document.cookie.split(';').map(c => c.trim());
            const googtrans = cookies.find(c => c.startsWith('googtrans='));
            
            if (googtrans) {
                log(`✅ Cookie encontrada: ${googtrans}`);
            } else {
                log('❌ Cookie googtrans NO encontrada');
                log(`Cookies actuales: ${document.cookie || '(ninguna)'}`);
            }
            
            updateJsInfo();
        }
        
        function clearCookie() {
            const domains = [
                '',
                window.location.hostname,
                '.' + window.location.hostname
            ];
            
            if (window.location.hostname.includes('.')) {
                const parts = window.location.hostname.split('.');
                domains.push('.' + parts.slice(-2).join('.'));
            }
            
            domains.forEach(domain => {
                const domainStr = domain ? `; domain=${domain}` : '';
                document.cookie = `googtrans=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC${domainStr}`;
                log(`Borrada cookie con dominio: ${domain || '(sin dominio)'}`);
            });
            
            setTimeout(() => {
                checkCookie();
                updateJsInfo();
            }, 100);
        }
        
        function reloadPage() {
            window.location.reload();
        }
        
        // Ejecutar al cargar
        updateJsInfo();
        checkCookie();
    </script>
</body>
</html>
