<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      itemOperations = {},
 *      collectionOperations = {
 *          "post"
 *      }
 * )
 */
class UserConfirmation{
    
    /**
     * 
     * @Assert\Length(
     *  min=20,
     *  max=20
     * )
     */
    private $confirmationToken;

    /**
     * Get the value of confirmationToken
     */ 
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * Set the value of confirmationToken
     *
     * @return  self
     */ 
    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }
}
