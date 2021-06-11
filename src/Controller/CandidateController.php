<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Form\CandidateType;
use App\Form\UserType;
use App\Repository\CandidateRepository;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use \App\Traits\CustomResetPassword;
use \App\Traits\CustomFiles;

/**
 * @Route("/candidate")
 */
class CandidateController extends AbstractController
{
    use CustomResetPassword, CustomFiles;
    /**
     * @Route("/", name="candidate_index", methods={"GET"})
     */
    public function index(CandidateRepository $candidateRepository): Response
    {
        return $this->render('candidate/index.html.twig', [
            'candidates' => $candidateRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="candidate_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $candidate = new Candidate();
        $form = $this->createForm(CandidateType::class, $candidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($candidate);
            $entityManager->flush();

            return $this->redirectToRoute('candidate_index');
        }

        return $this->render('candidate/new.html.twig', [
            'candidate' => $candidate,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="candidate_show", methods={"GET"})
     */
    public function show(Candidate $candidate, ClientRepository $clientRepository): Response
    {
        $user = $this->getUser();
        $clientConnected = $clientRepository->findOneBy(['user'=> $user]);

        return $this->render('candidate/show.html.twig', [
            'candidate' => $candidate,
            'client'=>$clientConnected
        ]);
    }

  
 /**
     * @Route("/{id}/edit", name="candidate_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Candidate $candidate, SluggerInterface $slugger, UserPasswordEncoderInterface $passwordEncoder): Response
    {   
        $user = $this->getUser();
        $userEmail = $user->getEmail();
        
        $form = $this->createForm(CandidateType::class, $candidate);
        $form2 = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $form2->handleRequest($request);
        $data = $candidate->toArray();
        $lengthData = count($data);
        $profilecompleted = 0;
 
        foreach($data as $dataCandidate){
        if($dataCandidate != null){
            $profilecompleted += 1; 
            }  
        }
        $pourcentageCompleted = $profilecompleted * 100 / $lengthData; 
       
         //dd($pourcentageCompleted);
        if ($form2->isSubmitted() && $form2->isValid()) {
    
            $oldPassword = $form2->get('password')->getData();
            $newPassword = $form2->get('newPassword')->getData();
            $email = $form2->get('email')->getData();
            $this->verifications($user, $userEmail, $email, $passwordEncoder,$oldPassword, $newPassword); 
            }
      
        

        if ($form->isSubmitted() && $form->isValid()) {
                
            $cv = $form->get('curriculumVitae')->getData();
            $profilPicture = $form->get('profilPicture')->getData();
            $passport = $form->get('passportFile')->getData();    
            if($cv !== null){
                $candidate->setCurriculumVitae($this->uploadFiles($cv, 'curriculumVitae_directory', $slugger));
                $this->addFlash('success', 'The CV was updated');
            }
            if($profilPicture !== null){
                $candidate->setProfilPicture($this->uploadFiles($profilPicture, 'profilePicture_directory', $slugger));
                $this->addFlash('success', 'The photo was updated');
            }
            if($passport !== null){
                $candidate->setPassportFile($this->uploadFiles($passport, 'passport_directory', $slugger));
                $this->addFlash('success', 'The passport was updated');
            }
             if($pourcentageCompleted === 100){
            $candidate->setProfileCompleted(1);
            }else{
                $candidate->setProfileCompleted(0);
            }
            $this->getDoctrine()->getManager()->flush();
   

            return $this->redirectToRoute('candidate_edit', [
                'id'=>$candidate->getId()
            ]);
        }


        return $this->render('candidate/edit.html.twig', [
            'candidate' => $candidate,
            'form' => $form->createView(),
            'pourcentageCompleted' => $pourcentageCompleted,
            'form2' => $form2->createView(), 
        ]); 
    }

    /**
     * @Route("/", name="candidate_delete", methods={"POST"})
     */
    public function delete(Request $request, Candidate $candidate): Response
    {
        if ($this->isCsrfTokenValid('delete'.$candidate->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($candidate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('home');
    }
}