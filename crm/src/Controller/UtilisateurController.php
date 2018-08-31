<?php

namespace App\Controller;

use App\Form\UtilisateurType;
use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UtilisateurController extends AbstractController
{

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('utilisateur/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }


    /**
     * @Route("/registre", name="user_registration")
     */
    public function registre(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $user = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->isValid()) {
                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();


                return $this->redirectToRoute('login');
            } else {
                return $this->render(
                    'utilisateur/registre.html.twig',
                    array(
                        'form' => $form->createView(),
                    )
                );
            }

        }


        return $this->render(
            'utilisateur/registre.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }
}