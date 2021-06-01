<?php

namespace App\Controller;

use App\Entity\Candidate;
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
            
            $user = $this->getUser();
            $candidate= $this->getDoctrine()->getRepository(Candidate::class)->findOneBy(array('user' => $user->getId()));
    
            return $this->render('home/index.html.twig', [
                'controller_name' => 'HomeController',
                'user' => $user,
                'candidate' => $candidate,
            ]);

        }else{
            return $this->render('home/index.html.twig', [
                'controller_name' => 'HomeController',
            ]);
        }
    }
}
