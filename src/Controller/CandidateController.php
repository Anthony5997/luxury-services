<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Form\CandidateType;
use App\Repository\CandidateRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
    public function edit(Request $request, Candidate $candidate, SluggerInterface $slugger): Response
    {

        // dd($fileUploader);
        $form = $this->createForm(CandidateType::class, $candidate);
        $form->handleRequest($request);
        $user = $this->getUser();
        $candidate= $this->getDoctrine()->getRepository(Candidate::class)->findOneBy(array('user' => $user->getId()));
           
            
            if ($form->isSubmitted() && $form->isValid()) {
                
                $cv = $form->get('curriculumVitae')->getData();
                $profilPicture = $form->get('profilPicture')->getData();
                $passport = $form->get('passportFile')->getData();    
                if($cv !== null){
                    $candidate->setCurriculumVitae($this->upload($cv, 'curriculumVitae_directory', $slugger));
                }
                if($profilPicture !== null){
                    $candidate->setProfilPicture($this->upload($profilPicture, 'profilePicture_directory', $slugger));
                }
                if($passport !== null){
                    $candidate->setPassportFile($this->upload($passport, 'passport_directory', $slugger));
                }
                
                $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('candidate_edit', [
                'id'=>$candidate->getId()
            ]);
            }

        return $this->render('candidate/edit.html.twig', [
            'candidate' => $candidate,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="candidate_delete", methods={"POST"})
     */
    public function delete(Request $request, Candidate $candidate): Response
    {
        if ($this->isCsrfTokenValid('delete'.$candidate->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($candidate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('candidate_index');
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

}



/* 
  if($cv !==null && $profilPicture !== null && $passport !== null){

                    $candidate->setCurriculumVitae($this->upload($cv, 'curriculumVitae_directory'));
                    $candidate->setProfilPicture($this->upload($profilPicture, 'profilePicture_directory'));
                    $candidate->setPassportFile($this->upload($passport, 'passport_directory'));
    
                }elseif($cv !==null && $profilPicture !== null && $passport === null){

                    $candidate->setCurriculumVitae($this->upload($cv, 'curriculumVitae_directory'));
                    $candidate->setProfilPicture($this->upload($profilPicture, 'profilePicture_directory'));
                    $userPassportFileValue= $request->get('btnpassportFile'); 
                    $candidate->setPassportFile($userPassportFileValue);
                     
                }elseif($cv !==null && $profilPicture === null && $passport !== null){

                    $candidate->setCurriculumVitae($this->upload($cv, 'curriculumVitae_directory'));
                    $candidate->setPassportFile($this->upload($passport, 'passport_directory'));
                    $userProfilPictureValue= $request->get('btnprofilPicture'); 
                    $candidate->setPassportFile($userProfilPictureValue);
                }
                elseif($cv ===null && $profilPicture !== null && $passport !== null){

                    $candidate->setCurriculumVitae($this->upload($cv, 'curriculumVitae_directory'));
                    $candidate->setPassportFile($this->upload($passport, 'passport_directory'));
                    $userProfilPictureValue= $request->get('btnprofilPicture'); 
                    $candidate->setPassportFile($userProfilPictureValue);
                }
*/