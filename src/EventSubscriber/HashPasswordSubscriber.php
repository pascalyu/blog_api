<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Email\Mailer;
use App\Entity\User;
use Swift_Mailer;
use Swift_Message;
use App\Security\TokenGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



/**
 * HashPasword when tryong to post user
 * 
 */
class HashPasswordSubscriber implements EventSubscriberInterface
{

    private $passwordEncoder;
    private $mailer;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, Mailer $mailer)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->mailer = $mailer;
    }


    public function hashPassword(ViewEvent $event)
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$user instanceof User ||  !in_array($method, [Request::METHOD_POST])) {
            return;
        }
        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
        $user->setConfirmationToken(TokenGenerator::generate());

        $this->mailer->sendConfirmationMail($user);
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.view' => ['hashPassword', EventPriorities::PRE_WRITE],
        ];
    }
}
