<?php

namespace App\Security;

use App\Email\Mailer;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Liip\ImagineBundle\Exception\Config\Filter\NotFoundException;
use Swift_Message;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserConfirmationService
{

    private $userRepo;
    private $em;

    public function __construct(
        UserRepository $userRepo,
        EntityManagerInterface $em
    ) {
        $this->userRepo = $userRepo;
        $this->em = $em;
    }
    public function confirmationUser(string $tokenString)
    {
        $user = $this->userRepo->findOneBy(['confirmationToken' => $tokenString]);
        if (!$user) {
            throw new EntityNotFoundException();
        }

        $user->setEnabled(true);
        $user->setConfirmationToken(NULL);
        $this->em->persist($user);
        $this->em->flush();
    }
}
