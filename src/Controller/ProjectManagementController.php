<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class ProjectManagementController extends AbstractController
{
    #[Route('/project/management', name: 'app_project_management')]
    public function index(): Response
    {
        return $this->render('project_management/index.html.twig', [
            'controller_name' => 'ProjectManagementController',
        ]);
    }
    #[Route('/project/management/tasks', name: 'project_management_tasks', methods: ['GET'], )]
    public function getTasksData(Request $request): Response
    {
        $data = [
            'message' => 'Data fetched successfully',
        ];

        return $this->json($data);
    }
}
