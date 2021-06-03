<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        if($this->getUser()){ 

            if($this->getUser()->getRoles()[0]== "ROLE_CANDIDATE"){
                $user = $this->getUser();
                $candidate= $this->getDoctrine()->getRepository(Candidate::class)->findOneBy(array('user' => $user->getId()));
        
                return $this->render('home/index.html.twig', [
                    'controller_name' => 'HomeController',
                    'user' => $user,
                    'candidate' => $candidate,
                ]);

            }elseif($this->getUser()->getRoles()[0] == "ROLE_CLIENT") {

                $user = $this->getUser();
                $client= $this->getDoctrine()->getRepository(Client::class)->findOneBy(array('user' => $user->getId()));
        
                return $this->render('home/index.html.twig', [
                    'controller_name' => 'HomeController',
                    'user' => $user,
                    'client' => $client,
                ]);

            }elseif($this->getUser()->getRoles()[0] == "ROLE_ADMIN"){

                $user = $this->getUser();
        
                return $this->render('home/index.html.twig', [
                    'controller_name' => 'HomeController',
                    'user' => $user
                ]);

            }
        }else{

            return $this->render('home/index.html.twig', [
                'controller_name' => 'HomeController'
            ]);
        }
    }
}
