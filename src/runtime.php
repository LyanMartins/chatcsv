<?php

require_once 'vendor/autoload.php';

// require '/var/www/html/vendor/autoload.php';

use parallel\Runtime;

$spreadsheetId = $_ENV['GOOGLE_SPREADSHEET_ID'];
$range = $_ENV['GOOGLE_SHEET_NAME'];
    
$redis = new Redis();
$runtime = new Runtime();
$redis->connect( gethostbyname('redis'), 6379);
$redis->setOption(Redis::OPT_READ_TIMEOUT, -1);

$thread = function($spreadsheetId, $range, $jsonData){

    // na criação da thread ele nao consegue ver o autoload, precisa forçar o require dentro da função
    require '/var/www/html/vendor/autoload.php';

    $client = new Google\Client();
    //$client->setDeveloperKey($_ENV['GOOGLE_API_KEY']);
    $client->setApplicationName('chatcsv');
    $client->setAuthConfig('/var/www/html/secret.json');
    $client->setScopes([Google\Service\Sheets::SPREADSHEETS]);

    $service = new Google\Service\Sheets($client);
   
    $valueRange = new Google_Service_Sheets_ValueRange();
    $valueRange->setValues([(Array)$jsonData]??null);

    $service->spreadsheets_values->append($spreadsheetId, $range, $valueRange, ['valueInputOption' => 'RAW']);

};

while (true) {
    $data = $redis->blPop(['queue'], 0); // 0 = espera infinito
    
    if ($data) {
        $queue = $data[0];
        $message = json_decode($data[1])->message;

        echo "Recebi: " . $message. PHP_EOL;

        $future = $runtime->run($thread ,[$spreadsheetId, $range, $message]);
        
        echo $future->value();
        // processa aqui
    }
}




