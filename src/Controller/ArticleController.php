<?php

namespace App\Controller;

use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article/{id}", name="article")
     */
    public function index(int $id, ArticleRepository $articleRepository, UserRepository $userRepository, Request $request, EntityManagerInterface $manager): Response
    {
        $article = $articleRepository->find($id);

        // Creating the form to add a comment under an article
        $form = $this->createForm(CommentType::class);

        // Handling information received with the form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();

            //TODO The author of the comment is the current user in session
            $author = $userRepository->find(1);
            // dd($author);

            $comment->setAuthor($author);
            $comment->setArticle($article);

            $manager->persist($comment);
            $manager->flush();
        }


        return $this->render('article/index.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }
}
