<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Form\CandidateType;
use App\Form\UserType;
use App\Repository\CandidateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/candidate")
 */
class CandidateController extends AbstractController
{
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
    public function show(Candidate $candidate): Response
    {
        return $this->render('candidate/show.html.twig', [
            'candidate' => $candidate,
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
        
        
        if ($form2->isSubmitted() && $form2->isValid()) {
    
            $oldPassword = $form2->get('password')->getData();
            $newPassword = $form2->get('newPassword')->getData();
            $email = $form2->get('email')->getData();
            
            if($userEmail === $email){
                $passwordValide = $passwordEncoder->isPasswordValid($user, $oldPassword);
            
                if($passwordValide){
                    $this->resetPassword($passwordEncoder,$newPassword);
                    $this->addFlash('success', 'The password was changed');
                   
                }else{
                    $this->addFlash('error', 'The password is wrong');
                    
                }
            }else{
                $this->addFlash('error', 'The email is wrong');
            }
                
    
            }
      
        $candidate= $this->getDoctrine()->getRepository(Candidate::class)->findOneBy(array('user' => $user->getId()));
        

        if ($form->isSubmitted() && $form->isValid()) {
                
            $cv = $form->get('curriculumVitae')->getData();
            $profilPicture = $form->get('profilPicture')->getData();
            $passport = $form->get('passportFile')->getData();    
            if($cv !== null){
                $candidate->setCurriculumVitae($this->upload($cv, 'curriculumVitae_directory', $slugger, $candidate, $form));
            }
            if($profilPicture !== null){
                $candidate->setProfilPicture($this->upload($profilPicture, 'profilePicture_directory', $slugger, $candidate, $form));
            }
            if($passport !== null){
                $candidate->setPassportFile($this->upload($passport, 'passport_directory', $slugger, $candidate, $form));
            }
            
            $this->getDoctrine()->getManager()->flush();
   

            return $this->redirectToRoute('candidate_edit', [
                'id'=>$candidate->getId()
            ]);
        }
                $data = $candidate->toArray();
                $lengthData = count($data);

            return $this->render('candidate/edit.html.twig', [
                'candidate' => $candidate,
                'form' => $form->createView(),
                'dataCandidate' => $data,
                'lengthData' => $lengthData,
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



    public function upload($file, $target_directory ,$slugger){
        if ($file) {
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $file->move(
                            $this->getParameter($target_directory),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    return $newFilename;
                }

        }
    public function resetPassword(UserPasswordEncoderInterface $passwordEncoder, $newPassword)

            {
                $user = $this->getUser();
                $newEncodedPassword = $passwordEncoder->encodePassword($user, $newPassword);
                $user->setPassword($newEncodedPassword);
                
            
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

            }
}