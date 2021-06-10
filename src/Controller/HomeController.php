<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\Client;
use App\Repository\JobCategoryRepository;
use App\Repository\JobOfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(JobOfferRepository $jobOfferRepository, JobCategoryRepository $jobCategory): Response
    {
        if($this->getUser()){ 

            if($this->getUser()->getRoles()[0]== "ROLE_CANDIDATE"){
                $user = $this->getUser();
                $candidate= $this->getDoctrine()->getRepository(Candidate::class)->findOneBy(array('user' => $user->getId()));
        
                return $this->render('home/index.html.twig', [
                    'controller_name' => 'HomeController',
                    'user' => $user,
                    'candidate' => $candidate,
                    'job_offers' => $jobOfferRepository->findAllByLimit(),
                    'job_category' => $jobCategory->findAll(),
                ]);

            }elseif($this->getUser()->getRoles()[0] == "ROLE_CLIENT") {

                $user = $this->getUser();
                $client= $this->getDoctrine()->getRepository(Client::class)->findOneBy(array('user' => $user->getId()));
        
                return $this->render('home/index.html.twig', [
                    'controller_name' => 'HomeController',
                    'user' => $user,
                    'client' => $client,
                    'job_offers' => $jobOfferRepository->findAllByLimit(),
                    'job_category' => $jobCategory->findAll(),
                ]);

            }elseif($this->getUser()->getRoles()[0] == "ROLE_ADMIN"){

                $user = $this->getUser();
        
                return $this->render('home/index.html.twig', [
                    'controller_name' => 'HomeController',
                    'user' => $user,
                    'job_offers' => $jobOfferRepository->findAllByLimit(),
                    'job_category' => $jobCategory->findAll(),
                ]);

            }
        }else{

            return $this->render('home/index.html.twig', [
                'controller_name' => 'HomeController',
                'job_offers' => $jobOfferRepository->findAllByLimit(),
                'job_category' => $jobCategory->findAll(),
            ]);
        }
    }

        /**
     * @Route("/about-us", priority=1, name="about_us", methods={"GET"})
     */
    public function show(): Response
    { $user = $this->getUser();
        $utilisateur= $this->getDoctrine()->getRepository(Candidate::class)->findOneBy(array('user' => $user->getId()));

        if(!$utilisateur){
            $utilisateur= $this->getDoctrine()->getRepository(Client::class)->findOneBy(array('user' => $user->getId()));
        }


        return $this->render('home/about-us.html.twig', [

            'client' => $utilisateur,
            'candidate' => $utilisateur,

        ]);
    }

         /**
     * @Route("/contact", priority=2, name="contact", methods={"GET"})
     */
    public function contact(): Response
    { 
        if($user = $this->getUser()){
            
            $utilisateur= $this->getDoctrine()->getRepository(Candidate::class)->findOneBy(array('user' => $user->getId()));
    
            if(!$utilisateur){
                $utilisateur= $this->getDoctrine()->getRepository(Client::class)->findOneBy(array('user' => $user->getId()));
            }
    
    
            return $this->render('home/contact.html.twig', [
    
                'client' => $utilisateur,
                'candidate' => $utilisateur,
    
            ]);

        }else{
            return $this->render('home/contact.html.twig');
        }
    }

    /**
     * @Route("/access-denied", priority=2, name="access_denied", methods={"GET"})
     */
    public function access_denied(): Response
    { 
        if($user = $this->getUser()){
            
           // add flash message 
    
    
            //redirect to route home
            return $this->redirectToRoute('home');

        }else{
            // add flash message 
    
    
            //redirect to route login
            return $this->redirectToRoute('app_login');

        }
    }
}
