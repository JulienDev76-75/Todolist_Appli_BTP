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
    public function singleProject(int $id, ProjectRepository $projectRepository, TasksRepository $tasksRepository): Response
    {

        $projectRepository = $this->getDoctrine()->getRepository(Project::class);
        $project = $projectRepository->find($id);

        return $this->render('front/singleProject.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/index/project/{id}/projectAndTask', name: 'projectAndTask', requirements: ['id' => '\d+'])]
    public function projectAndtask(ProjectRepository $projectRepository, TasksRepository $tasksRepository, $tasks = null, int $id): Response
    {
        $projectRepository = $this->getDoctrine()->getRepository(Project::class);
        $project = $projectRepository->find($id);

        $tasksRepository = $this->getDoctrine()->getRepository(Tasks::class, $tasks);
        $tasks = $tasksRepository->findBy(
            ['project' =>  $project],
        );
        // dd($tasks);

        return $this->render('front/projectAndTask.html.twig', [
            'project' => $project,
            'tasks' => $tasks,
        ]);
    }



    #[Route('/index/projet/{id}/nouvelletache', name: 'newTask', requirements: ['id' => '\d+'])]
    public function newTask(Request $request): Response
    {
        $task = new Tasks();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task->setTaskCreation(new \DateTime());
            $task->setTaskStatut("1");

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('registration/newTask.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/index/projet/{id}/supprimertache', name: 'deleteTask', requirements: ['id' => '\d+'])]
    public function deleteTask(Tasks $task): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($task);
        $entityManager->flush();

        return $this->redirectToRoute('projectAndTask', ['id' => $task->getProject()->getId()]);
    }

    #[Route('/index/projet/{id}/supprimerprojet', name: 'deleteProject')]
    public function deleteProject(Project $project): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($project);
        $entityManager->flush();

        return $this->redirectToRoute('index');
    }

    #[Route('/index/projet/{id}/editstatuto1', name: 'editTaskStatutTo1')]
    public function editTaskStatutTo1(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Tasks::class)->find($id);
        $task->setTaskStatut('1');
        $entityManager->flush();

        return $this->redirectToRoute('projectAndTask', ['id' => $task->getProject()->getId()]);
    }

    #[Route('/index/projet/{id}/editstatuto0', name: 'editTaskStatutTo0')]
    public function editTaskStatutTo0(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Tasks::class)->find($id);
        $task->setTaskStatut('0');
        $entityManager->flush();

        return $this->redirectToRoute('projectAndTask', ['id' => $task->getProject()->getId()]);
    }
}

//faire un bouton qui set automatiquement le statut a 0 si tu cliques dessus et si tu recliques dessus il le met a un, ca fait deux boutons donc deux fonctions
