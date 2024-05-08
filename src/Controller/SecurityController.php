<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $user=$this->getUser();
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        if($user){
            return $this->redirectToRoute('app_mainpage');
        }
        else{return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);}

        
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): Response
    {
        return $this->render('homepage/index.html.twig');
    }
}
