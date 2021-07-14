<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Project;
use App\Entity\User;
use App\Entity\Tasks;
use App\Form\AccountType;
use App\Form\CreditType;
use App\Form\RegistrationFormType;
use App\Form\ProjectType;
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
    public function singleAccount(int $id, TasksRepository $tasksRepository, $tasks = null): Response
    {
        $tasksRepository = $this->getDoctrine()->getRepository(Tasks::class);
        $task = $tasksRepository->find($id);

        $projectRepository = $this->getDoctrine()->getRepository(Project::class);
        $project = $projectRepository->find($id);

        return $this->render('front/singleAccount.html.twig', [
            'task' => $task,
            'project' => $project,
        ]);
    }

    // #[Route('/index/nouveauprojet/{projectId}/credit', name: 'newTask', requirements: ['projectId' => '\d+'])]
    // public function newProject(Request $request): Response
    // {
    //     $project = new Tasks();
    //     $form = $this->createForm(TaskType::class, $project);

    //     $form->handleRequest($request);
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $project->setProjetCreation(new \DateTime());
    //         $project->setUser($this->getUser());

    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($project);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('index');
    //     }

    //     return $this->render('registration/newProject.html.twig', [
    //         'form' => $form->createView()
    //     ]);
    // }
}
