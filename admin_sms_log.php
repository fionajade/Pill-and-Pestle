<?php
$stmt = $pdo->prepare("
    SELECT s.*, o.order_id, o.full_name
    FROM sms_logs s
    LEFT JOIN orders o ON s.order_id = o.order_id
    ORDER BY s.created_at DESC
    LIMIT :limit OFFSET :offset
");

$limit = 100;
$offset = 0;
$stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
$stmt->execute();
$smsLogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
