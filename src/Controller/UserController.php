<?php

namespace App\Controller;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user/add", name="user_add")
     */
    public function index(EntityManagerInterface $manager, Request $request): Response
    {
        // Creating the form to create a new user
        $form = $this->createForm(UserType::class);

        // Handling information received with the form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Vous êtes enregistré. Vous pouvez maintenant vous connecter.');
        }

        return $this->renderForm('user/add.html.twig', [
            'form' => $form,
        ]);
    }
}
