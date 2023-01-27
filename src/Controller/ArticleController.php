<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article/{id}", name="article")
     */
    public function index(int $id, ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->find($id);
        return $this->render('article/index.html.twig', [
            'article' => $article,
        ]);
    }
}
