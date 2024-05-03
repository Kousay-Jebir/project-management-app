<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Team;

class MainpageController extends AbstractController
{
    #[Route('/mainpage', name: 'app_mainpage')]
    public function index(): Response
    {
        


        

        $user = $this->getUser();
    
        $teams=$user->getTeam()->toArray();
        
        
        





        if ($user) {
            return $this->render('mainpage/index.html.twig', [
                'controller_name' => 'MainpageController',
                'teams'=>$teams,
            ]);
            
        } else {
            return $this->redirectToRoute('app_homepage');


        }

    }
}
