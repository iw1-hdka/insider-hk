<?php

namespace App\Controller\Front;

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
        return $this->render('front/faq_category/index.html.twig', ['faq_categories' => $faqCategoryRepository->findAll()]);
    }

}
