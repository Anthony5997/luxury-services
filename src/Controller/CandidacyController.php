<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\JobOfferRepository;
use App\Repository\CandidacyRepository;
use App\Entity\Candidate;
use App\Entity\Candidacy;

/**
 * @Route("/candidacy")
 */

class CandidacyController extends AbstractController
{
    /**
     * @Route("/{id}", name="candidacy")
     */
    public function index(Request $request, JobOfferRepository $jobOfferRepository, CandidacyRepository $candidacyRepository): Response
    {
        if($user = $this->getUser()){

            $candidate= $this->getDoctrine()->getRepository(Candidate::class)->findOneBy(array('user' => $user->getId()));
            $jobOffer = $jobOfferRepository->findOneBy(array('id' => $request->get('id')));
           // dd("JOB OFFER INDEX", $request->get('id'), "JOB OFFER DU COUP", $jobOffer);

            $candidacy = new Candidacy();
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
     * @Route("/{id}/delete", name="candidacy_delete", methods={"POST"})
     */
    public function delete(Request $request, Candidacy $candidacy): Response
    {
        
        $jobOffer = $candidacy->getJobOffer();
        
        if ($this->isCsrfTokenValid('delete'.$candidacy->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($candidacy);
            $entityManager->flush();
        }

        return $this->redirectToRoute('candidacy', [
            'id' => $jobOffer->getId()
            ]);
    }
}
