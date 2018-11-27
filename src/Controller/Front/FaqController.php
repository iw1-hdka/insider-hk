<?php

namespace App\Controller\Front;

use App\Entity\Faq;
use App\Repository\FaqRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/faq", name="faq_")
 */
class FaqController extends AbstractController
{
    /**
     * @Route("/", name="index", methods="GET")
     */
    public function index(FaqRepository $faqRepository): Response
    {
        return $this->render('front/faq/index.html.twig', ['faqs' => $faqRepository->findAll()]);
    }
}
