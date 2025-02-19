<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(405);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$text = $input['text'] ?? null;

if (!$text) {
    http_response_code(400);
    echo json_encode(['error' => 'No text provided']);
    exit;
}



function detectLang($text){
    $ruCount = preg_match_all('/[А-Яа-я]/', $text);
    $enCount = preg_match_all('/[A-Za-z]/', $text);
    return ($ruCount >= $enCount) ? 'ru': 'en';
}

function findWrong($text, $lang)
{
    $reg = $lang == 'ru' 
    ? '/[^\p{Cyrillic}]/u' 
    : '/[^\p{Latin}]/u';
    preg_match_all($reg, $text, $matches);

    return $matches[0] ?? [];
}
$lang = detectLang($text);

$wrong = findWrong($text, $lang);

global $pdo;

$stmt = $pdo->prepare('INSERT INTO history (input_text, result_text) VALUES (:input, :result)');


$result = [
    'language' => $lang,
    'errors' => $wrong
];

$stmt->execute([
    "input" => $text, 
    'result' => json_encode($result),
]);

$parsedResult = [
    'language' => $lang, 
    'errors' => $wrong,
    'input' => $text
];

echo json_encode($parsedResult);
