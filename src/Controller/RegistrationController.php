<?php

namespace App\Controller;

use App\Entity\UserDetails;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    #[Route('/registration', name: 'app_registration')]
    public function register(Request $request, UserPasswordHasherInterface $encoder, EntityManagerInterface $entityManager): Response
    {

        $userDetails = new UserDetails();

        $form = $this->createForm(RegistrationFormType::class, $userDetails);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            //create the user


            $hashpassword = $encoder->hashPassword($userDetails,$form->get('password')->getData());
            $userDetails->setPassword($hashpassword);


//            $userDetails->setPassword($encoder ->isPasswordValid($userDetails, $userDetails->getPassword()));
            $entityManager->persist($userDetails);
            $entityManager->flush();
            $this->addFlash('success', 'Registration successful!');


        }

        return $this->render('registration/index.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    
}
