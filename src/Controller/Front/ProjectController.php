<?php

namespace App\Controller\Front;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project", name="project_")
 */
class ProjectController extends AbstractController
{
    /**
     * @Route("/", name="index", methods="GET")
     */
    public function index(ProjectRepository $projectRepository): Response
    {
        return $this->render('front/project/index.html.twig', ['projects' => $projectRepository->findAll()]);
    }

    /**
     * @Route("/{id}", name="show", methods="GET")
     */
    public function show(Project $project): Response
    {
        return $this->render('front/project/show.html.twig', ['project' => $project]);
    }
}
