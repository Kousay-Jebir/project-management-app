<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CreateTeamType;
use App\Entity\Team;
use App\Entity\User;
class CreateTeamController extends AbstractController
{
    #[Route('/create/team', name: 'app_create_team')]
    public function index(Request $request,EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(CreateTeamType::class);
        $form->handleRequest($request);
        


        if ($form->isSubmitted() && $form->isValid() ) {
            $formData=$form->getData();
            $teamName = $formData['team_name'];
            $teamDescription=$formData['team_description'];
            $teamCode=$formData['team_join_code'];
            $team=new Team();
            $entityManager->persist($team);
            $entityManager->persist($user);
            
            if($this->isUnique($entityManager,$teamCode)){
                $team->setTeamName($teamName);
                $team->setTeamDescription($teamDescription);
                $team->setTeamJoinCode($teamCode);
                $team->setTeamLeader($user);
                $team->addUser($user);

                $entityManager->flush();
                return $this->redirectToRoute('app_homepage');}
            else{
                return $this->render('create_team/index.html.twig', [
                    'form' => $form,
                ]);
            }
        }

        return $this->render('create_team/index.html.twig', [
            'form' => $form,
        ]);
    }
    private function isUnique($entityManager,$teamCode)
    {
        $team =$entityManager->getRepository(Team::class)->findOneBy(['teamJoinCode' => $teamCode]);
        return !($team);

    }
}
