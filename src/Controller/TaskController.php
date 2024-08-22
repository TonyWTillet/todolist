<?php

namespace App\Controller;

use AllowDynamicProperties;
use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Service\UserTaskPermissions;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[AllowDynamicProperties] class TaskController extends AbstractController
{
    private UserTaskPermissions $userTaskPermissions;

    public function __construct(UserTaskPermissions $userTaskPermissions)
    {
        $this->userTaskPermissions = $userTaskPermissions;
    }
    #[Route('/tasks', name: 'task_list')]
    public function listAction(TaskRepository $taskRepository): Response
    {
        if ($this->userTaskPermissions::isAdmin($this->getUser())) {
            $tasks = $taskRepository->findAll();
            return $this->render('task/list.html.twig', [
                'tasks' => $tasks
            ]);
        }
        $tasks = $taskRepository->findBy(['user' => $this->getUser()]);
        return $this->render('task/list.html.twig', [
            'tasks' => $tasks
        ]);
    }


    #[Route('/tasks/create', name: 'task_create', methods: ['GET', 'POST'])]
    public function createAction(Request $request, EntityManagerInterface $entityManager, TaskRepository $taskRepository): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $task->setUser($this->getUser());
            $task->toggle(false);
            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/tasks/{id}/edit', name: 'task_edit',methods: ['GET', 'POST'])]
    public function editAction(Task $task, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->userTaskPermissions::isAdmin($this->getUser()) && !$this->userTaskPermissions::isOwner($this->getUser(), $task)) {
            return $this->redirectToRoute('task_list');
        }

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $task = $form->getData();
            $entityManager->persist($task);
            $entityManager->flush();
            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }


    #[Route('/tasks/{id}/toggle', name: 'task_toggle', methods: ['GET', 'POST'])]
    public function toggleTaskAction(Task $task, EntityManagerInterface $entityManager): Response
    {
        if (!$this->userTaskPermissions->isAdmin($this->getUser()) && !$this->userTaskPermissions->isOwner($this->getUser(), $task)) {
            return $this->redirectToRoute('task_list');
        }

        $task->toggle(!$task->isDone());
        $entityManager->persist($task);
        $entityManager->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/{id}/delete', name: 'task_delete', methods: ['GET', 'POST'])]
    public function deleteTaskAction(Task $task, EntityManagerInterface $entityManager): Response
    {

        $entityManager->remove($task);
        $entityManager->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
