<?php

namespace App\Controller;

use App\Security\UserConfirmationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/default", name="default")
     */
    public function index()
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/confirm-user/{token}", name="default_confirm_token")
     */
    public function confirmUser(string $token, UserConfirmationService $userConfirmationService)
    {
        $userConfirmationService->confirmationUser($token);
        return $this->redirectToRoute("default");
    }
}
