<?php

namespace App\Traits;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

trait CustomResetPassword {
    public function verifications($user, $userEmail, $email, $passwordEncoder,$oldPassword, $newPassword ){
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
    public function resetPassword($passwordEncoder, $newPassword) {
        $user = $this->getUser();
        $newEncodedPassword = $passwordEncoder->encodePassword($user, $newPassword);
        $user->setPassword($newEncodedPassword);
        
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();  
    }
}