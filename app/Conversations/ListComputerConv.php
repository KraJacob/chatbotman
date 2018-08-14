<?php
/**
 * Created by PhpStorm.
 * User: STEINER
 * Date: 13/08/2018
 * Time: 17:07
 */

namespace App\Conversations;


use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class ListComputerConv extends Conversation
{
    private $num;
    private $date;
    private $question;
    private $zone;
    /**
     * @return mixed
     */
    public function run()
    {
        $this->askWhen();
    }

    public function askWhen()
    {
        $this->ask('Vous tapez à la bonne porte, dites moi quand le voulez-vous?', function (Answer $answer){
            $this->date = new \DateTime($answer->getText());
            $this->say(
                'd\'accord pour '.
                $this->date->format('Y-m-d H:i:sP')
            );
            $this->askHowMany();
        });

    }

    public function askHowMany()
    {
        $this->ask('Quel votre budget ?', function (Answer $answer){
            $this->num = (int) $answer->getText();
            $this->say($this->num ." ? Cool!");
            $this->computer();
        });
    }

    public function computer()
    {
        $this->say(sprintf(
            "Votre commande a été prise en compte et sera traitée le plus tôp possible.
             Resumé de la commande : Ordinateur portable d'une valeur de %d F pour le ",
            $this->num//, $this->date->format('Y-m-d H:i:sP')
        ));

        $this->ask_zone();

    }

    public function ask_zone()
    {
        $this->question =  Question::create('Dans quelle commune devons faire la livraison ?')
            ->fallback('Vous ne pouvez pas poser une question')
            ->callbackId('customer_zone')
            ->addButtons([
                Button::create('Cocody')->value("Cocody"),
                Button::create('Marcory')->value("Marcory"),
                Button::create('Yopougon')->value("Yopougon"),
                Button::create('Plateau')->value("plateau")
            ]);
        $this->ask($this->question, function (Answer $answer){
            if ($answer->isInteractiveMessageReply()){
                $this->zone = $answer->getValue();
                $this->say('Merci et à bientôt');
            }else{
                $this->say('Vous devez choisir la commune pour la livraison');
                $this->ask_zone();
            }

        });
    }


}