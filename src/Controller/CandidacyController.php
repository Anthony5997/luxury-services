<?php

namespace App\Controller;

use App\Entity\Candidacy;
use App\Entity\Candidate;
use App\Form\CandidacyType;
use App\Repository\CandidacyRepository;
use App\Repository\JobOfferRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/candidacy")
 */
class CandidacyController extends AbstractController
{
    /**
     * @Route("/", name="candidacy_index", methods={"GET"})
     */
    public function index(CandidacyRepository $candidacyRepository): Response
    {
        return $this->render('candidacy/index.html.twig', [
            'candidacies' => $candidacyRepository->findAll(),
        ]);
    }

     /**
     * @Route("/{id}", name="candidacy_new")
     */
    public function new(Request $request, JobOfferRepository $jobOfferRepository, CandidacyRepository $candidacyRepository): Response
    {
        if($user = $this->getUser()){

            $candidate= $this->getDoctrine()->getRepository(Candidate::class)->findOneBy(array('user' => $user->getId()));
            $jobOffer = $jobOfferRepository->findOneBy(array('id' => $request->get('id')));
           // dd("JOB OFFER INDEX", $request->get('id'), "JOB OFFER DU COUP", $jobOffer);

            $candidacy = new Candidacy();
            $newDate = new DateTime();
            $candidacy->setDateCreated($newDate);
            $candidacy->setCandidate($candidate);
            $candidacy->setJobOffer($jobOffer);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($candidacy);
            $entityManager->flush();

            $candidacyExist = $candidacyRepository->findOneBy(array('jobOffer' => $jobOffer->getId()));


            return $this->redirectToRoute('job_offer_show', [
                'id' => $jobOffer->getId(),
                'candidacyExist' => $candidacyExist,
                'candidacy' => $candidacy,
            ]);

        }else{

            $jobOffer = $jobOfferRepository->findOneBy(array('id' => $request->get('id')));

            return $this->redirectToRoute('job_offer_show', [
                'id' => $jobOffer->getId(),
            ]);
        }

    }

    /**
     * @Route("/{id}", name="candidacy_show", methods={"GET"})
     */
    public function show(Candidacy $candidacy): Response
    {
        return $this->render('candidacy/show.html.twig', [
            'candidacy' => $candidacy,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="candidacy_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Candidacy $candidacy): Response
    {
        $form = $this->createForm(CandidacyType::class, $candidacy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('candidacy_index');
        }

        return $this->render('candidacy/edit.html.twig', [
            'candidacy' => $candidacy,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="candidacy_delete", methods={"POST", "GET"})
     */
    public function delete(Request $request, Candidacy $candidacy): Response
    {
        $jobOffer = $candidacy->getJobOffer();
        if ($this->isCsrfTokenValid('delete'.$candidacy->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($candidacy);
            $entityManager->flush();
        }

        
        return $this->redirectToRoute('job_offer_show', [
            'id' => $jobOffer->getId()
            ]);
    }

}
