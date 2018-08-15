<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use App\Conversations\ExampleConversation;

class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $botman = app('botman');

        $botman->listen();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinker()
    {
        return view('tinker');
    }

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public function startConversation(BotMan $bot)
    {
        $bot->startConversation(new ExampleConversation());
    }

    /**
     *
     */
    public function bot(Request $request)
    {
         $data = $request->all();
        // get user's id
        $id = $data["entry"][0]["messaging"][0]["sender"]["id"];
        //get message
        $sendMessage = $data["entry"][0]["messaging"][0]["message"];
        if(!empty($sendMessage)){
            $this->sendMessage($id, "Your bot is now connect to facebook !");
        }

    }

    public function sendMessage($recipienId, $message)
    {

        $messageData = [
                    "recipient" =>["id"=>$recipienId],
                     "message"  =>["text"=>$message]
                       ];
        $ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token=' . env("FACEBOOK_TOKEN"));

        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type:application/json"]);
        curl_setopt($ch, CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
        curl_exec($ch);
        curl_close($ch);

    }
}
