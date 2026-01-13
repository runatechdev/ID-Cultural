<?php

/**
 * Cuenta los intentos fallidos recientes para un email+IP
 * dentro de los últimos 10 minutos.
 */
function count_recent_failures($pdo, $email, $ip) {
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM security_events
        WHERE action = 'LOGIN_FAIL'
        AND details LIKE :email
        AND ip_address = :ip
        AND event_time >= NOW() - INTERVAL 10 MINUTE
    ");

    $stmt->execute([
        ':email' => '%"email":"' . $email . '"%',
        ':ip' => $ip
    ]);

    return (int)$stmt->fetchColumn();
}

