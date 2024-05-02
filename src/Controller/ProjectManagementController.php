<?php
namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;

class ProjectManagementController extends AbstractController
{
    #[Route('/project/management', name: 'app_project_management')]
    public function index(ProjectRepository $projectRepository): Response
    {
        $project = $projectRepository->find(2);
        return $this->render('project_management/index.html.twig', [
            'controller_name' => 'ProjectManagementController',
            'project' => $project
        ]);
    }

    #[Route('/project/management/alltasks', name: 'project_management_all_tasks', methods: ['GET'])]
    public function getAllTasksData(TaskRepository $taskRepository, Request $request): Response
    {
        $projectId = 2;
        $tasks = $taskRepository->findBy(['project' => $projectId]);
        $formattedTasks = $this->formatTasks($tasks);

        return $this->json($formattedTasks);
    }

    #[Route('/project/management/assignedtasks', name: 'project_management_assigned_tasks', methods: ['GET'])]
    public function getAssignedTasksData(TaskRepository $taskRepository, Request $request): Response
    {
        $userId = $this->getUser()->getId();
        $projectId = 2;

        $tasks = $taskRepository->findBy(['project' => $projectId, 'assignedUser' => $userId]);
        $formattedTasks = $this->formatTasks($tasks);

        return $this->json($formattedTasks);
    }

    private function formatTasks($tasks): array
    {
        $formattedTasks = [];

        foreach ($tasks as $task) {
            $formattedTasks[] = [
                'label' => $task->getTaskName(),
                'description' => $task->getTaskDescription(),
                'status' => $task->getTaskStatus(),
                'assignee' => $task->getAssignedUser()->getUserName(),
                'due_date' => '14/05/24', // You may need to adjust this
                'creator' => $task->getTaskCreator()->getUserName(),
                'id' => $task->getId()
            ];
        }

        return $formattedTasks;
    }

    #[Route('/project/management/update-status/{taskId}', name: 'update_task_status', methods: ['PATCH'])]
    public function updateTaskStatus(TaskRepository $taskRepository, Request $request, $taskId, EntityManagerInterface $entityManager): Response
    {

        // Reverse mapping from status string to integer
        $statusMap = [
            'blocked' => 0,
            'progress' => 1,
            'done' => 2,
            'review' => 3
        ];
        $requestData = json_decode($request->getContent(), true);
        $status = $requestData['status'];

        // Find the task by ID
        $task = $entityManager->getRepository(Task::class)->find($taskId);

        // If task not found, return 404 Not Found response
        if (!$task) {
            return new Response('Task not found', Response::HTTP_NOT_FOUND);
        }

        // Update the status
        $task->setTaskStatus($statusMap[$status]);
        $entityManager->flush();

        // Return a success response
        return new Response(Response::HTTP_OK);
    }
}
