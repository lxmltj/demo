<?php
/**
 * Created by PhpStorm.
 * User: xml
 * Date: 30/08/18
 * Time: 14:40
 */

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Addresse;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ContactController extends Controller
{
    /**
     * @Route("/new", name="contact_new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager, UserInterface $user)
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setUser($user);
            $entityManager->persist($contact);
            $entityManager->flush();
            return $this->redirectToRoute('contact_new');
        }

        return $this->render('contact/form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/remove/{id}", name="contact_remove")
     */
    public function remove($id, EntityManagerInterface $entityManager, UserInterface $user)
    {
        $contact = $entityManager->getRepository(Contact::class)->findOneBy(array(
            'id' => $id,
            'user' => $user->getId()
        ));
        if (null === $contact) {
            return $this->redirectToRoute('accueil');
        }
        $entityManager->remove($contact);
        $entityManager->flush();

        return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/edit/{id}", name="contact_edit")
     */
    public function edit($id, Request $request, EntityManagerInterface $entityManager, UserInterface $user)
    {
        $contact = $entityManager->getRepository(Contact::class)->findOneBy(array(
            'id' => $id,
            'user' => $user->getId()
        ));
        if (null === $contact) {
            return $this->redirectToRoute('accueil');
        }

        $originalAddresses = new ArrayCollection();

        //
        foreach ($contact->getAddresses() as $addresse) {
            $originalAddresses->add($addresse);
        }

        $editForm = $this->createForm(ContactType::class, $contact);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            // Supprimer la relation entre l'adresse et le contact.
            foreach ($originalAddresses as $addresse) {
                if (false === $contact->getAddresses()->contains($addresse)) {
                    $addresse->setContact(null);
                    //$entityManager->persist($addresse);
                    $entityManager->remove($addresse);
                }
            }

            $entityManager->persist($contact);
            $entityManager->flush();

            return $this->redirectToRoute('contact_edit', array('id' => $id));
        }

        return $this->render('contact/form.html.twig', array(
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Email validation callback.
     *
     * @Route("/ajax/email", name="email_valitation")
     */
    public function emailAction(Request $request, ValidatorInterface $validator)
    {
        $email = $request->get('email');
        if (empty($email)) {
            return new JsonResponse(array("status" => "null"));
        }
        $emailConstraint = new Assert\Email();
        $emailConstraint->message = 'Adresse e-mail invalide';

        $errors = $validator->validate(
            $email,
            $emailConstraint
        );

        if (0 === count($errors)) {
            return new JsonResponse(array("status" => "success"));
        } else {
            $errorMessage = $errors[0]->getMessage();
            return new JsonResponse(array("status" => "error", "msg" => $errorMessage));
        }
    }
}
