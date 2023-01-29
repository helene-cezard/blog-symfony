<?php

namespace App\Controller;

use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user/add", name="user_add")
     */
    public function index(): Response
    {
        // Creating the form to create a new user
        $form = $this->createForm(UserType::class);

        return $this->renderForm('user/add.html.twig', [
            'form' => $form,
        ]);
    }
}
