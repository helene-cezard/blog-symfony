<?php

namespace App\Controller;

use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article/{id}", name="article")
     */
    public function index(int $id, ArticleRepository $articleRepository, Request $request): Response
    {
        // Creating the form to add a comment under an article
        $form = $this->createForm(CommentType::class);

        // Handling information received with the form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($form->getData());
        }

        $article = $articleRepository->find($id);

        return $this->render('article/index.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }
}
