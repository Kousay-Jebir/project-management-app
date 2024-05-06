<?php
namespace App\Controller;

use App\Form\CommentType;
use App\Form\NewTaskFormType;
use App\Repository\CommentRepository;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;
use App\Entity\Comment;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ProjectManagementController extends AbstractController
{
    #[Route('/project/management/{teamId}/{projectId}', name: 'app_project_management')]
    public function index(ProjectRepository $projectRepository, TaskRepository $taskRepository, Request $request, EntityManagerInterface $entityManager, CommentRepository $commentRepository, TeamRepository $teamRepository, $teamId, $projectId, AuthorizationCheckerInterface $authorizationChecker): Response
    {
        $accessGranted = $authorizationChecker->isGranted('ACCESS_TEAM_PROJECT', ['teamId' => $teamId, 'projectId' => $projectId]);
        if (!$accessGranted) {
            throw $this->createAccessDeniedException('You are not authorized to access this team or project.');
        }

        $user = $this->getUser();
        $team = $teamRepository->find($teamId);
        $project = $projectRepository->find($projectId);

        $statusMap = [
            0 => 'blocked',
            1 => 'progress',
            2 => 'done',
            3 => 'review'
        ];

        $task = new Task();
        $comment = new Comment();
        $form = $this->createForm(NewTaskFormType::class, $task, ['teamMembers' => $team]);
        $form2 = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        $form2->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $task->setTaskStatus(0);
            $task->setTaskCreator($user);
            $task->setProject($project);

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('add_task_success');
        }
        if ($form2->isSubmitted() && $form2->isValid()) {
            $comment = $form2->getData();
            $comment->setAssosiatedUser($user);
            $comment->setAssosiatedProject($project);
            $comment->setCommentDate(new \DateTimeImmutable());

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('add_comment_success');
        }

        // Fetch project tasks
        $tasks = $taskRepository->findBy(['project' => $project]);
        //Fetch comments
        $comments = $commentRepository->findBy(['assosiatedProject' => $project]);

        return $this->render('project_management/index.html.twig', [
            'controller_name' => 'ProjectManagementController',
            'project' => $project,
            'tasks' => $tasks,
            'comments' => $comments,
            'form' => $form,
            'form2' => $form2,
            'statusMap' => $statusMap
        ]);
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

    #[Route('/project/management/delete-task/{taskId}', name: 'delete_task', methods: ['DELETE'])]
    public function deleteTask(TaskRepository $taskRepository, Request $request, $taskId, EntityManagerInterface $entityManager): Response
    {
        // Find the task by ID
        $task = $entityManager->getRepository(Task::class)->find($taskId);
        dump($task);
        // If task not found, return 404 Not Found response
        if (!$task) {
            return new Response('Task not found', Response::HTTP_NOT_FOUND);
        }

        $currentUser = $this->getUser();
        if ($task->getTaskCreator() !== $currentUser) {
            return new Response('Forbidden: You are not allowed to delete this task.', Response::HTTP_FORBIDDEN);
        }
        // Update the status
        $entityManager->remove($task);
        $entityManager->flush();

        // Return a success response
        return new Response(Response::HTTP_OK);
    }
    #[Route('/project/management/add-task-success', name: 'add_task_success')]
    public function taskSuccess(): Response
    {
        return $this->render('project_management/success.html.twig');
    }

    #[Route('/project/management/add-comment-success', name: 'add_comment_success')]
    public function commentSuccess(): Response
    {
        return $this->render('project_management/CommentSuccess.html.twig');
    }




}
