<?php

use Chatcsv\Controller\ChatController;


require_once '../vendor/autoload.php';


app()->get('/message', [new ChatController(), 'getMessages']);

app()->post('/message', [new ChatController(), 'sendMessage']);


app()->run();