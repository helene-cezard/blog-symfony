<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        foreach ($articles as $article) {
            // dump($article->getContent());
            $article->setContent(substr($article->getContent(), 0, 200) . '…');
        };

        return $this->render('home/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}
