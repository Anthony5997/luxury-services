<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\User;
use App\Entity\Client;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\Authenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, Authenticator $authenticator, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            // do anything else you need here, like send an email
            $entityManager = $this->getDoctrine()->getManager();
            $date = new \DateTime();
            $user->setDateCreated($date);
            $entityManager->persist($user);
            $entityManager->flush();
            $user = $userRepository->find($user->getId());
            $role = $request->get('role');
            if($role == 1){
      
                $candidate = new Candidate();
                $candidate->setUser($user);
                $candidate->setProfileCompleted(false);
                $user->setRoles(["ROLE_CANDIDATE"]);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($candidate);
                $entityManager->flush();

            }else{

                $client = new Client();
                $user->setRoles(["ROLE_CLIENT"]);
                $client->setUser($user);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($client);
                $entityManager->flush();
            }

     


            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
