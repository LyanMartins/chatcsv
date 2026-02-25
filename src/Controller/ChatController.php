<?php

namespace Chatcsv\Controller;

use Google\Client;
use Google\Service\Sheets;
use Redis;

class ChatController
{
    private Redis $redis;
    
    public function __construct(Redis $redis = new Redis) 
    {
        $this->redis = $redis;
    }

    public function sendMessage()
    {
        $this->redis->connect('redis', 6379);
        $this->redis->rPush('queue', json_encode(['message' => 2]));
        response()->json(['message' => 'Hello World!']);
    }

    public function getMessages()
    {
        $spreadsheetId = $_ENV['GOOGLE_SPREADSHEET_ID'];
        $range = $_ENV['GOOGLE_SHEET_NAME'];

        $client = new Client();
        //$client->setDeveloperKey($_ENV['GOOGLE_API_KEY']);
        $client->setApplicationName('chatcsv');
        $client->setAuthConfig('../secret.json');
        $client->setScopes([Sheets::SPREADSHEETS]);

        $service = new Sheets($client);

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();

         response()->json($values);
    }
}
