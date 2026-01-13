<?php

function log_security_event(PDO $db, string $action, string $severity, array $extra = []): void
{
    // --- Datos del evento ---
    $userId    = $extra['user_id']   ?? null;
    $resource  = $extra['resource']  ?? ($_SERVER['REQUEST_URI'] ?? null);
    $details   = isset($extra['details']) ? json_encode($extra['details']) : null;

    $ip        = $_SERVER['REMOTE_ADDR']      ?? null;
    $userAgent = $_SERVER['HTTP_USER_AGENT']  ?? null;

    // ----------------------------------------------------------------------
    // 1) GUARDAR EN BASE DE DATOS
    // ----------------------------------------------------------------------
    $stmt = $db->prepare("
        INSERT INTO security_events
        (event_time, user_id, ip_address, user_agent, action, severity, resource, details)
        VALUES (NOW(), :user_id, :ip, :ua, :action, :severity, :resource, :details)
    ");

    $stmt->execute([
        ':user_id'  => $userId,
        ':ip'       => $ip,
        ':ua'       => $userAgent,
        ':action'   => $action,
        ':severity' => $severity,
        ':resource' => $resource,
        ':details'  => $details,
    ]);


    // ----------------------------------------------------------------------
    // 2) GUARDAR EN ARCHIVO TXT DIARIO
    // ----------------------------------------------------------------------

    // Nombre del archivo: audit_YYYY-MM-DD.txt
    $logFile = __DIR__ . "/audit_" . date('Y-m-d') . ".txt";

    // Línea del log
    $logLine =
        "[" . date('Y-m-d H:i:s') . "] " .
        "ACTION=$action | " .
        "SEVERITY=$severity | " .
        "USER=" . ($userId ?? '-') . " | " .
        "IP=$ip | " .
        "RESOURCE=$resource | " .
        "DETAILS=$details | " .
        "AGENT=$userAgent" .
        PHP_EOL;

    // Guardar (agrega si ya existe)
    file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);
}
