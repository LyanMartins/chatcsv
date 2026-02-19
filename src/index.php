<?php

// require_once __DIR__ . '/vendor/autoload.php';

require_once '../vendor/autoload.php';

$spreadsheetId = env('GOOGLE_SPREADSHEET_ID');
$range = env('GOOGLE_SHEET_NAME') . '!A1:C5';

$client = new Google\Client();
$client->setDeveloperKey(env('GOOGLE_API_KEY'));
// $client->setAuthConfig('../secret.json');
$client->setApplicationName('chatcsv');
$client->setScopes([Google\Service\Sheets::SPREADSHEETS_READONLY]);

$service = new Google\Service\Sheets($client);
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$values = $response->getValues();  

echo $values ? json_encode($values) : 'No data found.';