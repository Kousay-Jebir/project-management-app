<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Team;
use App\Entity\User;
use App\Repository\TeamRepository;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;

class TeamPageController extends AbstractController
{
    #[Route('TeamPage/{id}', name: 'app_team_page')]
    public function index(EntityManagerInterface $entityManager,$id): Response
    {
        
        $user=$this->getUser();
        $team=$entityManager->getRepository(Team:: class)->findOneBy(['id'=>$id]);
        $leader_id=$team->getTeamLeader()->getId();
        $team_leader=$entityManager->getRepository(User:: class)->findOneBy(['id'=>$leader_id]);
        
        return $this->render('team_page/index.html.twig', [
            'controller_name' => 'TeamPageController',
            'team'=>$team,
            'leader'=>$team_leader,
            'user'=>$user,
            'team_id'=>$id
        ]);
    }
    #[Route('/TeamPage/{id}/overview', name: 'team_page_overview', methods: ['GET'])]
    public function getOverviewData(TeamRepository $teamRepository, Request $request,$id, ): Response
    {   
        $team=$teamRepository->findOneBy(['id'=>$id]);
        $projects=$team->getProjects()->toArray();
        $current_project=($projects[count($projects)-1])->getProjectName();
        $users=$team->getUsers()->toArray();
        $user=$this->getUser();
        $tasks=$user->getAssignedTask()->toArray();
        return $this->json(['teamDescription'=>$team->getTeamDescription(), 'nb_projects'=>count($projects), 'current_project'=> $current_project, 'nb_members'=>count($users), 'nb_tasks'=>count($tasks), ]);
    }

    #[Route('/TeamPage/{id}/projects', name: 'team_page_projects', methods: ['GET'])]
    public function getProjectsData(TeamRepository $teamRepository, Request $request,$id): Response
    {   
        $team=$teamRepository->findOneBy(['id'=>$id]);
        $projects=$team->getProjects()->toArray();
       $projectsData=[];
       foreach ($projects as $project) {
        $projectsData[] = [
            'project_id' => $project->getId(),
            'projectName' => $project->getProjectName(),
            'description' => $project->getProjectDescription(),
            
        ];
    }
        return $this->json($projectsData);
    }
    #[Route('/TeamPage/{id}/members', name: 'team_page_members', methods: ['GET'])]
    public function getUsersData(TeamRepository $teamRepository, Request $request,$id): Response
    {   
        $team=$teamRepository->findOneBy(['id'=>$id]);
        $users=$team->getUsers()->toArray();
       $usersData=[];
       foreach ($users as $user) {
        $usersData[] = [
            'user_id' => $user->getId(),
            'userName' => $user->getUserName(),
            'role' => $user->getRoles(),
            
        ];
    }
        return $this->json($usersData);
    }



   
}
