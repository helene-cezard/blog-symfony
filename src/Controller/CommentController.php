<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment/update/{id}", name="comment_update", requirements={"id": "\d+"})
     */
    public function update(Comment $comment, Request $request, EntityManagerInterface $manager): Response
    {
        $this->denyAccessUnlessGranted('EDIT', $comment);

        // Creating the form to update the comment
        $form = $this->createForm(CommentType::class, $comment);

        // Handling information received with the form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash('success', 'Votre commentaire a bien été mis à jour.');

            return $this->redirectToRoute('article_show', ['id' => $comment->getArticle()->getId()]);
        }

        return $this->renderForm('comment/form.html.twig', [
            'form' => $form,
            'comment' => $comment

        ]);
    }

    /**
     * @Route("/comment/delete/{id}", name="comment_delete", requirements={"id": "\d+"})
     */
    public function delete(Comment $comment, EntityManagerInterface $manager)
    {
        $this->denyAccessUnlessGranted('DELETE', $comment);

        $article = $comment->getArticle();
        
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash('success', 'Votre commentaire a bien été supprimé.');

        return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
    }
}
