<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            // Confirmation message
            $this->addFlash('success', 'Vous êtes bien enregistré.');

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("user/update/{id}", name="user_update", requirements={"id": "\d+"})
     */
    public function update(User $user, EntityManagerInterface $manager, Request $request, UserPasswordHasherInterface $userPasswordHasher) {

        $this->denyAccessUnlessGranted('EDIT', $user);

        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
                
            $manager->flush();

            $this->addFlash('success', 'Votre profil a bien été modifié.');

            return $this->redirectToRoute('home');
        }

        return $this->renderForm('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);

    }

    /**
     * @Route("user/delete/{id}", name="user_delete", requirements={"id": "\d+"})
     */
    public function delete(User $user, EntityManagerInterface $manager, Session $session) {

        $this->denyAccessUnlessGranted('DELETE', $user);

        $this->container->get('security.token_storage')->setToken(null);

        $manager->remove($user);
        $manager->flush();

        $this->addFlash('success', 'Votre compte a bien été supprimé.');

        return $this->redirectToRoute('home');
    }
}