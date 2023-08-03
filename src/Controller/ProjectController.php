<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectController extends AbstractController
{
    #[Route('/projects', name: 'projects_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $projects = $entityManager->getRepository(Project::class)->findAll();

        return $this->render('projects/list.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/projects/add', name: 'projects_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = new Project();

        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $project = $form->getData();

            // ... perform some action, such as saving the task to the database
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('projects_list');
        }

        return $this->render('projects/add.html.twig', [
            'form' => $form,
        ]);
    }
}
