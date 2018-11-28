<?php

namespace App\Controller\Back;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/comment", name="comment_")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/", name="index", methods="GET")
     */
    public function index(CommentRepository $commentRepository): Response
    {
        return $this->render('back/comment/index.html.twig', ['comments' => $commentRepository->findAll()]);
    }

    /**
     * @Route("/{id}", name="show", methods="GET")
     */
    public function show(Comment $comment): Response
    {
        return $this->render('back/comment/show.html.twig', ['comment' => $comment]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods="GET|POST")
     */
    public function edit(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_back_comment_index', ['id' => $comment->getId()]);
        }

        return $this->render('back/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods="DELETE")
     */
    public function delete(Request $request, Comment $comment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush();
        }

        return $this->redirectToRoute('app_back_comment_index');
    }
}
