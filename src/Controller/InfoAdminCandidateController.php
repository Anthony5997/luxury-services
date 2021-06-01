<?php

namespace App\Controller;

use App\Entity\InfoAdminCandidate;
use App\Form\InfoAdminCandidateType;
use App\Repository\InfoAdminCandidateRepository;
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
     * @Route("/new", name="info_admin_candidate_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $infoAdminCandidate = new InfoAdminCandidate();
        $form = $this->createForm(InfoAdminCandidateType::class, $infoAdminCandidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($infoAdminCandidate);
            $entityManager->flush();

            return $this->redirectToRoute('info_admin_candidate_index');
        }

        return $this->render('info_admin_candidate/new.html.twig', [
            'info_admin_candidate' => $infoAdminCandidate,
            'form' => $form->createView(),
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
     * @Route("/{id}", name="info_admin_candidate_delete", methods={"POST"})
     */
    public function delete(Request $request, InfoAdminCandidate $infoAdminCandidate): Response
    {
        if ($this->isCsrfTokenValid('delete'.$infoAdminCandidate->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($infoAdminCandidate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('info_admin_candidate_index');
    }
}
