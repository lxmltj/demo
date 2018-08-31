<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Addresse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AccueilController extends Controller
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, UserInterface $user)
    {

        $qb = $entityManager->createQueryBuilder()
            ->select('c')
            ->from('App\Entity\Contact', 'c')
            ->where('c.user = :user_id')->setParameter('user_id', $user->getId());


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $request->query->get('page', 1),
            $this->container->getParameter('knp_paginator.page_range'));

        return $this->render('accueil/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
