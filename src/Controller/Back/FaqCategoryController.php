<?php

namespace App\Controller\Back;

use App\Entity\FaqCategory;
use App\Form\FaqCategoryType;
use App\Repository\FaqCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/faq/category", name="faq_category_")
 */
class FaqCategoryController extends AbstractController
{
    /**
     * @Route("/", name="index", methods="GET")
     */
    public function index(FaqCategoryRepository $faqCategoryRepository): Response
    {
        return $this->render('back/faq_category/index.html.twig', ['faq_categories' => $faqCategoryRepository->findAll()]);
    }

    /**
     * @Route("/new", name="new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $faqCategory = new FaqCategory();
        $form = $this->createForm(FaqCategoryType::class, $faqCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($faqCategory);
            $em->flush();

            return $this->redirectToRoute('app_back_faq_category_index');
        }

        return $this->render('back/faq_category/new.html.twig', [
            'faq_category' => $faqCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods="GET")
     */
    public function show(FaqCategory $faqCategory): Response
    {
        return $this->render('back/faq_category/show.html.twig', ['faq_category' => $faqCategory]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods="GET|POST")
     */
    public function edit(Request $request, FaqCategory $faqCategory): Response
    {
        $form = $this->createForm(FaqCategoryType::class, $faqCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_back_faq_category_index', ['id' => $faqCategory->getId()]);
        }

        return $this->render('back/faq_category/edit.html.twig', [
            'faq_category' => $faqCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods="DELETE")
     */
    public function delete(Request $request, FaqCategory $faqCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$faqCategory->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($faqCategory);
            $em->flush();
        }

        return $this->redirectToRoute('app_back_faq_category_index');
    }
}
