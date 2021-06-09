<?php

namespace App\Controller;

use App\Entity\InfoAdminCandidate;
use App\Form\InfoAdminCandidateType;
use App\Repository\InfoAdminCandidateRepository;
use App\Repository\CandidateRepository;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/info/admin/candidate")
 */
class InfoAdminCandidateController extends AbstractController
{
    /**
     * @Route("/", name="info_admin_candidate_index", methods={"GET"})
     */
    public function index(InfoAdminCandidateRepository $infoAdminCandidateRepository): Response
    {
        return $this->render('info_admin_candidate/index.html.twig', [
            'info_admin_candidates' => $infoAdminCandidateRepository->findAll(),
        ]);
    }

 /**
     * @Route("/new", name="info_admin_candidate_new", methods={"POST"})
     */
    public function new(Request $request, CandidateRepository $candidateRepository): Response
    {
        $candidate = $candidateRepository->findOneBy(['id'=> $request->get('id')]);
        $infoAdminCandidate = new InfoAdminCandidate();
        $date = new \DateTime();
        if ($request->get('id') && $request->get('notes')) {
            $entityManager = $this->getDoctrine()->getManager();
            
            $infoAdminCandidate-> setDateUpdated($date);
            $infoAdminCandidate-> setNotes($request->get('notes'));
            $infoAdminCandidate-> setCandidate($candidate);
            $entityManager->persist($infoAdminCandidate);
            $entityManager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->render('info_admin_candidate/new.html.twig', [
            'info_admin_candidate' => $infoAdminCandidate,
        ]);
    }

    /**
     * @Route("/{id}", name="info_admin_candidate_show", methods={"GET"})
     */
    public function show(InfoAdminCandidate $infoAdminCandidate): Response
    {
        return $this->render('info_admin_candidate/show.html.twig', [
            'info_admin_candidate' => $infoAdminCandidate,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="info_admin_candidate_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, InfoAdminCandidate $infoAdminCandidate): Response
    {
        $form = $this->createForm(InfoAdminCandidateType::class, $infoAdminCandidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('info_admin_candidate_index');
        }

        return $this->render('info_admin_candidate/edit.html.twig', [
            'info_admin_candidate' => $infoAdminCandidate,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="info_admin_candidate_delete", methods={"GET"})
     */
    public function delete(Request $request, InfoAdminCandidate $infoAdminCandidate): Response
    {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($infoAdminCandidate);
            $entityManager->flush();

        return $this->redirectToRoute('admin');
    }
}
