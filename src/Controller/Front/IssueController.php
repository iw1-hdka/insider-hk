<?php

namespace App\Controller\Front;

use App\Entity\Issue;
use App\Form\IssueType;
use App\Repository\IssueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/issue", name="issue_")
 */
class IssueController extends AbstractController
{
    /**
     * @Route("/", name="index", methods="GET")
     */
    public function index(IssueRepository $issueRepository): Response
    {
        return $this->render('front/issue/index.html.twig', ['issues' => $issueRepository->findAll()]);
    }

    /**
     * @Route("/new", name="new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $issue = new Issue();
        $form = $this->createForm(IssueType::class, $issue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $issue->setUser($this->getUser());
            $issue->setStatus('new');
            $em = $this->getDoctrine()->getManager();
            $em->persist($issue);
            $em->flush();

            return $this->redirectToRoute('app_front_issue_index');
        }

        return $this->render('front/issue/new.html.twig', [
            'issue' => $issue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods="GET")
     */
    public function show(Issue $issue): Response
    {
        return $this->render('front/issue/show.html.twig', ['issue' => $issue]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods="GET|POST")
     */
    public function edit(Request $request, Issue $issue): Response
    {
        if ($this->getUser() !== $issue->getUser()) {
            $this->createAccessDeniedException();
        }

        $form = $this->createForm(IssueType::class, $issue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_front_issue_index', ['id' => $issue->getId()]);
        }

        return $this->render('front/issue/edit.html.twig', [
            'issue' => $issue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods="DELETE")
     */
    public function delete(Request $request, Issue $issue): Response
    {
        if ($this->isCsrfTokenValid('delete'.$issue->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($issue);
            $em->flush();
        }

        return $this->redirectToRoute('app_front_issue_index');
    }

    /**
     * @Route("/{id}/upvote", name="upvote", methods="POST")
     */
    public function upvoteAction(Issue $issue, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $issue->addUpvote();
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/{id}/downvote", name="downvote", methods="POST")
     */
    public function downvoteAction(Issue $issue, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $issue->addDownvote();
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
