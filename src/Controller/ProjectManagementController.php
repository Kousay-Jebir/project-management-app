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

            [
                'label' => 'Task 1',
                'status' => 'DONE',
                'assignee' => 'John Doe',
                'due_date' => '2024-05-07',
                'creator' => 'Jane Doe'
            ],
            [
                'label' => 'Task 2',
                'status' => 'BLOCKED',
                'assignee' => 'Alice Smith',
                'due_date' => '2024-05-10',
                'creator' => 'Bob Smith'
            ],
            [
                'label' => 'Task 3',
                'status' => 'DONE',
                'assignee' => 'Alice Smith',
                'due_date' => '2024-05-10',
                'creator' => 'Bob Smith'
            ],
            [
                'label' => 'Task 2',
                'status' => 'REVIEW',
                'assignee' => 'Alice Smith',
                'due_date' => '2024-05-10',
                'creator' => 'Bob Smith'
            ],
        ];

        return $this->json($data);
    }
}
