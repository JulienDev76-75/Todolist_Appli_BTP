<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Project;
use App\Entity\User;
use App\Entity\Tasks;
use App\Form\RegistrationFormType;
use App\Form\ProjectType;
use App\Form\TaskType;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use App\Repository\TasksRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Require ROLE_ADMIN for *every* controller method in this class.
 *
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */

class FrontController extends AbstractController

{
    #[Route('/index', name: 'index')]
    public function index(): Response
    {
        $projetRepository = $this->getDoctrine()->getRepository(Project::class);
        $projects = $projetRepository->findby(
            ['user' => $this->getUser()],
        );

        return $this->render('front/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/index/nouveauprojet', name: 'newProject')]
    public function newProject(Request $request): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $project->setProjetCreation(new \DateTime());
            $project->setUser($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('registration/newProject.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/index/projet/{id}', name: 'singleProject', requirements: ['id' => '\d+'])]
    public function singleProject(int $id, TasksRepository $tasksRepository, $tasks = null): Response
    {
        $tasksRepository = $this->getDoctrine()->getRepository(Tasks::class);
        $task = $tasksRepository->find($id);

        $projectRepository = $this->getDoctrine()->getRepository(Project::class);
        $project = $projectRepository->find($id);

        return $this->render('front/singleProject.html.twig', [
            'task' => $task,
            'project' => $project,
        ]);
    }

    #[Route('/index/projet/{id}/nouvelletache', name: 'newTask', requirements: ['id' => '\d+'])]
    public function newTask(Request $request, ProjectRepository $projectRepository, int $id): Response
    {
        $task = new Tasks();
        $form = $this->createForm(TaskType::class, $task);

        $project = new Project();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task->setTaskCreation(new \DateTime());
            $task->setTaskStatut(1);
            $project = $projectRepository->find($id);
            $task->setProject($project);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('singleProject', ["id" => $project->getUser()->getId()]);
        }

        return $this->render('registration/newTask.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/index/projet/{id}/supprimerlatache', name: 'deleteTask', requirements: ['id' => '\d+'])]
    public function deleteTask(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Tasks::class)->find($id);

        $entityManager->remove($task);
        $entityManager->flush();

        return $this->redirectToRoute('index');
    }
}
