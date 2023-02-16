<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
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
    public function show(int $id, ArticleRepository $articleRepository, Request $request, EntityManagerInterface $manager): Response
    {
        $article = $articleRepository->find($id);

        // Creating the form to add a comment under an article
        $form = $this->createForm(CommentType::class);

        // Handling information received with the form
        $form->handleRequest($request);

        if ($this->getUser()) {
            if ($form->isSubmitted() && $form->isValid()) {
                $comment = $form->getData();
    
                //The author is the current user in session
                $author = $this->getUser();
    
                $comment->setAuthor($author);
                $comment->setArticle($article);
    
                $manager->persist($comment);
                $manager->flush();
            }
        }


        return $this->render('article/show.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/article/add", name="article_add")
     */
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        // Creating the form to create a new article
        $form = $this->createForm(ArticleType::class);

        // Handling information received with the form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            //The author is the current user in session
            $author = $this->getUser();

            $article->setAuthor($author);

            $manager->persist($article);
            $manager->flush();

            $this->addFlash('success', 'Votre article a bien été enregistré.');

            return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
        }

        return $this->renderForm('article/form.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/article/update/{id}", name="article_update", requirements={"id": "\d+"})
     */
    public function update(Request $request, EntityManagerInterface $manager, Article $article)
    {
        $this->denyAccessUnlessGranted('EDIT', $article);

        // Creating the form to update the article
        $form = $this->createForm(ArticleType::class, $article);

        // Handling information received with the form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash('success', 'Votre article a bien été mis à jour.');

            return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
        }

        return $this->renderForm('article/form.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/article/delete/{id}", name="article_delete", requirements={"id": "\d+"})
     */
    public function delete(EntityManagerInterface $manager, Article $article)
    {
        $this->denyAccessUnlessGranted('DELETE', $article);
        
        $manager->remove($article);
        $manager->flush();

        $this->addFlash('success', 'Votre article a bien été supprimé.');

        return $this->redirectToRoute('home');
    }
}
