<?php
use App\Http\Controllers\BotManController;
use App\Conversations\ListComputerConv;
use BotMan\BotMan\Middleware\ApiAi;


$botman = resolve('botman');

$dialogflow = ApiAi::create('2a9e73da4eb047de9ab9d9e90ed5b2f6')->listenForAction();
// Apply global "received" middleware
$botman->middleware->received($dialogflow);
// Apply matching middleware per hears command
$botman->hears('dialogflow_greet', function (\BotMan\BotMan\BotMan $bot) {
    // The incoming message matched the "my_api_action" on Dialogflow
    // Retrieve Dialogflow information:
    /* $extras = $bot->getMessage()->getExtras();
     $apiReply = $extras['apiReply'];
     $apiAction = $extras['apiAction'];
     $apiIntent = $extras['apiIntent']; */

    $bot->reply("Connexion ok avec dialogflow");
})->middleware($dialogflow);

// Apply global "received" middleware
$botman->middleware->received($dialogflow);


$botman->hears('Je suis {name}', function ($bot, $name) {
    $bot->reply('Bienvenu  '.$name);
});



$botman->hears(
    'Je cherche un ordinateur portable',
    function ($bot){
        $bot->startConversation(new ListComputerConv());
    }
);
$botman->hears('Start conversation', BotManController::class.'@startConversation');
$botman->hears('Ordinateur portable', function ($bot){
    $message = Message::create('Nous avons une promotion en cours')
        ->image('https://inpulsclic.s3.us-west-2.amazonaws.com/customer-logo/SNGI_logo.jpg');
    $bot->reply($message);
});
