<?php
namespace App\Controller;

use App\Form\NewTaskFormType;
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
    public function index(ProjectRepository $projectRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // creates a task object and initializes some data for this example
        $task = new Task();

        $form = $this->createForm(NewTaskFormType::class, $task);
        $project = $projectRepository->find(2);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $task = $form->getData();
            $task->setTaskStatus(0);
            $task->setTaskCreator($this->getUser());
            $task->setProject($project);


            $entityManager->persist($task);


            $entityManager->flush();
            return $this->redirectToRoute('add_task_success');
        }


        return $this->render('project_management/index.html.twig', [
            'controller_name' => 'ProjectManagementController',
            'project' => $project,
            'form' => $form,
            'success' => false
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

        $currentUser = $this->getUser();
        if ($task->getAssignedUser() !== $currentUser) {
            return new Response('Forbidden: You are not allowed to update this task.', Response::HTTP_FORBIDDEN);
        }
        // Update the status
        $task->setTaskStatus($statusMap[$status]);
        $entityManager->flush();

        // Return a success response
        return new Response(Response::HTTP_OK);
    }
    #[Route('/project/management/add-task-success', name: 'add_task_success')]
    public function taskSuccess(): Response
    {
        return $this->render('project_management/success.html.twig');
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
}
