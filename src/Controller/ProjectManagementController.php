<?php
namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectManagementController extends AbstractController
{
    #[Route('/project/management/alltasks', name: 'project_management_all_tasks', methods: ['GET'])]
    public function getAllTasksData(TaskRepository $taskRepository, Request $request): Response
    {
        $projectId = 1;
        $tasks = $taskRepository->findBy(['project' => $projectId]);
        $formattedTasks = $this->formatTasks($tasks);

        return $this->json($formattedTasks);
    }

    #[Route('/project/management/assignedtasks', name: 'project_management_assigned_tasks', methods: ['GET'])]
    public function getAssignedTasksData(TaskRepository $taskRepository, Request $request): Response
    {
        $userId = $this->getUser()->getId();
        $projectId = 1;

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
                'creator' => $task->getTaskCreator()->getUserName()
            ];
        }

        return $formattedTasks;
    }
}
