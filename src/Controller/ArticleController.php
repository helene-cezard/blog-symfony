<?php

namespace App\Controller;

use App\Form\ArticleType;
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
     * @Route("/article/{id}", name="article_show", requirements={"id": "\d+"})
     */
    public function show(int $id, ArticleRepository $articleRepository, UserRepository $userRepository, Request $request, EntityManagerInterface $manager): Response
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

            $comment->setAuthor($author);
            $comment->setArticle($article);

            $manager->persist($comment);
            $manager->flush();
        }


        return $this->render('article/show.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/article/add", name="article_add")
     */
    public function add(Request $request, EntityManagerInterface $manager, UserRepository $userRepository): Response
    {
        // Creating the form to create a new article
        $form = $this->createForm(ArticleType::class);

        // Handling information received with the form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            //TODO The author of the comment is the current user in session
            $author = $userRepository->find(1);

            $article->setAuthor($author);

            $manager->persist($article);
            $manager->flush();

            $this->addFlash('success', 'Votre article a bien été enregistré.');
        }

        return $this->renderForm('article/add.html.twig', [
            'form' => $form,
        ]);
    }
}