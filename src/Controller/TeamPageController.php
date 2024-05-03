<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Team;
use App\Entity\User;

class TeamPageController extends AbstractController
{
    #[Route('TeamPage', name: 'app_team_page')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user=$this->getUser();
        $team=$user->getTeam()->toArray()[0];
        $leader_id=$team->getTeamLeader()->getId();
        $team_leader=$entityManager->getRepository(User:: class)->findOneBy(['id'=>$leader_id]);
        
        return $this->render('team_page/index.html.twig', [
            'controller_name' => 'TeamPageController',
            'team'=>$team,
            'leader'=>$team_leader
        ]);
    }
}
