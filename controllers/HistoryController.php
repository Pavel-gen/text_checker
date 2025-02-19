<?php

if (!$pdo) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection error']);
    exit;
}



global $pdo;
$stmt = $pdo->query("SELECT input_text, result_text FROM history ORDER BY created_at DESC");
$history = [];


foreach($stmt as $row)
{
    $result = json_decode($row['result_text'], true);
    $history[] = [
        'input_text' => $row['input_text'],
        'language' => $result['language'],
        'errors' => $result['errors'],
    ];
}
header('Content-Type: application/json');

echo json_encode(array_slice($history, 0, 10));
exit;