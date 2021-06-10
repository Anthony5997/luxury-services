<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\JobOffer;
use App\Entity\Candidate;
use App\Form\ClientType;
use App\Form\UserType;
use App\Repository\ClientRepository;
use App\Repository\JobOfferRepository;
use App\Repository\CandidacyRepository;
use App\Repository\CandidateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Traits\CustomResetPassword;
use \App\Traits\CustomFiles;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/client")
 */
class ClientController extends AbstractController
{
    use CustomResetPassword, CustomFiles;

    /**
     * @Route("/", name="client_index", methods={"GET"})
     */
    public function index(ClientRepository $clientRepository): Response
    {
        return $this->render('client/index.html.twig', [
            'clients' => $clientRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="client_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('client_index');
        }

        return $this->render('client/new.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="client_show", methods={"GET"})
     */
    public function show(Client $client): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

   /**
     * @Route("/{id}/edit", name="client_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Client $client,  UserPasswordEncoderInterface $passwordEncoder, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();
        $userEmail = $user->getEmail();
        $client= $this->getDoctrine()->getRepository(Client::class)->findOneBy(array('user' => $user->getId()));
        $allJobOffer= $this->getDoctrine()->getRepository(JobOffer::class)->findBy(array('client' => $client->getId()));
        $data = $client->toArray();
        $lengthData = count($data);


        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        $form2 = $this->createForm(UserType::class, $user);
        $form2->handleRequest($request);
        
        
        if ($form2->isSubmitted() && $form2->isValid()) {
    
            $oldPassword = $form2->get('password')->getData();
            $newPassword = $form2->get('newPassword')->getData();
            $email = $form2->get('email')->getData();
            
     
        $this->verifications($user, $userEmail, $email, $passwordEncoder,$oldPassword, $newPassword);
        }
       

        if ($form->isSubmitted() && $form->isValid()) {
            $profilPicture = $form->get('profilPicture')->getData();

            if($profilPicture !== null){
                $client->setProfilPicture($this->uploadFiles($profilPicture, 'profilePicture_directory', $slugger));
                $this->addFlash('success', 'The photo was updated');
            }
            $this->getDoctrine()->getManager()->flush();
            

            return $this->redirectToRoute('client_edit', ['id'=>  $client->getId()]);
        }

        return $this->render('client/edit.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
            'form2' => $form2->createView(),
            'dataClient' => $data, 
            'lengthData' => $lengthData,
            'jobOffer' => $allJobOffer,

        ]);
    }

    /**
     * @Route("/{id}", name="client_delete", methods={"POST"})
     */
    public function delete(Request $request, Client $client): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($client);
            $entityManager->flush();
        }

        return $this->redirectToRoute('client_index');
    }

    /**
     * @Route("/{id}", name="client_candidacies", priority=1, methods={"GET"})
     */
    public function candidacies(Request $request, Client $client, ClientRepository $clientRepository, JobOfferRepository $jobOfferRepository, CandidacyRepository $candidacyRepository, CandidateRepository $candidateRepository): Response
    {
        $jobOffers= $jobOfferRepository->findBy(['client'=> $client]);

        //  $candidacy = $candidacyRepository->findBy(['jobOffer' => $jobOffers]);
        //  $candidate = $candidateRepository->findBy(['id' => $candidacy[0]->getCandidate()]);
        //  $candidacy->setCandidate($candidate);
        //  dd($candidacy);

        foreach($jobOffers as $jobOffer){
        }


        return $this->render('client/candidacies.html.twig', [
            'client' => $client,
        ]);
    }
}
