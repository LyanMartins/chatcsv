<?php

// require_once __DIR__ . '/vendor/autoload.php';

require_once '../vendor/autoload.php';

$spreadsheetId = $_ENV['GOOGLE_SPREADSHEET_ID'];
$range = $_ENV['GOOGLE_SHEET_NAME'] . '!A1:C5';

$client = new Google\Client();
//$client->setDeveloperKey($_ENV['GOOGLE_API_KEY']);
$client->setApplicationName('chatcsv');
$client->setAuthConfig('../secret.json');
$client->setScopes([Google\Service\Sheets::SPREADSHEETS]);

$service = new Google\Service\Sheets($client);

// var_dump($client->getAccessToken());

$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$values = $response->getValues();  


// // $values = [...$values, ['teste2']];

// // $valueRange = new Google_Service_Sheets_ValueRange();
// // $valueRange->setValues($values);


// // $write = $service->spreadsheets_values->append($spreadsheetId, $range, $valueRange, ['valueInputOption' => 'RAW']);


// // $response = $service->spreadsheets_values->get($spreadsheetId, $range);
// // $values = $response->getValues();  


// echo $values ? json_encode($values) : 'No data found.';


    $redis = new Redis();
    $redis->connect('redis', 6379);
    $json_data = $redis->get('messages');
    echo $json_data;

    $spreadsheetId = $_ENV['GOOGLE_SPREADSHEET_ID'];
    $range = $_ENV['GOOGLE_SHEET_NAME'];

    $client = new Google\Client();
    //$client->setDeveloperKey($_ENV['GOOGLE_API_KEY']);
    $client->setApplicationName('chatcsv');
    $client->setAuthConfig('../secret.json');
    $client->setScopes([Google\Service\Sheets::SPREADSHEETS]);

    $service = new Google\Service\Sheets($client);


    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();  
    
    $values = ($values) ? array_push($values, [$json_data]) : [$json_data];

    var_dump($values);

    echo "<br>";
    $valueRange = new Google_Service_Sheets_ValueRange();
    $valueRange->setValues($values);

    //$write = $service->spreadsheets_values->append($spreadsheetId, $range, $valueRange, ['valueInputOption' => 'RAW']);

    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();  

    response()->json(['message' => 'Hello World!', 'details' => $json_data, 'spreadsheet_values' => $values]);