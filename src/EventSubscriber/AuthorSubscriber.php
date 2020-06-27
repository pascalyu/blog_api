<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\AuthorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/*
 * Used to add author from token when post article or comment, because they implement both 
 * AuthorInterface
 *  
*/

class AuthorSubscriber implements EventSubscriberInterface
{

    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }
    public function addAuthor(ViewEvent $event)
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$entity instanceof AuthorInterface  || !in_array($method, [Request::METHOD_POST])) {

            return;
        }

        $entity->setAuthor($this->tokenStorage->getToken()->getUser());
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.view' =>  ['addAuthor', EventPriorities::PRE_WRITE],
        ];
    }
}
