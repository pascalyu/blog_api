<?php

namespace App\Controller;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Image;
use App\Form\ImageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class UploaderImageAction
{
    private $formfactory;
    private $em;
    private $validator;
    public function __construct(FormFactoryInterface $formfactory, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->formfactory = $formfactory;
        $this->em = $em;
        $this->validator = $validator;
    }
    public function __invoke(Request $request)
    {
        //create a new image instance
        $image = new Image();
        
        $form = $this->formfactory->create(ImageType::class,$image);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($image);
            $this->em->flush();
            $image->setFile(null);
            return $image;
        }
        throw new ValidationException(
            $this->validator->validate($image)
        );
    }
}
