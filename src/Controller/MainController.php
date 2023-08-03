<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $projects = $entityManager->getRepository(Project::class)->findAll();

        return $this->render('main/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/tasks/add', name: 'tasks_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();
        $task->setDone(false);

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();

            // ... perform some action, such as saving the task to the database
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('main/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/tasks/{id}/complete', methods: ['PATCH'])]
    public function complete(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Task */
        $task = $entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException(
                'No task found for id ' . $id
            );
        }

        $task->setDone(true);
        $entityManager->persist($task);
        $entityManager->flush();

        return new Response('OK.');
    }



}
