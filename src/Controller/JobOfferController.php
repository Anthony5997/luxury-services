<?php

namespace App\Controller;

use App\Entity\JobOffer;
use App\Entity\Client;
use App\Entity\Candidate;
use App\Entity\JobType;
use App\Form\JobOfferType;
use App\Repository\JobOfferRepository;
use App\Repository\CandidacyRepository;
use App\Repository\CandidateRepository;
use App\Repository\JobCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/job/offer")
 */
class JobOfferController extends AbstractController
{
    /**
     * @Route("/", name="job_offer_index", methods={"GET"})
     */
    public function index(JobOfferRepository $jobOfferRepository, JobCategoryRepository $jobCategory): Response
    {
        if($user = $this->getUser()){

            $utilisateur= $this->getDoctrine()->getRepository(Candidate::class)->findOneBy(array('user' => $user->getId()));

        if(!$utilisateur){
            $utilisateur= $this->getDoctrine()->getRepository(Client::class)->findOneBy(array('user' => $user->getId()));
        }
        return $this->render('job_offer/index.html.twig', [
            'job_offers' =>  $jobOfferRepository->findAll(),
            'job_category' => $jobCategory->findAll(),
            'client' => $utilisateur,
            'candidate' => $utilisateur,

        ]);

        }else{
            return $this->render('job_offer/index.html.twig', [
                'job_offers' => $jobOfferRepository->findAll(),
                'job_category' => $jobCategory->findAll(),
                ]);

        }
    }

    

    /**
     * @Route("/{id}/new", name="job_offer_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $jobOffer = new JobOffer();
        $form = $this->createForm(JobOfferType::class, $jobOffer);
        $form->handleRequest($request);
        
        
        $user = $this->getUser();
        $client= $this->getDoctrine()->getRepository(Client::class)->findOneBy(array('user' => $user->getId()));
        $jobOffer->setClient($client);
          

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $date = new \DateTime();
            $jobOffer->setDateCreated($date);
            $entityManager->persist($jobOffer);
            $entityManager->flush();

            return $this->redirectToRoute('client_edit', [
                'id' => $client->getId(),
            ]);
        }

        return $this->render('job_offer/new.html.twig', [
            'job_offer' => $jobOffer,
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

 /**
     * @Route("/{id}", name="job_offer_show", methods={"GET", "POST"})
     */
    public function show(JobOffer $jobOffer, CandidacyRepository $candidacyRepository, JobOfferRepository $jobOfferRepository, Request $request, CandidateRepository $candidateRepository): Response
    {   $user = $this->getUser();
        $candidate = $candidateRepository->findOneBy(['user'=> $user->getId()]); 
        if($user->getRoles()[0] === "ROLE_CANDIDATE"){
            $candidacyExist= $candidacyRepository->findJobOfferCandidacy($jobOffer, $candidate);
        }else{
            $candidacyExist = false;
        }
  
        $allJobs= $jobOfferRepository->JobOffersByDateCreated();
        $next = $request->get('next'); 
        $previous = $request->get('previous'); 
        $jobOffer->setJobType($this->getDoctrine()->getRepository(JobType::class)->findOneBy(array('id' => $jobOffer->getJobType())));
       
        $i = 0;
        $lengthJobOffer = count($allJobs);
       
        if($user = $this->getUser()){

            $utilisateur= $this->getDoctrine()->getRepository(Candidate::class)->findOneBy(array('user' => $user->getId()));

            if(!$utilisateur){
                $utilisateur= $this->getDoctrine()->getRepository(Client::class)->findOneBy(array('user' => $user->getId()));
            }

            if($next){
                foreach($allJobs as $jobsOffer){
                    $i+=1; 
                    if($jobOffer->getId()=== $jobsOffer->getId()){ 
                          break;  
                    }
                }
                    if($i >= $lengthJobOffer){
                            $i = 0;
                            $jobOffer= $allJobs[$i];
                        }else{
                           $jobOffer= $allJobs[$i];    
                          
                        }
                        return $this->redirectToRoute('job_offer_show', [
                            'id' => $jobOffer->getId(),
                        ]);
            }
            if($previous){
                foreach($allJobs as $jobsOffer){
                    $i+=1; 
                    if($jobOffer->getId()=== $jobsOffer->getId()){
                          $i -= 2;  
                          break;  
                    }
                }
                if($i === 0){
                    $jobOffer= $allJobs[0];
                }elseif($i <= 0){ 
                    $i = $lengthJobOffer - 1;
                    $jobOffer= $allJobs[$i];
                }else{
                    $jobOffer= $allJobs[$i];     
                }
                return $this->redirectToRoute('job_offer_show', [
                    'id' => $jobOffer->getId(),
                ]);
            }
            return $this->render('job_offer/show.html.twig', [
                'job_offer' => $jobOffer,
                'client' => $utilisateur,
                'candidate' => $utilisateur,
                'candidacyExist' => $candidacyExist,
                'candidacy' => $candidacyExist,

            ]);

        }else{
            return $this->render('job_offer/show.html.twig', [
                'job_offer' => $jobOffer,
            ]);
        }
      

    }

    /**
     * @Route("/{id}/edit", name="job_offer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, JobOffer $jobOffer): Response
    {
        $form = $this->createForm(JobOfferType::class, $jobOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('job_offer_index');
        }

        return $this->render('job_offer/edit.html.twig', [
            'job_offer' => $jobOffer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="job_offer_delete", methods={"POST"})
     */
    public function delete(Request $request, JobOffer $jobOffer, JobOfferRepository $jobOfferRepository): Response
    {
        $client = $jobOfferRepository->findOneBy(["client" => $jobOffer->getClient()]);
        dd($client);
        if ($this->isCsrfTokenValid('delete'.$jobOffer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($jobOffer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('client_edit', [

            'id' => $client->getId(),
        ]);
    }
}
