<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CreateProjectType;
use App\Entity\Team;
use App\Entity\User;
use App\Entity\Project;
class CreateProjectController extends AbstractController
{
    #[Route('/create/project/{id}', name: 'app_create_project')]
    public function index(Request $request,EntityManagerInterface $entityManager,$id): Response
    {
        $user = $this->getUser();
        $project=new Project();
        $team=$entityManager->getRepository(Team:: class)->findOneBy(['id'=>$id]);
        $form = $this->createForm(CreateProjectType::class,$project);

        $form->handleRequest($request);
        



        if ($form->isSubmitted() && $form->isValid() ) {
            $project->setTeam($team);
            $entityManager->persist($project);
            $entityManager->flush();

            

            return $this->redirectToRoute('app_mainpage');

        }

        return $this->render('create_project/index.html.twig', [
            'form' => $form,
        ]);
    }
}
