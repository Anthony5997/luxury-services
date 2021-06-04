<?php

namespace App\Controller;

use App\Entity\JobOffer;
use App\Entity\Client;
use App\Entity\Candidate;
use App\Entity\JobType;
use App\Form\JobOfferType;
use App\Repository\JobOfferRepository;
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
        // dd($jobOffer);
        
        
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
     * @Route("/{id}", name="job_offer_show", methods={"GET"})
     */
    public function show(JobOffer $jobOffer): Response
    {
        $user = $this->getUser();
        $jobOffer->setJobType($this->getDoctrine()->getRepository(JobType::class)->findOneBy(array('id' => $jobOffer->getJobType())));
        
        if($user = $this->getUser()){

            $utilisateur= $this->getDoctrine()->getRepository(Candidate::class)->findOneBy(array('user' => $user->getId()));

            if(!$utilisateur){
                $utilisateur= $this->getDoctrine()->getRepository(Client::class)->findOneBy(array('user' => $user->getId()));
            }
            return $this->render('job_offer/show.html.twig', [
                'job_offer' => $jobOffer,
                'client' => $utilisateur,
                'candidate' => $utilisateur,

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
    public function delete(Request $request, JobOffer $jobOffer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$jobOffer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($jobOffer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('job_offer_index');
    }
}
