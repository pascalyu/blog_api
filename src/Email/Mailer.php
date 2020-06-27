<?php

namespace App\Email;

use App\Entity\User;
use Swift;
use Swift_Mailer;
use Swift_Message;
use Twig\Environment;

class Mailer
{

    private $swiftMailer;
    private $twig;

    public function __construct(Swift_Mailer $swiftMailer, Environment $twig)
    {

        $this->swiftMailer = $swiftMailer;
        $this->twig = $twig;
    }

    public function sendConfirmationMail(User $user)
    {


        $body = $this->twig->render('email/token_confirmation.html.twig', [
            "user" => $user,

        ]);
        $message = (new Swift_Message("titre"))
            ->setFrom("pascalyut@gmail.com")
            ->setTo($user->getEmail())
            ->setBody($body);

        $this->swiftMailer->send($message);
    }
}
