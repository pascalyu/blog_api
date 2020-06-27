<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\AuthorInterface;
use App\Repository\UserRepository;
use App\Security\UserConfirmationService;
use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Exception\Config\Filter\NotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/*
 * Used to add author from token when post article or comment, because they implement both 
 * AuthorInterface
 *  
*/

class UserConfirmationSubscriber implements EventSubscriberInterface
{


    private $userConfirmationService;
    public function __construct(UserConfirmationService $userConfirmationService)
    {

        $this->userConfirmationService = $userConfirmationService;
    }
    public function confirmUser(ViewEvent $event)
    {
        $userConfirmation = $event->getControllerResult();

        $request = $event->getRequest();
        if ("api_user_confirmations_post_collection" != $request->get('_route')) {
            return;
        }
        $this->userConfirmationService->confirmationUser($userConfirmation->getConfirmationToken());
        $event->headers->set('Content-Type', 'application/json');
        $event->setResponse(new JsonResponse(Response::HTTP_OK));
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.view' =>  ['confirmUser', EventPriorities::POST_VALIDATE],
        ];
    }
}
