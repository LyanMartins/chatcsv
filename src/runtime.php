<?php

require_once 'vendor/autoload.php';



use parallel\Runtime;

$runtime = new Runtime();

$secret = '../secret.json';

$future = $runtime->run(function () {

    require '/var/www/html/vendor/autoload.php';

    echo gethostbyname('redis') . PHP_EOL;
    
    $redis = new Redis();
    $redis->connect( gethostbyname('redis'), 6379);
    $jsonData = $redis->get('messages');
    //echo $jsonData;
    if(!$jsonData){
        return false;
    }

    $spreadsheetId = $_ENV['GOOGLE_SPREADSHEET_ID'];
    $range = $_ENV['GOOGLE_SHEET_NAME'];

    $client = new Google\Client();
    //$client->setDeveloperKey($_ENV['GOOGLE_API_KEY']);
    $client->setApplicationName('chatcsv');
    $client->setAuthConfig('/var/www/html/secret.json');
    $client->setScopes([Google\Service\Sheets::SPREADSHEETS]);

    $service = new Google\Service\Sheets($client);
   
    $valueRange = new Google_Service_Sheets_ValueRange();
    $valueRange->setValues([(Array)$jsonData]??null);

    $write = $service->spreadsheets_values->append($spreadsheetId, $range, $valueRange, ['valueInputOption' => 'RAW']);

});

echo $future->value();