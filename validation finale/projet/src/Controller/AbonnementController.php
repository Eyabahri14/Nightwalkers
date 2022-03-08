<?php

namespace App\Controller;

use App\Repository\AbonnementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AbonnementController extends AbstractController
{
    /**
     * @Route("/abonnements", name="abonnements")
     */
    public function index(AbonnementRepository $abonnementRepository): Response
    {
        return $this->render('abonnement/index.html.twig', [
            'sub'=>$abonnementRepository->findOneBy(['user'=>$this->getUser()]),
        ]);
    }

    /**
     * @Route("/myabonnement", name="myabonnement")
     * @param AbonnementRepository $abonnementRepository
     * @return Response
     */
    public function listAbonnements(AbonnementRepository $abonnementRepository): Response
    {
        return $this->render('abonnement/mySubscription.html.twig', [
            'sub'=>$abonnementRepository->findOneBy(['user'=>$this->getUser()])
        ]);
    }
}
